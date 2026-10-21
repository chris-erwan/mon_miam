<?php

namespace App\Modules\Auth\Http\Controllers;

use App\Modules\Auth\Models\Employee;
use App\Modules\Auth\Services\EmployeeService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class EmployeeController
{
    public function __construct(private readonly EmployeeService $service) {}

    public function validateOrder(Request $request, int $orderId): JsonResponse
    {
        $this->service->validateOrder($this->employee($request), $orderId);
        return response()->json(['message' => 'Commande validée.']);
    }

    public function changeOrderStatus(Request $request, int $orderId): JsonResponse
    {
        $data = $request->validate(['status' => ['required', 'string']]);
        $this->service->changeOrderStatus($this->employee($request), $orderId, $data['status']);
        return response()->json(['message' => 'Statut mis à jour.']);
    }

    public function updateMenu(Request $request, int $menuItemId): JsonResponse
    {
        $data = $request->validate([
            'name' => ['sometimes', 'string', 'max:255'],
            'price' => ['sometimes', 'numeric', 'min:0'],
            'available' => ['sometimes', 'boolean'],
        ]);
        $this->service->updateMenu($this->employee($request), $menuItemId, $data);
        return response()->json(['message' => 'Menu mis à jour.']);
    }

    public function replyToComplaint(Request $request, int $complaintId): JsonResponse
    {
        $data = $request->validate(['reply' => ['required', 'string', 'max:2000']]);
        $this->service->replyToComplaint($this->employee($request), $complaintId, $data['reply']);
        return response()->json(['message' => 'Réponse envoyée.']);
    }

    public function weeklyStats(Request $request): JsonResponse
    {
        $restaurantId = $request->integer('restaurant_id') ?: null;
        return response()->json($this->service->weeklyStats($this->employee($request), $restaurantId));
    }

    private function employee(Request $request): Employee
    {
        return Employee::findOrFail($request->user()->id);
    }
}