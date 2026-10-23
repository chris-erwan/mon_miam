<?php

namespace Tests\Unit\Commandes;

use App\Modules\Commandes\Enums\OrderStatus;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class OrderStatusTest extends TestCase
{
    public function test_a_pending_order_can_be_validated_or_cancelled(): void
    {
        $this->assertTrue(OrderStatus::Pending->canGoTo(OrderStatus::Validated));
        $this->assertTrue(OrderStatus::Pending->canGoTo(OrderStatus::Cancelled));
        $this->assertFalse(OrderStatus::Pending->canGoTo(OrderStatus::Ready));
    }

    public function test_the_kitchen_flow_goes_forward_only(): void
    {
        $this->assertTrue(OrderStatus::Validated->canGoTo(OrderStatus::Preparing));
        $this->assertTrue(OrderStatus::Preparing->canGoTo(OrderStatus::Ready));
        $this->assertTrue(OrderStatus::Ready->canGoTo(OrderStatus::Delivered));
        $this->assertFalse(OrderStatus::Ready->canGoTo(OrderStatus::Preparing));
        $this->assertFalse(OrderStatus::Validated->canGoTo(OrderStatus::Delivered));
    }

    public function test_finished_orders_cannot_change(): void
    {
        $this->assertSame([], OrderStatus::Delivered->next());
        $this->assertSame([], OrderStatus::Cancelled->next());
    }

    public function test_statuses_fit_the_varchar_20_column(): void
    {
        foreach (OrderStatus::cases() as $status) {
            $this->assertLessThanOrEqual(20, strlen($status->value));
        }
    }

    public function test_orders_and_restaurants_routes_are_registered(): void
    {
        $routes = [];
        foreach (Route::getRoutes() as $route) {
            foreach ($route->methods() as $method) {
                $routes[] = $method . ' ' . $route->uri();
            }
        }

        $this->assertContains('POST api/orders', $routes);
        $this->assertContains('GET api/orders', $routes);
        $this->assertContains('POST api/orders/{orderId}/cancel', $routes);
        $this->assertContains('POST api/employee/orders/{orderId}/validate', $routes);
        $this->assertContains('PATCH api/employee/orders/{orderId}/status', $routes);
        $this->assertContains('GET api/manager/orders', $routes);
        $this->assertContains('GET api/restaurants', $routes);
    }
}