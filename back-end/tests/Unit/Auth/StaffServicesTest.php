<?php

namespace Tests\Unit\Auth;

use App\Modules\Auth\Models\Admin;
use App\Modules\Auth\Models\Employee;
use App\Modules\Auth\Models\Manager;
use App\Modules\Auth\Services\AdminService;
use App\Modules\Auth\Services\EmployeeService;
use App\Modules\Auth\Services\ManagerService;
use Illuminate\Auth\Access\AuthorizationException;
use Tests\TestCase;

class StaffServicesTest extends TestCase
{
    private function employeeAt(?int $restaurantId): Employee
    {
        $employee = new Employee();
        $employee->id_restaurant = $restaurantId;

        return $employee;
    }

    private function managerAt(?int $restaurantId): Manager
    {
        $manager = new Manager();
        $manager->id_restaurant = $restaurantId;

        return $manager;
    }

    public function test_employee_sees_stats_of_his_own_restaurant_by_default(): void
    {
        $stats = (new EmployeeService())->weeklyStats($this->employeeAt(3));

        $this->assertSame(3, $stats['id_restaurant']);
    }

    public function test_employee_cannot_see_stats_of_another_restaurant(): void
    {
        $this->expectException(AuthorizationException::class);

        (new EmployeeService())->weeklyStats($this->employeeAt(3), 5);
    }

    public function test_employee_without_restaurant_is_refused(): void
    {
        $this->expectException(AuthorizationException::class);

        (new EmployeeService())->weeklyStats($this->employeeAt(null));
    }

    public function test_manager_sees_general_stats_of_his_own_restaurant(): void
    {
        $stats = (new ManagerService())->generalStats($this->managerAt(3));

        $this->assertSame(3, $stats['id_restaurant']);
    }

    public function test_manager_cannot_see_general_stats_of_another_restaurant(): void
    {
        $this->expectException(AuthorizationException::class);

        (new ManagerService())->generalStats($this->managerAt(3), 5);
    }

    public function test_manager_keeps_the_weekly_stats_of_employee(): void
    {
        $stats = (new ManagerService())->weeklyStats($this->managerAt(3));

        $this->assertSame(3, $stats['id_restaurant']);
    }

    public function test_admin_sees_general_stats_of_any_restaurant(): void
    {
        $stats = (new AdminService())->generalStats(new Admin(), 9);

        $this->assertSame(9, $stats['id_restaurant']);
    }
}