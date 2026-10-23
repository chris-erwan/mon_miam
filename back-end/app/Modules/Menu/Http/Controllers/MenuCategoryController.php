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

    public function index(Request $request): JsonResponse
    {
        $filters = $request->validate([
            'id_restaurant' => ['sometimes', 'integer'],
        ]);

        return response()->json($this->service->list($filters));
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
        $data = $request->validate([
            'id_restaurant' => ['required', 'integer'],
            'libelle' => [
                'required',
                'string',
                'max:100',
                Rule::unique('categories_menu', 'libelle')
                    ->where('id_restaurant', $request->input('id_restaurant')),
            ],
            'ordre_affichage' => ['sometimes', 'integer', 'min:0'],
        ]);

        Gate::authorize('create', [MenuCategory::class, $data['id_restaurant']]);

        return response()->json($this->service->create($data), 201);
    }

    public function update(Request $request, MenuCategory $menuCategory): JsonResponse
    {
        Gate::authorize('update', $menuCategory);

        $data = $request->validate([
            'libelle' => [
                'sometimes',
                'string',
                'max:100',
                Rule::unique('categories_menu', 'libelle')
                    ->where('id_restaurant', $menuCategory->id_restaurant)
                    ->ignore($menuCategory->id_categorie, 'id_categorie'),
            ],
            'ordre_affichage' => ['sometimes', 'integer', 'min:0'],
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