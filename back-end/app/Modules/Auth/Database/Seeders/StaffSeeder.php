<?php

namespace App\Modules\Auth\Database\Seeders;

use App\Modules\Auth\Models\Admin;
use App\Modules\Auth\Models\Employee;
use App\Modules\Auth\Models\Manager;
use Illuminate\Database\Seeder;

class StaffSeeder extends Seeder
{
    private const RESTAURANT_ID = 1;
    private const DOMAIN = 'zeduc.com';
    private const PASSWORD = 'Password123';

    public function run(): void
    {
        // [classe du modèle, nom affiché, identifiant (sans espace ni accent), rôle dans l'e-mail, restaurant]
        $staff = [
            [Employee::class, 'Amina Employée', 'amina', 'employe', self::RESTAURANT_ID],
            [Employee::class, 'Paul Employé',   'paul',  'employe', self::RESTAURANT_ID],
            [Manager::class,  'Marie Gérante',  'marie', 'gerant',  self::RESTAURANT_ID],
            [Admin::class,    'Jean Admin',     'jean',  'admin',   null],
        ];

        foreach ($staff as [$model, $nom, $identifiant, $role, $restaurantId]) {
            $model::updateOrCreate(
                ['email' => "{$identifiant}.{$role}@" . self::DOMAIN],
                [
                    'nom'               => $nom,
                    'mot_de_passe_hash' => self::PASSWORD,
                    'id_restaurant'     => $restaurantId,
                ],
            );
        }
    }
}