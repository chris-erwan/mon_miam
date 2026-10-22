<?php

namespace App\Modules\Menu\Http\Controllers;

use App\Modules\Menu\Models\MenuItem;
use App\Modules\Menu\Services\MenuItemService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class MenuItemController
{
    public function __construct(private readonly MenuItemService $service) {}

    public function index(Request $request): JsonResponse
    {
        $filters = $request->validate([
            'restaurant_id' => ['sometimes', 'integer'],
            'category_id' => ['sometimes', 'integer'],
            'available' => ['sometimes', 'boolean'],
            'is_daily' => ['sometimes', 'boolean'],
        ]);

        return response()->json($this->service->list($filters));
    }

    public function show(MenuItem $menuItem): JsonResponse
    {
        return response()->json($menuItem);
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'restaurant_id' => ['required', 'integer'],
            'category_id' => ['required', 'integer', 'exists:menu_categories,id'],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'integer', 'min:0'],
            'available' => ['sometimes', 'boolean'],
            'is_daily' => ['sometimes', 'boolean'],
        ]);

        Gate::authorize('create', [MenuItem::class, $data['restaurant_id']]);

        return response()->json($this->service->create($data), 201);
    }

    public function update(Request $request, MenuItem $menuItem): JsonResponse
    {
        Gate::authorize('update', $menuItem);

        $data = $request->validate([
            'category_id' => ['sometimes', 'integer', 'exists:menu_categories,id'],
            'name' => ['sometimes', 'string', 'max:255'],
            'description' => ['sometimes', 'nullable', 'string'],
            'price' => ['sometimes', 'integer', 'min:0'],
            'available' => ['sometimes', 'boolean'],
            'is_daily' => ['sometimes', 'boolean'],
        ]);

        return response()->json($this->service->update($menuItem, $data));
    }

    public function destroy(MenuItem $menuItem): JsonResponse
    {
        Gate::authorize('delete', $menuItem);

        $this->service->delete($menuItem);

        return response()->json(['message' => 'Plat supprimé.']);
    }
}