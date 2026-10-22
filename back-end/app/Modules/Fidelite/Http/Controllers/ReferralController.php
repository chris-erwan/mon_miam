<?php

namespace App\Modules\Fidelite\Http\Controllers;

use App\Modules\Auth\Models\Etudiant;
use App\Modules\Fidelite\Services\ReferralService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ReferralController
{
    public function __construct(private readonly ReferralService $service)
    {
    }

    public function index(Request $request): JsonResponse
    {
        $student = $this->student($request);

        return response()->json([
            'code_parrainage' => $this->service->codeOf($student),
            'filleuls'        => $this->service->listFor($student),
        ]);
    }

    public function use(Request $request): JsonResponse
    {
        $data = $request->validate([
            'code_parrainage' => ['required', 'string', 'max:20'],
        ]);

        try {
            $this->service->useCode($this->student($request), $data['code_parrainage']);
        } catch (\DomainException $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }

        return response()->json(['message' => 'Code de parrainage appliqué.'], 201);
    }

    private function student(Request $request): Etudiant
    {
        return Etudiant::findOrFail($request->user()->getKey());
    }
}