<?php
 
namespace Tests\Feature\Auth;
 
use App\Modules\Auth\Models\Admin;
use App\Modules\Auth\Models\Employee;
use App\Modules\Auth\Models\Manager;
use App\Shared\Contracts\ComplaintHandler;
use App\Shared\Contracts\OrderWorkflow;
use App\Shared\Enums\Role;
use Illuminate\Auth\Access\AuthorizationException;
use Mockery\MockInterface;
use Tests\TestCase;
 
class StaffRolesTest extends TestCase
{
    public function test_each_class_covers_its_own_role(): void
    {
        $this->assertSame([Role::Employee], Employee::roles());
        $this->assertSame([Role::Manager], Manager::roles());
        $this->assertSame([Role::Admin], Admin::roles());
    }
 
    public function test_employee_validates_an_order_through_the_contract(): void
    {
        $employee = new Employee();
        $employee->id = 7;
 
        $this->mock(OrderWorkflow::class, function (MockInterface $mock) {
            $mock->shouldReceive('validate')->once()->with(10, 7);
        });
 
        $employee->validateOrder(10);
    }
 
    public function test_manager_validates_a_complaint_through_the_contract(): void
    {
        $manager = new Manager();
        $manager->id = 4;
 
        $this->mock(ComplaintHandler::class, function (MockInterface $mock) {
            $mock->shouldReceive('validate')->once()->with(21, 4);
        });
 
        $manager->validateComplaint(21);
    }
 
    public function test_employee_cannot_read_another_restaurant(): void
    {
        $employee = new Employee();
        $employee->restaurant_id = 3;
 
        $this->expectException(AuthorizationException::class);
 
        $employee->weeklyStats(5);
    }
 
    public function test_admin_can_act_in_any_restaurant(): void
    {
        $admin = new Admin();
 
        $this->assertTrue($admin->worksIn(5));
    }
}

