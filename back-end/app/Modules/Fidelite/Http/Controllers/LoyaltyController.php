<?php

namespace App\Modules\Fidelite\Http\Controllers;

use App\Modules\Auth\Models\Etudiant;
use App\Modules\Fidelite\Services\LoyaltyService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LoyaltyController
{
    public function __construct(private readonly LoyaltyService $service)
    {
    }

    public function balance(Request $request): JsonResponse
    {
        $student = $this->student($request);
        $rules = $this->service->rules();

        return response()->json([
            'points_solde'     => $this->service->balance($student),
            'seuil_points'     => $rules->pointsPerDiscount,
            'valeur_reduction' => $rules->discountValue,
            'validite_mois'    => $rules->validityMonths,
        ]);
    }

    public function history(Request $request): JsonResponse
    {
        return response()->json($this->service->history($this->student($request)));
    }

    private function student(Request $request): Etudiant
    {
        return Etudiant::findOrFail($request->user()->getKey());
    }
}