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
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8'],
            'phone' => ['nullable', 'string', 'max:20'],
            'restaurant_id' => ['required', 'integer'],
            'role' => ['required', Rule::in([Role::Employee->value, Role::Manager->value])],
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
        return Admin::findOrFail($request->user()->id);
    }
}