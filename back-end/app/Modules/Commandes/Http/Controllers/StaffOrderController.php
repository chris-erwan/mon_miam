<?php

namespace App\Modules\Commandes\Http\Controllers;

use App\Modules\Commandes\Services\OrderService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class StaffOrderController
{
    public function __construct(private readonly OrderService $service)
    {
    }

    public function index(Request $request): JsonResponse
    {
        $data = $request->validate([
            'id_restaurant' => ['nullable', 'integer'],
            'statut' => ['nullable', 'string', 'max:20'],
        ]);

        return response()->json(
            $this->service->listForRestaurant($request->user(), $data['id_restaurant'] ?? null, $data['statut'] ?? null)
        );
    }

    public function validateOrder(Request $request, int $orderId): JsonResponse
    {
        return response()->json($this->service->validate($request->user(), $orderId));
    }

    public function changeStatus(Request $request, int $orderId): JsonResponse
    {
        $data = $request->validate([
            'statut' => ['required', 'string', 'max:20'],
        ]);

        return response()->json($this->service->changeStatus($request->user(), $orderId, $data['statut']));
    }
}