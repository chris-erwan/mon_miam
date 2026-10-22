<?php

namespace Database\Seeders;

use App\Modules\Auth\Database\Seeders\StaffSeeder;
use App\Modules\Menu\Database\Seeders\MenuSeeder;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            StaffSeeder::class,
            MenuSeeder::class,
        ]);
    }
}