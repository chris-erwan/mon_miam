<?php
 
namespace App\Shared\Contracts;
 
interface MenuEditor
{
    public function create(array $data): int;
 
    public function update(int $menuItemId, array $data): void;
 
    public function delete(int $menuItemId): void;
}

