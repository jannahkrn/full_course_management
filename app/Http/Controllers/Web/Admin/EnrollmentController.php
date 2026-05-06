<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Models\{Course, User};
use App\Services\EnrollmentService;
use Illuminate\Http\Request;

class EnrollmentController extends Controller
{
    public function __construct(private readonly EnrollmentService $enrollmentService) {}

    /**
     * Daftar peserta di suatu mata kuliah
     */
    public function index(Request $request, Course $course)
    {
        $enrollments = $this->enrollmentService->getCourseEnrollments(
            $course,
            $request->only(['keyword', 'status']),
            (int) $request->get('per_page', 10)
        );
        return view('admin.enrollments.index', compact('course', 'enrollments'));
    }

    /**
     * Enroll users ke course langsung dari halaman course
     */
    public function enroll(Request $request, Course $course)
    {
        $request->validate([
            'user_ids'   => 'required|array',
            'user_ids.*' => 'exists:users,id',
        ]);

        $result = $this->enrollmentService->enrollUsers($course, $request->user_ids);

        return back()->with('success',
            count($result['enrolled']) . ' pengguna berhasil didaftarkan.'
        );
    }

    /**
     * Unenroll satu user dari course
     */
    public function unenroll(Course $course, int $userId)
    {
        $this->enrollmentService->unenrollUser($course, $userId);
        return back()->with('success', 'Pengguna berhasil dikeluarkan dari mata kuliah.');
    }

    /**
     * Halaman Tambah Pengguna ke Mata Kuliah (bulk enroll)
     */
    public function addUsersForm(Request $request)
    {
        $users   = User::students()->active()->orderBy('name')->get(['id','name','email']);
        $courses = Course::active()->with('category')->orderBy('title')->get(['id','title','category_id']);
        return view('admin.enrollments.add-users', compact('users', 'courses'));
    }

    /**
     * Proses bulk enroll
     */
    public function bulkEnroll(Request $request)
    {
        $request->validate([
            'user_ids'    => 'required|array|min:1',
            'user_ids.*'  => 'exists:users,id',
            'course_ids'  => 'required|array|min:1',
            'course_ids.*'=> 'exists:courses,id',
        ]);

        $this->enrollmentService->bulkEnroll($request->user_ids, $request->course_ids);

        return redirect()->route('admin.courses.index')
            ->with('success', 'Pendaftaran massal berhasil diproses.');
    }
}