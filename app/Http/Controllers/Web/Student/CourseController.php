<?php

namespace App\Http\Controllers\Web\Student;

use App\Http\Controllers\Controller;
use App\Models\{Course, CourseCategory};
use App\Services\{CourseService, EnrollmentService};
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function __construct(
        private readonly CourseService $courseService,
        private readonly EnrollmentService $enrollmentService
    ) {}

    public function index(Request $request)
    {
        $filters    = $request->only(['keyword', 'category_id']);
        $perPage    = (int) $request->get('per_page', 10);
        $view       = $request->get('view', 'grid'); // grid | list

        $courses    = $this->courseService->listForStudent(auth()->user(), $filters, $perPage);
        $categories = CourseCategory::active()->orderBy('name')->get();

        return view('student.courses.index', compact('courses', 'categories', 'filters', 'view'));
    }

    public function show(Course $course)
    {
        $course->load(['category', 'teachers', 'sessions.materials', 'sessions.exercises']);
        return view('student.courses.show', compact('course'));
    }

    public function unenroll(Course $course)
    {
        $this->enrollmentService->selfUnenroll($course, auth()->user());

        return redirect()->route('student.courses.index')
            ->with('success', "Kamu telah keluar dari mata kuliah \"{$course->title}\".");
    }
}