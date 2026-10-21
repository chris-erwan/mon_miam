<?php
 
namespace App\Shared\Contracts;
 
interface OrderWorkflow
{
    public function validate(int $orderId, int $actorId): void;
 
    public function changeStatus(int $orderId, string $status, int $actorId): void;
 
    /** @return iterable<mixed> */
    public function forRestaurant(int $restaurantId): iterable;
}

