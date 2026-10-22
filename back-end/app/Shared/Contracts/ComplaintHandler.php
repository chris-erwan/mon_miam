<?php
 
namespace App\Shared\Contracts;
 
interface ComplaintHandler
{
    public function weekly(int $restaurantId): array;
 
    public function general(int $restaurantId): array;
 
    public function realTime(): array;
}

