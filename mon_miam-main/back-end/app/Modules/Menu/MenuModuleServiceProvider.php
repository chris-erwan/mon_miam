<?php

namespace App\Modules\Menu;

use App\Modules\Menu\Models\MenuCategory;
use App\Modules\Menu\Models\MenuItem;
use App\Modules\Menu\Policies\MenuCategoryPolicy;
use App\Modules\Menu\Policies\MenuItemPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class MenuModuleServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Gate::policy(MenuItem::class, MenuItemPolicy::class);
        Gate::policy(MenuCategory::class, MenuCategoryPolicy::class);

        $this->loadRoutesFrom(__DIR__ . '/Routes/api.php');
    }
}