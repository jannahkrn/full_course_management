<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\{StoreCourseCategoryRequest, UpdateCourseCategoryRequest};
use App\Http\Resources\CourseCategoryResource;
use App\Models\CourseCategory;
use Illuminate\Http\{JsonResponse, Request};

class CourseCategoryController extends Controller
{
    /**
     * GET /api/categories
     */
    public function index(Request $request): JsonResponse
    {
        $query = CourseCategory::query();

        if ($request->boolean('active_only', false)) {
            $query->active();
        }
        if ($keyword = $request->get('keyword')) {
            $query->where('name', 'like', "%{$keyword}%");
        }

        $categories = $query->withCount('courses')->orderBy('name')->get();

        return response()->json(['data' => CourseCategoryResource::collection($categories)]);
    }

    /**
     * GET /api/categories/{category}
     */
    public function show(CourseCategory $category): JsonResponse
    {
        $category->loadCount('courses');
        return response()->json(['data' => new CourseCategoryResource($category)]);
    }

    /**
     * POST /api/admin/categories
     */
    public function store(StoreCourseCategoryRequest $request): JsonResponse
    {
        $category = CourseCategory::create($request->validated());
        return response()->json([
            'message' => 'Kategori berhasil dibuat.',
            'data'    => new CourseCategoryResource($category),
        ], 201);
    }

    /**
     * PUT /api/admin/categories/{category}
     */
    public function update(UpdateCourseCategoryRequest $request, CourseCategory $category): JsonResponse
    {
        $category->update($request->validated());
        return response()->json([
            'message' => 'Kategori berhasil diperbarui.',
            'data'    => new CourseCategoryResource($category),
        ]);
    }

    /**
     * DELETE /api/admin/categories/{category}
     */
    public function destroy(CourseCategory $category): JsonResponse
    {
        if ($category->courses()->count() > 0) {
            return response()->json([
                'message' => 'Kategori tidak dapat dihapus karena masih memiliki mata kuliah.',
            ], 422);
        }

        $category->delete();
        return response()->json(['message' => 'Kategori berhasil dihapus.']);
    }
}