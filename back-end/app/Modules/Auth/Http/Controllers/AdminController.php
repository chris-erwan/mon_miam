<?php

namespace App\Modules\Auth\Http\Controllers;

use App\Modules\Auth\Models\Admin;
use App\Modules\Auth\Services\AdminService;
use App\Shared\Enums\Role;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AdminController
{
    public function __construct(private readonly AdminService $service) {}

    public function employees(Request $request): JsonResponse
    {
        return response()->json($this->service->manageEmployees($this->admin($request)));
    }

    public function createEmployee(Request $request): JsonResponse
    {
        $data = $request->validate([
            'nom' => ['required', 'string', 'max:150'],
            'email' => ['required', 'email', 'max:190', 'unique:utilisateurs,email'],
            'mot_de_passe' => ['required', 'string', 'min:8'],
            'telephone' => ['nullable', 'string', 'max:30'],
            'id_restaurant' => ['required', 'integer'],
            'id_role' => ['required', Rule::in([Role::Employee->value, Role::Manager->value])],
        ]);

        $staff = $this->service->createStaff($this->admin($request), $data);

        return response()->json($staff, 201);
    }

    public function deleteEmployee(Request $request, int $employeeId): JsonResponse
    {
        $this->service->deleteStaff($this->admin($request), $employeeId);

        return response()->json(['message' => 'Compte supprimé.']);
    }

    public function realTimeStats(Request $request): JsonResponse
    {
        return response()->json($this->service->realTimeStats($this->admin($request)));
    }

    private function admin(Request $request): Admin
    {
        return Admin::findOrFail($request->user()->getKey());
    }
}