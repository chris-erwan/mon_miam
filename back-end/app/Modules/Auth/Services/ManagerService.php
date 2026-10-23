<?php

namespace App\Modules\Auth\Services;

use App\Modules\Auth\Models\Employee;
use App\Modules\Auth\Models\Manager;

class ManagerService extends EmployeeService
{

    // creerEmploye()
    public function createEmployee(Manager $manager, array $data): Employee
    {
        return Employee::create([
            'nom' => $data['nom'],
            'email' => $data['email'],
            'mot_de_passe_hash' => $data['mot_de_passe'],
            'telephone' => $data['telephone'] ?? null,
            'id_restaurant' => $manager->id_restaurant,
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
        $restaurantId ??= (int) $manager->id_restaurant;

        $this->ensureWorksIn($manager, $restaurantId);

        // TODO: remplacer par les vraies stats.
        return ['id_restaurant' => $restaurantId, 'period' => 'all', 'data' => []];
    }
}