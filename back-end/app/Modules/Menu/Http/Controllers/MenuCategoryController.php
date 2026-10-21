<?php

namespace App\Modules\Menu\Http\Controllers;

use App\Modules\Menu\Models\MenuCategory;
use App\Modules\Menu\Services\MenuCategoryService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class MenuCategoryController
{
    public function __construct(private readonly MenuCategoryService $service) {}

    public function index(): JsonResponse
    {
        return response()->json($this->service->list());
    }

    public function show(MenuCategory $menuCategory): JsonResponse
    {
        return response()->json($menuCategory);
    }

    public function items(MenuCategory $menuCategory): JsonResponse
    {
        return response()->json($this->service->items($menuCategory));
    }

    public function store(Request $request): JsonResponse
    {
        Gate::authorize('create', MenuCategory::class);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:menu_categories,name'],
        ]);

        return response()->json($this->service->create($data), 201);
    }

    public function update(Request $request, MenuCategory $menuCategory): JsonResponse
    {
        Gate::authorize('update', $menuCategory);

        $data = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('menu_categories', 'name')->ignore($menuCategory->id),
            ],
        ]);

        return response()->json($this->service->update($menuCategory, $data));
    }

    public function destroy(MenuCategory $menuCategory): JsonResponse
    {
        Gate::authorize('delete', $menuCategory);

        $this->service->delete($menuCategory);

        return response()->json(['message' => 'Catégorie supprimée.']);
    }
}
