<?php

namespace Tests\Unit\Auth;

use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class ModuleRoutesTest extends TestCase
{
    private function routes(): array
    {
        $list = [];
        foreach (Route::getRoutes() as $route) {
            foreach ($route->methods() as $method) {
                $list[] = $method . ' ' . $route->uri();
            }
        }

        return $list;
    }

    public function test_auth_routes_are_registered(): void
    {
        $routes = $this->routes();

        $this->assertContains('POST api/auth/register', $routes);
        $this->assertContains('POST api/auth/login', $routes);
        $this->assertContains('POST api/auth/logout', $routes);
        $this->assertContains('GET api/auth/me', $routes);
        $this->assertContains('POST api/cookies/consent', $routes);
    }

    public function test_fidelite_routes_are_registered(): void
    {
        $routes = $this->routes();

        $this->assertContains('GET api/loyalty/balance', $routes);
        $this->assertContains('GET api/loyalty/history', $routes);
        $this->assertContains('GET api/referrals', $routes);
        $this->assertContains('POST api/referrals/use', $routes);
    }
}