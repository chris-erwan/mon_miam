<?php
 
namespace App\Shared\Contracts;
 
interface SettingEditor
{
    public function get(string $key): ?string;
 
    public function set(string $key, string $value): void;
}
