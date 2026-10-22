<?php

namespace App\Host\Http\Middleware;

use App\Shared\Enums\Role;
use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class EnsureRole
{
    public function handle(Request $request, Closure $next, string ...$roles): mixed
    {
        $user = $request->user();

        if (! $user) {
            return $this->error('Non authentifié.', 401);
        }

        $allowed = array_map(fn (string $name) => $this->idOf($name), $roles);

        if (! in_array((int) $user->id_role, $allowed, true)) {
            return $this->error('Accès refusé pour votre rôle.', 403);
        }

        return $next($request);
    }

    private function idOf(string $name): int
    {
        foreach (Role::cases() as $role) {
            if (strtolower($role->name) === strtolower(trim($name))) {
                return $role->value;
            }
        }

        throw new \InvalidArgumentException("Rôle inconnu : {$name}");
    }

    private function error(string $message, int $status): JsonResponse
    {
        return response()->json(['message' => $message], $status);
    }
}