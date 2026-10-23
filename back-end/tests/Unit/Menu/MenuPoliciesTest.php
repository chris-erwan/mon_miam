<?php

namespace Tests\Unit\Menu;

use App\Modules\Auth\Models\User;
use App\Modules\Menu\Models\MenuCategory;
use App\Modules\Menu\Models\MenuItem;
use App\Modules\Menu\Policies\MenuCategoryPolicy;
use App\Modules\Menu\Policies\MenuItemPolicy;
use App\Shared\Enums\Role;
use Tests\TestCase;

class MenuPoliciesTest extends TestCase
{
    private function userWith(Role $role, ?int $restaurantId = null): User
    {
        $user = new User();
        $user->id_role = $role->value;
        $user->id_restaurant = $restaurantId;

        return $user;
    }

    private function categoryOfRestaurant(int $restaurantId): MenuCategory
    {
        $category = new MenuCategory();
        $category->id_restaurant = $restaurantId;

        return $category;
    }

    private function itemOfRestaurant(int $restaurantId): MenuItem
    {
        $item = new MenuItem();
        $item->setRelation('category', $this->categoryOfRestaurant($restaurantId));

        return $item;
    }

    // ---------- MenuItemPolicy ----------

    public function test_employee_can_manage_menu_of_his_restaurant(): void
    {
        $policy = new MenuItemPolicy();
        $employee = $this->userWith(Role::Employee, 3);

        $this->assertTrue($policy->create($employee, 3));
        $this->assertTrue($policy->update($employee, $this->itemOfRestaurant(3)));
        $this->assertTrue($policy->delete($employee, $this->itemOfRestaurant(3)));
    }

    public function test_employee_cannot_manage_menu_of_another_restaurant(): void
    {
        $policy = new MenuItemPolicy();
        $employee = $this->userWith(Role::Employee, 3);

        $this->assertFalse($policy->create($employee, 5));
        $this->assertFalse($policy->update($employee, $this->itemOfRestaurant(5)));
        $this->assertFalse($policy->delete($employee, $this->itemOfRestaurant(5)));
    }

    public function test_manager_can_manage_menu_of_his_restaurant_only(): void
    {
        $policy = new MenuItemPolicy();
        $manager = $this->userWith(Role::Manager, 3);

        $this->assertTrue($policy->update($manager, $this->itemOfRestaurant(3)));
        $this->assertFalse($policy->update($manager, $this->itemOfRestaurant(5)));
    }

    public function test_admin_can_manage_menu_of_any_restaurant(): void
    {
        $policy = new MenuItemPolicy();
        $admin = $this->userWith(Role::Admin);

        $this->assertTrue($policy->create($admin, 99));
        $this->assertTrue($policy->update($admin, $this->itemOfRestaurant(5)));
        $this->assertTrue($policy->delete($admin, $this->itemOfRestaurant(5)));
    }

    public function test_student_cannot_manage_menu(): void
    {
        $policy = new MenuItemPolicy();
        $student = $this->userWith(Role::Student, 3);

        $this->assertFalse($policy->create($student, 3));
        $this->assertFalse($policy->update($student, $this->itemOfRestaurant(3)));
    }

    public function test_staff_without_restaurant_cannot_manage_menu(): void
    {
        $policy = new MenuItemPolicy();
        $employee = $this->userWith(Role::Employee, null);

        $this->assertFalse($policy->create($employee, 3));
    }

    public function test_item_without_category_cannot_be_managed_by_staff(): void
    {
        $policy = new MenuItemPolicy();
        $item = new MenuItem();
        $item->setRelation('category', null);

        $this->assertFalse($policy->update($this->userWith(Role::Employee, 3), $item));
        $this->assertTrue($policy->update($this->userWith(Role::Admin), $item));
    }

    // ---------- MenuCategoryPolicy ----------

    public function test_admin_can_manage_categories_of_any_restaurant(): void
    {
        $policy = new MenuCategoryPolicy();
        $admin = $this->userWith(Role::Admin);

        $this->assertTrue($policy->create($admin, 99));
        $this->assertTrue($policy->update($admin, $this->categoryOfRestaurant(5)));
        $this->assertTrue($policy->delete($admin, $this->categoryOfRestaurant(5)));
    }

    public function test_manager_can_manage_categories_of_his_restaurant_only(): void
    {
        $policy = new MenuCategoryPolicy();
        $manager = $this->userWith(Role::Manager, 3);

        $this->assertTrue($policy->create($manager, 3));
        $this->assertTrue($policy->update($manager, $this->categoryOfRestaurant(3)));
        $this->assertTrue($policy->delete($manager, $this->categoryOfRestaurant(3)));

        $this->assertFalse($policy->create($manager, 5));
        $this->assertFalse($policy->update($manager, $this->categoryOfRestaurant(5)));
        $this->assertFalse($policy->delete($manager, $this->categoryOfRestaurant(5)));
    }

    public function test_employee_and_student_cannot_manage_categories(): void
    {
        $policy = new MenuCategoryPolicy();
        $category = $this->categoryOfRestaurant(3);

        foreach ([Role::Employee, Role::Student] as $role) {
            $user = $this->userWith($role, 3);

            $this->assertFalse($policy->create($user, 3));
            $this->assertFalse($policy->update($user, $category));
            $this->assertFalse($policy->delete($user, $category));
        }
    }
}