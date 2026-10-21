<?php

namespace App\Modules\Auth\Services;

use App\Modules\Auth\Models\Employee;
use App\Modules\Auth\Models\Manager;

class ManagerService extends EmployeeService
{
    // superviserCommandes()
    public function superviseOrders(Manager $manager, ?int $restaurantId = null): array
    {
        $restaurantId ??= (int) $manager->restaurant_id;

        $this->ensureWorksIn($manager, $restaurantId);

        // TODO: appeler le module Commandes.
        return ['restaurant_id' => $restaurantId, 'orders' => []];
    }

    // creerEmploye()
    public function createEmployee(Manager $manager, array $data): Employee
    {
        return Employee::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $data['password'],
            'phone' => $data['phone'] ?? null,
            'restaurant_id' => $manager->restaurant_id,
        ]);
    }

    // validerReclamation()
    public function validateComplaint(Manager $manager, int $complaintId): void
    {
        // TODO: appeler le module Réclamations.
    }

    // voirStatsGenerales()
    public function generalStats(Manager $manager, ?int $restaurantId = null): array
    {
        $restaurantId ??= (int) $manager->restaurant_id;

        $this->ensureWorksIn($manager, $restaurantId);

        // TODO: remplacer par les vraies stats.
        return ['restaurant_id' => $restaurantId, 'period' => 'all', 'data' => []];
    }
}