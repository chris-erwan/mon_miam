<?php

namespace App\Shared\Enums;

// Les valeurs sont les identifiants de la table `roles` (colonne id_role).
enum Role: int
{
    case Student = 1;
    case Employee = 2;
    case Manager = 3;
    case Admin = 4;
}