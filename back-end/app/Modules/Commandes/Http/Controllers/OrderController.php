<?php

namespace App\Modules\Commandes\Http\Controllers;

use App\Modules\Auth\Models\Etudiant;
use App\Modules\Commandes\Enums\ServiceMode;
use App\Modules\Commandes\Services\OrderService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class OrderController
{
    public function __construct(private readonly OrderService $service)
    {
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'id_restaurant' => ['required', 'integer'],
            'mode_service' => ['required', Rule::enum(ServiceMode::class)],
            'adresse_livraison' => ['nullable', 'string', 'max:255'],
            'heure_arrivee' => ['nullable', 'date_format:H:i'],
            'utiliser_points' => ['nullable', 'boolean'],
            'articles' => ['required', 'array', 'min:1', 'max:30'],
            'articles.*.id_element' => ['required', 'integer'],
            'articles.*.quantite' => ['required', 'integer', 'min:1', 'max:20'],
        ]);

        $order = $this->service->create($this->student($request), $data);

        return response()->json($order, 201);
    }

    public function index(Request $request): JsonResponse
    {
        return response()->json($this->service->listFor($this->student($request)));
    }

    public function show(Request $request, int $orderId): JsonResponse
    {
        return response()->json($this->service->findFor($this->student($request), $orderId));
    }

    public function cancel(Request $request, int $orderId): JsonResponse
    {
        return response()->json($this->service->cancel($this->student($request), $orderId));
    }

    private function student(Request $request): Etudiant
    {
        return Etudiant::findOrFail($request->user()->getKey());
    }
}