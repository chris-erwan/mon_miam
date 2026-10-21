<?php
 
namespace App\Modules\Auth\Models;
 
use App\Shared\Contracts\ComplaintHandler;
use App\Shared\Contracts\OrderWorkflow;
use App\Shared\Contracts\StatisticsProvider;
use App\Shared\Enums\Role;
 
class Manager extends Employee
{
    public static function roles(): array
    {
        return [Role::Manager];
    }
 
    public static function defaultRole(): Role
    {
        return Role::Manager;
    }
 
    // superviserCommandes()
    public function superviseOrders(?int $restaurantId = null): iterable
    {
        $restaurantId = $this->allowedRestaurant($restaurantId);
 
        return app(OrderWorkflow::class)->forRestaurant($restaurantId);
    }
 
    // creerEmploye()
    public function createEmployee(array $data): Employee
    {
        return Employee::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $data['password'],
            'phone' => $data['phone'] ?? null,
            'restaurant_id' => $this->restaurantForNewEmployee($data),
        ]);
    }
 
    // validerReclamation()
    public function validateComplaint(int $complaintId): void
    {
        app(ComplaintHandler::class)->validate($complaintId, $this->id);
    }
 
    // voirStatsGenerales()
    public function generalStats(?int $restaurantId = null): array
    {
        $restaurantId = $this->allowedRestaurant($restaurantId);
 
        return app(StatisticsProvider::class)->general($restaurantId);
    }
 
    protected function restaurantForNewEmployee(array $data): ?int
    {
        return $this->restaurant_id;
    }
}
