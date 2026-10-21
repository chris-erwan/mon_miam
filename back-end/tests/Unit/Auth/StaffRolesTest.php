<?php

namespace Tests\Unit\Auth;

use App\Modules\Auth\Models\Admin;
use App\Modules\Auth\Models\Employee;
use App\Modules\Auth\Models\Manager;
use App\Shared\Enums\Role;
use Tests\TestCase;

class StaffRolesTest extends TestCase
{
    public function test_each_class_has_its_own_role(): void
    {
        $this->assertSame([Role::Employee], Employee::roles());
        $this->assertSame([Role::Manager], Manager::roles());
        $this->assertSame([Role::Admin], Admin::roles());
    }

    public function test_each_class_has_its_own_default_role(): void
    {
        $this->assertSame(Role::Employee, Employee::defaultRole());
        $this->assertSame(Role::Manager, Manager::defaultRole());
        $this->assertSame(Role::Admin, Admin::defaultRole());
    }

    public function test_roles_match_the_ids_of_the_roles_table(): void
    {
        $this->assertSame(1, Role::Student->value);
        $this->assertSame(2, Role::Employee->value);
        $this->assertSame(3, Role::Manager->value);
        $this->assertSame(4, Role::Admin->value);
    }

    public function test_each_class_filters_its_queries_on_its_role(): void
    {
        $employeeQuery = Employee::query()->toBase();
        $this->assertStringContainsString('in (?)', $employeeQuery->toSql());
        $this->assertSame([Role::Employee->value], $employeeQuery->getBindings());

        $this->assertSame([Role::Manager->value], Manager::query()->toBase()->getBindings());
        $this->assertSame([Role::Admin->value], Admin::query()->toBase()->getBindings());
    }

    public function test_employee_works_only_in_his_own_restaurant(): void
    {
        $employee = new Employee();
        $employee->id_restaurant = 3;

        $this->assertTrue($employee->worksIn(3));
        $this->assertFalse($employee->worksIn(5));
    }

    public function test_employee_without_restaurant_works_nowhere(): void
    {
        $employee = new Employee();

        $this->assertFalse($employee->worksIn(0));
        $this->assertFalse($employee->worksIn(3));
    }

    public function test_manager_keeps_the_restaurant_rule_of_employee(): void
    {
        $manager = new Manager();
        $manager->id_restaurant = 3;

        $this->assertTrue($manager->worksIn(3));
        $this->assertFalse($manager->worksIn(5));
    }

    public function test_admin_works_in_every_restaurant(): void
    {
        $admin = new Admin();

        $this->assertTrue($admin->worksIn(3));
        $this->assertTrue($admin->worksIn(99));
    }
}