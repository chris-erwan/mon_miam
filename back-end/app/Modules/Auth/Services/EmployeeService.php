<?php

namespace App\Modules\Auth\Services;

use App\Modules\Auth\Models\Employee;
use Illuminate\Auth\Access\AuthorizationException;

class EmployeeService
{
    // validerCommande()
    public function validateOrder(Employee $employee, int $orderId): void
    {
        // TODO: appeler le module Commandes (via un contrat branché par le Host).
        // TODO: vérifier que la commande appartient au restaurant de l'employé.
    }

    // changerStatut()
    public function changeOrderStatus(Employee $employee, int $orderId, string $status): void
    {
        // TODO: appeler le module Commandes.
    }

    // majMenu()
    public function updateMenu(Employee $employee, int $menuItemId, array $data): void
    {
        // TODO: appeler le module Menu (le tien).
    }

    // repondreReclamation()
    public function replyToComplaint(Employee $employee, int $complaintId, string $reply): void
    {
        // TODO: appeler le module Réclamations.
    }

    // voirStatsHebdo()
    public function weeklyStats(Employee $employee, ?int $restaurantId = null): array
    {
        $restaurantId ??= (int) $employee->restaurant_id;

        $this->ensureWorksIn($employee, $restaurantId);

        // TODO: remplacer par les vraies stats.
        return ['restaurant_id' => $restaurantId, 'period' => 'week', 'data' => []];
    }

    protected function ensureWorksIn(Employee $employee, int $restaurantId): void
    {
        if (! $employee->worksIn($restaurantId)) {
            throw new AuthorizationException("Ce restaurant n'est pas le vôtre.");
        }
    }
}