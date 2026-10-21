<?php
 
namespace App\Shared\Enums;
 
enum Role: string
{
    case Student = 'etudiant';
    case Employee = 'employe';
    case Manager = 'gerant';
    case Admin = 'admin';
}
