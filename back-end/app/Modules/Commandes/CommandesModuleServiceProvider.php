<?php

namespace App\Modules\Commandes;

use Illuminate\Support\ServiceProvider;

class CommandesModuleServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadRoutesFrom(__DIR__ . '/Routes/api.php');
    }
}