<?php

namespace App\Modules\Auth\Http\Controllers;

use App\Modules\Auth\Models\Manager;
use App\Modules\Auth\Services\ManagerService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ManagerController
{
    public function __construct(private readonly ManagerService $service) {}

    public function superviseOrders(Request $request): JsonResponse
    {
        $restaurantId = $request->integer('restaurant_id') ?: null;

        return response()->json(
            $this->service->superviseOrders($this->manager($request), $restaurantId)
        );
    }

    public function createEmployee(Request $request): JsonResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8'],
            'phone' => ['nullable', 'string', 'max:20'],
        ]);

        $employee = $this->service->createEmployee($this->manager($request), $data);

        return response()->json($employee, 201);
    }

    public function validateComplaint(Request $request, int $complaintId): JsonResponse
    {
        $this->service->validateComplaint($this->manager($request), $complaintId);

        return response()->json(['message' => 'Réclamation validée.']);
    }

    public function generalStats(Request $request): JsonResponse
    {
        $restaurantId = $request->integer('restaurant_id') ?: null;

        return response()->json(
            $this->service->generalStats($this->manager($request), $restaurantId)
        );
    }

    private function manager(Request $request): Manager
    {
        return Manager::findOrFail($request->user()->id);
    }
}
