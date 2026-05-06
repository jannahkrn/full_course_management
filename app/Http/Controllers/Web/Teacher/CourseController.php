<?php

namespace App\Http\Controllers\Web\Teacher;

use App\Http\Controllers\Controller;
use App\Models\{Course, CourseCategory};
use App\Services\CourseService;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function __construct(private readonly CourseService $courseService) {}

    public function index(Request $request)
    {
        $filters = $request->only(['keyword', 'category_id', 'sort_by', 'direction']);
        $perPage = (int) $request->get('per_page', 10);

        $courses    = $this->courseService->catalogForTeacher(auth()->user(), $filters, $perPage);
        $categories = CourseCategory::active()->orderBy('name')->get();

        return view('teacher.courses.index', compact('courses', 'categories', 'filters'));
    }

    public function show(Course $course)
    {
        $course->load(['category', 'teachers', 'sessions', 'enrollments.user']);
        return view('teacher.courses.show', compact('course'));
    }
}