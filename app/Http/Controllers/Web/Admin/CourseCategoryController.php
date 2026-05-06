<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Models\CourseCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CourseCategoryController extends Controller
{
    public function index(Request $request)
    {
        $keyword    = $request->get('keyword', '');
        $perPage    = (int) $request->get('per_page', 10);

        $categories = CourseCategory::withCount('courses')
            ->when($keyword, fn($q) => $q->where('name', 'like', "%{$keyword}%"))
            ->orderBy('name')
            ->paginate($perPage)
            ->withQueryString();

        return view('admin.categories.index', compact('categories', 'keyword'));
    }

    public function create()
    {
        return view('admin.categories.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:255|unique:course_categories,name',
            'description' => 'nullable|string',
            'is_active'   => 'boolean',
        ]);

        $data['slug']      = Str::slug($data['name']);
        $data['is_active'] = $request->boolean('is_active', true);

        $cat = CourseCategory::create($data);

        return redirect()->route('admin.categories.index')
            ->with('success', "Kategori \"{$cat->name}\" berhasil dibuat.");
    }

    public function show(CourseCategory $category)
    {
        $category->loadCount('courses');
        return redirect()->route('admin.categories.edit', $category);
    }

    public function edit(CourseCategory $category)
    {
        $category->loadCount('courses');
        return view('admin.categories.edit', compact('category'));
    }

    public function update(Request $request, CourseCategory $category)
    {
        $data = $request->validate([
            'name'        => ['required','string','max:255', \Illuminate\Validation\Rule::unique('course_categories','name')->ignore($category->id)],
            'description' => 'nullable|string',
            'is_active'   => 'boolean',
        ]);

        $data['slug']      = Str::slug($data['name']);
        $data['is_active'] = $request->boolean('is_active');
        $category->update($data);

        return redirect()->route('admin.categories.index')
            ->with('success', "Kategori \"{$category->name}\" berhasil diperbarui.");
    }

    public function destroy(CourseCategory $category)
    {
        $name = $category->name;
        $category->delete();
        return redirect()->route('admin.categories.index')
            ->with('success', "Kategori \"{$name}\" berhasil dihapus.");
    }
}