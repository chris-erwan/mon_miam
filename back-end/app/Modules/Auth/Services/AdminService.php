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
        $model = $data['role'] === Role::Manager->value ? Manager::class : Employee::class;

        return $model::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $data['password'],
            'phone' => $data['phone'] ?? null,
            'restaurant_id' => $data['restaurant_id'],
        ]);
    }

    // gererEmployes() : suppression
    public function deleteStaff(Admin $admin, int $employeeId): void
    {
        $this->staffQuery()->findOrFail($employeeId)->delete();
    }

    // gererMenu()
    public function manageMenu(Admin $admin): void
    {
        // TODO: les routes du menu seront dans le module Menu (accès admin via le Host).
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
            ->whereIn('role', [Role::Employee->value, Role::Manager->value]);
    }
}