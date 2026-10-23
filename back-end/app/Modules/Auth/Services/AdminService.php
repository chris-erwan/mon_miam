<?php

namespace App\Modules\Auth\Services;

use App\Modules\Auth\Models\Admin;
use App\Modules\Auth\Models\Employee;
use App\Modules\Auth\Models\Manager;
use App\Shared\Enums\Role;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class AdminService extends ManagerService
{
    // gererEmployes() : liste
    public function manageEmployees(Admin $admin): Collection
    {
        return $this->staffQuery()->get();
    }

    // gererEmployes() : création
    public function createStaff(Admin $admin, array $data): Employee
    {
        $model = (int) $data['id_role'] === Role::Manager->value ? Manager::class : Employee::class;

        return $model::create([
            'nom' => $data['nom'],
            'email' => $data['email'],
            'mot_de_passe_hash' => $data['mot_de_passe'],
            'telephone' => $data['telephone'] ?? null,
            'id_restaurant' => $data['id_restaurant'],
        ]);
    }

    // gererEmployes() : suppression
    public function deleteStaff(Admin $admin, int $employeeId): void
    {
        $this->staffQuery()->findOrFail($employeeId)->delete();
    }

    // gererPromotions()
    public function managePromotions(Admin $admin): void
    {
        // TODO: les routes des promotions seront dans le module Promotions.
    }

    // gererParametres()
    public function manageSettings(Admin $admin): void
    {
        // TODO: les routes des paramètres seront dans le module Paramètres.
    }

    // voirStatsTempsReel()
    public function realTimeStats(Admin $admin): array
    {
        // TODO: remplacer par les vraies stats.
        return ['period' => 'realtime', 'data' => []];
    }

    private function staffQuery(): Builder
    {
        return Employee::withoutGlobalScope('roles')
            ->whereIn('id_role', [Role::Employee->value, Role::Manager->value]);
    }
}