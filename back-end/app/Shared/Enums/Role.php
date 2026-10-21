<?php
 
namespace App\Shared\Enums;
 
enum Role: string
{
    case Student = 'student';
    case Employee = 'employee';
    case Manager = 'manager';
    case Admin = 'admin';
}

