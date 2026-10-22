<?php
 
namespace App\Shared\Contracts;
 
interface PromotionEditor
{
    public function weekly(int $restaurantId): array;
 
    public function general(int $restaurantId): array;
 
    public function realTime(): array;
}

