<?php

namespace App\Host;

use Illuminate\Support\ServiceProvider;

class HostServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        foreach ($this->modules() as $provider) {
            $this->app->register($provider);
        }
    }

    /** @return array<int, class-string<ServiceProvider>> */
    private function modules(): array
    {
        return require __DIR__ . '/modules.php';
    }
}