<?php

namespace App\Modules\Order\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Modules\Auth\Models\User;
use DomainException;
use InvalidArgumentException;

/**
 * Class Order
 *
 * Gère le cycle de vie d'une commande à ZeDuc@Space :
 * calcul des totaux, déduction des points de fidélité, validation.
 *
 * @package App\Modules\Order\Models
 */
class Order extends Model
{
    protected $fillable = [
        'user_id',
        'restaurant_id',
        'mode',             // sur_place, a_emporter, livraison
        'arrival_time',
        'delivery_address',
        'total',
        'discount',
        'points_used',
        'points_earned',
        'status',           // en_attente, validee, en_preparation, prete, livree, annulee
        'payment_method',
        'payment_status',
    ];

    /** @var OrderItem[] Stockage temporaire des items pour les calculs mémoire */
    protected array $transientItems = [];

    protected function casts(): array
    {
        return [
            'user_id'       => 'integer',
            'restaurant_id' => 'integer',
            'total'         => 'integer',
            'discount'      => 'integer',
            'points_used'   => 'integer',
            'points_earned' => 'integer',
        ];
    }

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        // Statuts initiaux par défaut
        $this->attributes['status'] = $this->attributes['status'] ?? 'en_attente';
        $this->attributes['discount'] = $this->attributes['discount'] ?? 0;
        $this->attributes['points_used'] = $this->attributes['points_used'] ?? 0;
    }

    /**
     * Ajoute une ligne à la commande pour le calcul du panier.
     */
    public function addItem(OrderItem $item): void
    {
        $this->transientItems[] = $item;
    }

    /**
     * Calcule le sous-total brut des articles.
     */
    public function calculateSubtotal(): int
    {
        $subtotal = 0;
        foreach ($this->transientItems as $item) {
            $subtotal += $item->getSubtotal();
        }
        return $subtotal;
    }

    /**
     * Applique une remise commerciale.
     */
    public function applyDiscount(int $amount): void
    {
        if ($amount > $this->calculateSubtotal()) {
            throw new InvalidArgumentException('La remise ne peut pas dépasser le sous-total');
        }
        $this->attributes['discount'] = $amount;
    }

    /**
     * Déduit un montant issu des points de fidélité.
     */
    public function applyPointsDeduction(int $amount): void
    {
        $this->attributes['points_used'] = $amount;
    }

    /**
     * Calcule le montant final net à payer.
     */
    public function calculateTotal(): int
    {
        $net = $this->calculateSubtotal() - (int) ($this->attributes['discount'] ?? 0) - (int) ($this->attributes['points_used'] ?? 0);
        return max(0, $net);
    }

    /**
     * Valide et confirme la commande.
     */
    public function confirm(): void
    {
        if (empty($this->transientItems)) {
            throw new DomainException('Impossible de valider une commande vide');
        }
        $this->attributes['status'] = 'validee';
    }

    // Relations
    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class, 'order_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
