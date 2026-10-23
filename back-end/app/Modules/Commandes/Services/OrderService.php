<?php

namespace App\Modules\Commandes\Services;

use App\Modules\Auth\Models\Etudiant;
use App\Modules\Auth\Models\User;
use App\Modules\Commandes\Enums\OrderStatus;
use App\Modules\Commandes\Enums\ServiceMode;
use App\Modules\Commandes\Models\Order;
use App\Modules\Commandes\Models\OrderItem;
use App\Modules\Fidelite\Exceptions\InsufficientPointsException;
use App\Modules\Fidelite\Services\LoyaltyService;
use App\Modules\Fidelite\Services\ReferralService;
use App\Modules\Menu\Models\MenuItem;
use App\Modules\Restaurants\Models\Restaurant;
use App\Shared\Enums\Role;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class OrderService
{
    private const WITH = ['items.menuItem:id_element,nom'];

    public function __construct(
        private readonly LoyaltyService $loyalty,
        private readonly ReferralService $referrals,
    ) {
    }

    /**
     * @param array{id_restaurant:int, mode_service:string, adresse_livraison?:?string, heure_arrivee?:?string, utiliser_points?:bool, articles:array<int, array{id_element:int, quantite:int}>} $data
     * @throws ValidationException
     */
    public function create(Etudiant $client, array $data): Order
    {
        $restaurant = Restaurant::find($data['id_restaurant']);
        if (! $restaurant || ! $restaurant->isOpen()) {
            throw ValidationException::withMessages(['id_restaurant' => 'Ce restaurant est fermé ou introuvable.']);
        }

        $mode = ServiceMode::from($data['mode_service']);
        if ($mode === ServiceMode::Delivery && empty($data['adresse_livraison'])) {
            throw ValidationException::withMessages(['adresse_livraison' => "L'adresse de livraison est obligatoire."]);
        }

        $lines = $this->resolveLines((int) $restaurant->id_restaurant, $data['articles']);
        $gross = array_sum(array_map(fn (array $l) => $l['prix'] * $l['quantite'], $lines));

        $discount = ['discount' => 0, 'points_used' => 0];
        if (! empty($data['utiliser_points'])) {
            $discount = $this->loyalty->discountFor($client, $gross);
        }

        return DB::transaction(function () use ($client, $restaurant, $mode, $data, $lines, $gross, $discount) {
            $order = Order::create([
                'id_client' => $client->getKey(),
                'id_restaurant' => $restaurant->id_restaurant,
                'date_commande' => now(),
                'statut' => OrderStatus::Pending->value,
                'mode_service' => $mode->value,
                'heure_arrivee' => $data['heure_arrivee'] ?? null,
                'adresse_livraison' => $mode === ServiceMode::Delivery ? $data['adresse_livraison'] : null,
                'montant_brut' => $gross,
                'points_utilises' => $discount['points_used'],
                'reduction' => $discount['discount'],
                'montant_total' => $gross - $discount['discount'],
            ]);

            foreach ($lines as $line) {
                OrderItem::create([
                    'id_commande' => $order->id_commande,
                    'id_element' => $line['item']->id_element,
                    'quantite' => $line['quantite'],
                    'prix_unitaire' => $line['prix'],
                ]);
            }

            if ($discount['points_used'] > 0) {
                try {
                    $this->loyalty->spend($client, $discount['points_used'], $order->id_commande);
                } catch (InsufficientPointsException $e) {
                    throw ValidationException::withMessages(['utiliser_points' => $e->getMessage()]);
                }
            }

            return $order->load(self::WITH);
        });
    }

    /** @return Collection<int, Order> */
    public function listFor(Etudiant $client): Collection
    {
        return Order::with(self::WITH)
            ->where('id_client', $client->getKey())
            ->orderByDesc('id_commande')
            ->get();
    }

    public function findFor(Etudiant $client, int $orderId): Order
    {
        return Order::with(self::WITH)
            ->where('id_client', $client->getKey())
            ->findOrFail($orderId);
    }

    public function cancel(Etudiant $client, int $orderId): Order
    {
        return DB::transaction(function () use ($client, $orderId) {
            $order = Order::where('id_client', $client->getKey())->lockForUpdate()->findOrFail($orderId);

            if ($order->statut !== OrderStatus::Pending->value) {
                throw ValidationException::withMessages(['statut' => 'Seule une commande en attente peut être annulée.']);
            }

            if ($order->points_utilises > 0) {
                $this->loyalty->grant($client, $order->points_utilises, $order->id_commande, 'REMBOURSEMENT');
            }
            $order->update(['statut' => OrderStatus::Cancelled->value]);

            return $order->load(self::WITH);
        });
    }

    /** @return Collection<int, Order> */
    public function listForRestaurant(User $staff, ?int $restaurantId = null, ?string $status = null): Collection
    {
        $restaurantId ??= $staff->id_restaurant !== null ? (int) $staff->id_restaurant : null;

        if ($restaurantId !== null) {
            $this->ensureCanAct($staff, $restaurantId);
        } elseif ((int) $staff->id_role !== Role::Admin->value) {
            throw new AuthorizationException("Aucun restaurant n'est associé à ce compte.");
        }

        return Order::with(self::WITH)
            ->when($restaurantId !== null, fn ($q) => $q->where('id_restaurant', $restaurantId))
            ->when($status, fn ($q) => $q->where('statut', $status))
            ->orderByDesc('id_commande')
            ->get();
    }

    public function validate(User $staff, int $orderId): Order
    {
        return DB::transaction(function () use ($staff, $orderId) {
            $order = Order::lockForUpdate()->findOrFail($orderId);
            $this->ensureCanAct($staff, (int) $order->id_restaurant);

            if ($order->statut !== OrderStatus::Pending->value) {
                throw ValidationException::withMessages(['statut' => 'Seule une commande en attente peut être validée.']);
            }

            $client = Etudiant::findOrFail($order->id_client);
            $points = $this->loyalty->earn($client, (int) $order->montant_total, $order->id_commande);

            $order->update([
                'statut' => OrderStatus::Validated->value,
                'id_employe_validateur' => $staff->getKey(),
                'date_validation' => now(),
                'points_gagnes' => $points,
            ]);

            $this->referrals->rewardOnFirstOrder($client, $order->id_commande);

            return $order->load(self::WITH);
        });
    }

    public function changeStatus(User $staff, int $orderId, string $status): Order
    {
        $target = OrderStatus::tryFrom($status);
        $allowed = [OrderStatus::Preparing, OrderStatus::Ready, OrderStatus::Delivered];

        if ($target === null || ! in_array($target, $allowed, true)) {
            throw ValidationException::withMessages([
                'statut' => 'Statut accepté : EN_PREPARATION, PRETE ou LIVREE.',
            ]);
        }

        return DB::transaction(function () use ($staff, $orderId, $target) {
            $order = Order::lockForUpdate()->findOrFail($orderId);
            $this->ensureCanAct($staff, (int) $order->id_restaurant);

            if (! OrderStatus::from($order->statut)->canGoTo($target)) {
                throw ValidationException::withMessages([
                    'statut' => "Passage impossible de {$order->statut} à {$target->value}.",
                ]);
            }

            $order->update(['statut' => $target->value]);

            return $order->load(self::WITH);
        });
    }

    private function ensureCanAct(User $user, int $restaurantId): void
    {
        if ((int) $user->id_role === Role::Admin->value) {
            return;
        }

        if ($user->id_restaurant === null || (int) $user->id_restaurant !== $restaurantId) {
            throw new AuthorizationException("Ce restaurant n'est pas le vôtre.");
        }
    }

    /**
     * @param array<int, array{id_element:int, quantite:int}> $articles
     * @return array<int, array{item:MenuItem, prix:int, quantite:int}>
     */
    private function resolveLines(int $restaurantId, array $articles): array
    {
        $quantities = [];
        foreach ($articles as $article) {
            $id = (int) $article['id_element'];
            $quantities[$id] = ($quantities[$id] ?? 0) + (int) $article['quantite'];
        }

        $items = MenuItem::with('category')->whereIn('id_element', array_keys($quantities))->get()->keyBy('id_element');

        $lines = [];
        foreach ($quantities as $id => $quantity) {
            $item = $items->get($id);

            if (! $item || ! $item->disponible || (int) $item->category?->id_restaurant !== $restaurantId) {
                throw ValidationException::withMessages([
                    'articles' => "Le plat n°{$id} est indisponible dans ce restaurant.",
                ]);
            }

            $lines[] = ['item' => $item, 'prix' => (int) $item->prix, 'quantite' => $quantity];
        }

        return $lines;
    }
}