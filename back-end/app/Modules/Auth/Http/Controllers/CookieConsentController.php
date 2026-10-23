<?php

namespace App\Modules\Auth\Http\Controllers;

use App\Modules\Auth\Services\CookieConsentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CookieConsentController
{
    public function __construct(private readonly CookieConsentService $service)
    {
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'identifiant_visiteur' => ['required', 'string', 'max:100'],
            'accepte'              => ['required', 'boolean'],
            'cookies_analytiques'  => ['nullable', 'boolean'],
        ]);

        $user = $request->user('sanctum');

        $this->service->record(
            $user,
            $data['identifiant_visiteur'],
            (bool) $data['accepte'],
            (bool) ($data['cookies_analytiques'] ?? false),
        );

        return response()->json(['message' => 'Choix enregistré.'], 201);
    }
}