<?php

namespace App\Modules\Auth\Http\Controllers;

use App\Modules\Auth\Models\Employee;
use App\Modules\Auth\Services\EmployeeService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class EmployeeController
{
    public function __construct(private readonly EmployeeService $service) {}


    public function replyToComplaint(Request $request, int $complaintId): JsonResponse
    {
        $data = $request->validate([
            'reponse' => ['required', 'string', 'max:2000'],
        ]);

        $this->service->replyToComplaint($this->employee($request), $complaintId, $data['reponse']);

        return response()->json(['message' => 'Réponse envoyée.']);
    }

    public function weeklyStats(Request $request): JsonResponse
    {
        $restaurantId = $request->integer('id_restaurant') ?: null;

        return response()->json(
            $this->service->weeklyStats($this->employee($request), $restaurantId)
        );
    }

    private function employee(Request $request): Employee
    {
        return Employee::withoutGlobalScope('roles')->findOrFail($request->user()->getKey());
    }
}