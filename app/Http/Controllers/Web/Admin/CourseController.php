<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Models\{Course, CourseCategory, User};
use App\Services\CourseService;
use App\Http\Requests\{StoreCourseRequest, UpdateCourseRequest};
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function __construct(private readonly CourseService $courseService) {}

    /**
     * Daftar Mata Kuliah – List Standar & List Manajemen
     */
    public function index(Request $request)
    {
        $view    = $request->get('view', 'standar'); // standar | manajemen
        $perPage = (int) $request->get('per_page', 10);
        $filters = $request->only([
            'keyword','title','code','category_id','language',
            'access_type','is_registered','is_allowed',
            'allow_unsubscribe','is_active','sort','direction',
        ]);

        $courses = $view === 'manajemen'
            ? $this->courseService->listForManagement($filters, $perPage)
            : $this->courseService->listForAdmin($filters, $perPage);

        $categories = CourseCategory::active()->orderBy('name')->get();
        $teachers   = User::teachers()->active()->orderBy('name')->get();

        return view('admin.courses.index', compact('courses', 'categories', 'teachers', 'view', 'filters'));
    }

    /**
     * Form Tambah Mata Kuliah
     */
    public function create()
    {
        $categories    = CourseCategory::active()->orderBy('name')->get();
        $teachers      = User::teachers()->active()->orderBy('name')->get();
        $templateCourses = Course::active()->orderBy('title')->get(['id','title']);

        return view('admin.courses.create', compact('categories', 'teachers', 'templateCourses'));
    }

    /**
     * Simpan Mata Kuliah Baru
     */
    public function store(StoreCourseRequest $request)
    {
        $course = $this->courseService->create(
            $request->validated(),
            $request->hasFile('thumbnail') ? $request->file('thumbnail') : null
        );

        return redirect()
            ->route('admin.courses.index')
            ->with('success', "Mata kuliah \"{$course->title}\" berhasil dibuat.");
    }

    /**
     * Detail Mata Kuliah
     */
    public function show(Course $course)
    {
        $course->load(['category', 'teachers', 'sessions.materials', 'sessions.exercises', 'enrollments']);
        return view('admin.courses.show', compact('course'));
    }

    /**
     * Form Edit Mata Kuliah
     */
    public function edit(Course $course)
    {
        $course->load(['teachers', 'category']);
        $categories      = CourseCategory::active()->orderBy('name')->get();
        $teachers        = User::teachers()->active()->orderBy('name')->get();
        $templateCourses = Course::active()->where('id', '!=', $course->id)->orderBy('title')->get(['id','title']);

        return view('admin.courses.edit', compact('course', 'categories', 'teachers', 'templateCourses'));
    }

    /**
     * Update Mata Kuliah
     */
    public function update(UpdateCourseRequest $request, Course $course)
    {
        $this->courseService->update(
            $course,
            $request->validated(),
            $request->hasFile('thumbnail') ? $request->file('thumbnail') : null
        );

        return redirect()
            ->route('admin.courses.index')
            ->with('success', "Mata kuliah \"{$course->title}\" berhasil diperbarui.");
    }

    /**
     * Hapus Mata Kuliah
     */
    public function destroy(Course $course)
    {
        $title = $course->title;
        $this->courseService->delete($course);
        return redirect()->route('admin.courses.index')
            ->with('success', "Mata kuliah \"{$title}\" berhasil dihapus.");
    }

    /**
     * Buat Cadangan (Duplikat)
     */
    public function duplicate(Course $course)
    {
        $new = $this->courseService->duplicate($course);
        return redirect()->route('admin.courses.edit', $new)
            ->with('success', "Salinan dari \"{$course->title}\" berhasil dibuat.");
    }

    /**
     * Publish / Unpublish
     */
    public function publish(Course $course)
    {
        $this->courseService->publish($course);
        return back()->with('success', "Mata kuliah berhasil dipublikasikan.");
    }

    public function unpublish(Course $course)
    {
        $this->courseService->unpublish($course);
        return back()->with('success', "Mata kuliah berhasil di-unpublish.");
    }
}