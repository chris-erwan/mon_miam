<?php

namespace App\Modules\Auth\Services;

use App\Modules\Auth\Events\StudentRegistered;
use App\Modules\Auth\Models\Etudiant;
use App\Modules\Auth\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthService
{
    public const STATUS_ACTIVE = 'ACTIF';

    public const PASSWORD_PATTERN = '/^(?=.*[A-Z])(?=.*\d).{8,}$/';

    public static function passwordRules(): array
    {
        return ['required', 'string', 'min:8', 'regex:' . self::PASSWORD_PATTERN];
    }

    public static function passwordIsValid(string $password): bool
    {
        return (bool) preg_match(self::PASSWORD_PATTERN, $password);
    }

    /**
     * @param array{nom:string,email:string,mot_de_passe:string,telephone?:?string,localisation?:?string,code_parrainage?:?string} $data
     * @return array{user:Etudiant, token:string}
     * @throws ValidationException
     */
    public function register(array $data): array
    {
        if (! self::passwordIsValid($data['mot_de_passe'])) {
            throw ValidationException::withMessages([
                'mot_de_passe' => 'Le mot de passe doit contenir au moins une majuscule et un chiffre (8 caractères minimum).',
            ]);
        }
        if (User::where('email', $data['email'])->exists()) {
            throw ValidationException::withMessages(['email' => 'Cette adresse e-mail est déjà utilisée.']);
        }

        $etudiant = DB::transaction(fn () => Etudiant::create([
            'nom'               => $data['nom'],
            'email'             => $data['email'],
            'mot_de_passe_hash' => $data['mot_de_passe'],
            'telephone'         => $data['telephone'] ?? null,
            'localisation'      => $data['localisation'] ?? null,
        ]));

        StudentRegistered::dispatch($etudiant, $data['code_parrainage'] ?? null);

        return [
            'user'  => $etudiant->fresh(),
            'token' => $etudiant->createToken('api')->plainTextToken,
        ];
    }

    /**
     * @return array{user:User, token:string}
     * @throws AuthenticationException
     * @throws AuthorizationException
     */
    public function login(string $email, string $password): array
    {
        $user = User::where('email', $email)->first();

        if (! $user || ! Hash::check($password, $user->mot_de_passe_hash)) {
            throw new AuthenticationException('Identifiants invalides.');
        }
        if ($user->statut_compte !== self::STATUS_ACTIVE) {
            throw new AuthorizationException('Ce compte est désactivé.');
        }

        return ['user' => $user, 'token' => $user->createToken('api')->plainTextToken];
    }

    public function logout(User $user): void
    {
        $user->currentAccessToken()?->delete();
    }
}