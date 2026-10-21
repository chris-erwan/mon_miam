<?php

namespace App\Modules\Menu\Http\Controllers;

use App\Modules\Menu\Models\MenuCategory;
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
            'id_restaurant' => ['sometimes', 'integer'],
            'id_categorie' => ['sometimes', 'integer'],
            'disponible' => ['sometimes', 'boolean'],
            'plat_du_jour' => ['sometimes', 'boolean'],
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
            'id_categorie' => ['required', 'integer', 'exists:categories_menu,id_categorie'],
            'nom' => ['required', 'string', 'max:150'],
            'description' => ['nullable', 'string'],
            'prix' => ['required', 'integer', 'min:0'],
            'image' => ['nullable', 'string', 'max:255'],
            'disponible' => ['sometimes', 'boolean'],
            'plat_du_jour' => ['sometimes', 'boolean'],
        ]);

        $category = MenuCategory::findOrFail($data['id_categorie']);

        Gate::authorize('create', [MenuItem::class, $category->id_restaurant]);

        return response()->json($this->service->create($data), 201);
    }

    public function update(Request $request, MenuItem $menuItem): JsonResponse
    {
        Gate::authorize('update', $menuItem);

        $data = $request->validate([
            'id_categorie' => ['sometimes', 'integer', 'exists:categories_menu,id_categorie'],
            'nom' => ['sometimes', 'string', 'max:150'],
            'description' => ['sometimes', 'nullable', 'string'],
            'prix' => ['sometimes', 'integer', 'min:0'],
            'image' => ['sometimes', 'nullable', 'string', 'max:255'],
            'disponible' => ['sometimes', 'boolean'],
            'plat_du_jour' => ['sometimes', 'boolean'],
        ]);

        if (isset($data['id_categorie'])) {
            $target = MenuCategory::findOrFail($data['id_categorie']);

            Gate::authorize('create', [MenuItem::class, $target->id_restaurant]);
        }

        return response()->json($this->service->update($menuItem, $data));
    }

    public function destroy(MenuItem $menuItem): JsonResponse
    {
        Gate::authorize('delete', $menuItem);

        $this->service->delete($menuItem);

        return response()->json(['message' => 'Plat supprimé.']);
    }
}