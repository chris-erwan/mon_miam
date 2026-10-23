<?php

namespace App\Modules\Auth\Http\Controllers;

use App\Modules\Auth\Models\Manager;
use App\Modules\Auth\Services\ManagerService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ManagerController
{
    public function __construct(private readonly ManagerService $service) {}

    public function createEmployee(Request $request): JsonResponse
    {
        $data = $request->validate([
            'nom' => ['required', 'string', 'max:150'],
            'email' => ['required', 'email', 'max:190', 'unique:utilisateurs,email'],
            'mot_de_passe' => ['required', 'string', 'min:8'],
            'telephone' => ['nullable', 'string', 'max:30'],
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
        $restaurantId = $request->integer('id_restaurant') ?: null;

        return response()->json(
            $this->service->generalStats($this->manager($request), $restaurantId)
        );
    }

    private function manager(Request $request): Manager
    {
        return Manager::findOrFail($request->user()->getKey());
    }
}