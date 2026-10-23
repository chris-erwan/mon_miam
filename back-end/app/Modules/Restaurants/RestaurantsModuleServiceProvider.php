<?php

namespace App\Modules\Restaurants;

use Illuminate\Support\ServiceProvider;

class RestaurantsModuleServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadRoutesFrom(__DIR__ . '/Routes/api.php');
    }
}