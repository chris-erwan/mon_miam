<?php

namespace App\Modules\Auth\Services;

use App\Modules\Auth\Models\Employee;
use Illuminate\Auth\Access\AuthorizationException;

class EmployeeService
{

    // repondreReclamation()
    public function replyToComplaint(Employee $employee, int $complaintId, string $reply): void
    {
        // TODO: appeler le module Réclamations.
    }

    // voirStatsHebdo()
    public function weeklyStats(Employee $employee, ?int $restaurantId = null): array
    {
        $restaurantId ??= (int) $employee->id_restaurant;

        $this->ensureWorksIn($employee, $restaurantId);

        // TODO: remplacer par les vraies stats.
        return [
            'id_restaurant' => $restaurantId,
            'period' => 'week',
            'data' => [],
        ];
    }

    protected function ensureWorksIn(Employee $employee, int $restaurantId): void
    {
        if (! $employee->worksIn($restaurantId)) {
            throw new AuthorizationException("Ce restaurant n'est pas le vôtre.");
        }
    }
}