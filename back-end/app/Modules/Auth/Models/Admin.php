<?php
 
namespace App\Modules\Auth\Models;
 
use App\Shared\Contracts\MenuEditor;
use App\Shared\Contracts\PromotionEditor;
use App\Shared\Contracts\SettingEditor;
use App\Shared\Contracts\StatisticsProvider;
use App\Shared\Enums\Role;
use Illuminate\Database\Eloquent\Builder;
 
class Admin extends Manager
{
    public static function roles(): array
    {
        return [Role::Admin];
    }
 
    public static function defaultRole(): Role
    {
        return Role::Admin;
    }
 
    public function worksIn(int $restaurantId): bool
    {
        return true;
    }
 
    protected function restaurantForNewEmployee(array $data): ?int
    {
        return $data['restaurant_id'] ?? null;
    }
 
    // gererEmployes()
    public function manageEmployees(): Builder
    {
        return User::query()->whereIn('role', [
            Role::Employee->value,
            Role::Manager->value,
        ]);
    }
 
    // gererMenu()
    public function manageMenu(): MenuEditor
    {
        return app(MenuEditor::class);
    }
 
    // gererPromotions()
    public function managePromotions(): PromotionEditor
    {
        return app(PromotionEditor::class);
    }
 
    // gererParametres()
    public function manageSettings(): SettingEditor
    {
        return app(SettingEditor::class);
    }
 
    // voirStatsTempsReel()
    public function realTimeStats(): array
    {
        return app(StatisticsProvider::class)->realTime();
    }
}

