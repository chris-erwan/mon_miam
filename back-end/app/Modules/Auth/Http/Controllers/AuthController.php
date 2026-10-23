<?php

namespace App\Modules\Auth\Http\Controllers;

use App\Modules\Auth\Models\User;
use App\Modules\Auth\Services\AuthService;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthController
{
    public function __construct(private readonly AuthService $service)
    {
    }

        public function register(Request $request): JsonResponse
    {
        $data = $request->validate([
            'nom'             => ['required', 'string', 'max:150'],
            'email'           => ['required', 'email', 'max:190'],
            'mot_de_passe'    => AuthService::passwordRules(),
            'telephone'       => ['nullable', 'string', 'max:30'],
            'localisation'    => ['nullable', 'string', 'max:255'],
            'code_parrainage' => ['nullable', 'string', 'max:20'],
        ], [
            'mot_de_passe.regex' => 'Le mot de passe doit contenir au moins une majuscule, un chiffre et 8 caractères.',
        ]);

        $result = $this->service->register($data);

        return response()->json([
            'user'  => $this->profile($result['user']),
            'token' => $result['token'],
        ], 201);
    }

    public function login(Request $request): JsonResponse
    {
        $data = $request->validate([
            'email'        => ['required', 'email'],
            'mot_de_passe' => ['required', 'string'],
        ]);

        try {
            $result = $this->service->login($data['email'], $data['mot_de_passe']);
        } catch (AuthenticationException $e) {
            return response()->json(['message' => $e->getMessage()], 401);
        } catch (AuthorizationException $e) {
            return response()->json(['message' => $e->getMessage()], 403);
        }

        return response()->json([
            'user'  => $this->profile($result['user']),
            'token' => $result['token'],
        ]);
    }

    public function logout(Request $request): JsonResponse
    {
        $this->service->logout($request->user());

        return response()->json(['message' => 'Déconnecté.']);
    }

    public function me(Request $request): JsonResponse
    {
        return response()->json(['user' => $this->profile($request->user())]);
    }

    private function profile(User $user): array
    {
        return $user->only([
            'id_utilisateur', 'nom', 'email', 'telephone', 'localisation',
            'id_role', 'id_restaurant', 'points_solde', 'code_parrainage', 'statut_compte',
        ]);
    }
}