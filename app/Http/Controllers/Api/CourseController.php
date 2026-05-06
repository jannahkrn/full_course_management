<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\{StoreCourseRequest, UpdateCourseRequest};
use App\Http\Resources\CourseResource;
use App\Models\Course;
use App\Services\CourseService;
use Illuminate\Http\{JsonResponse, Request};

class CourseController extends Controller
{
    public function __construct(private readonly CourseService $courseService) {}

    // ─── Admin: Standard list ─────────────────────────────────────────

    /**
     * GET /api/admin/courses
     * Supports: keyword, title, code, category_id, language, access_type,
     *           is_registered, is_allowed, allow_unsubscribe, is_active,
     *           sort, direction, per_page
     */
    public function index(Request $request): JsonResponse
    {
        $perPage = (int) $request->get('per_page', 10);
        $courses = $this->courseService->listForAdmin($request->all(), $perPage);

        return response()->json([
            'data' => CourseResource::collection($courses),
            'meta' => [
                'current_page' => $courses->currentPage(),
                'last_page'    => $courses->lastPage(),
                'per_page'     => $courses->perPage(),
                'total'        => $courses->total(),
                'from'         => $courses->firstItem(),
                'to'           => $courses->lastItem(),
            ],
        ]);
    }

    // ─── Admin: Management list ───────────────────────────────────────

    /**
     * GET /api/admin/courses/management
     */
    public function management(Request $request): JsonResponse
    {
        $perPage = (int) $request->get('per_page', 10);
        $courses = $this->courseService->listForManagement($request->all(), $perPage);

        return response()->json([
            'data' => CourseResource::collection($courses),
            'meta' => [
                'current_page' => $courses->currentPage(),
                'last_page'    => $courses->lastPage(),
                'per_page'     => $courses->perPage(),
                'total'        => $courses->total(),
            ],
        ]);
    }

    // ─── Teacher: Catalog ─────────────────────────────────────────────

    /**
     * GET /api/teacher/courses/catalog
     */
    public function teacherCatalog(Request $request): JsonResponse
    {
        $teacher = $request->user();
        $perPage = (int) $request->get('per_page', 10);
        $courses = $this->courseService->catalogForTeacher($teacher, $request->all(), $perPage);

        return response()->json([
            'data' => CourseResource::collection($courses),
            'meta' => [
                'current_page' => $courses->currentPage(),
                'last_page'    => $courses->lastPage(),
                'per_page'     => $courses->perPage(),
                'total'        => $courses->total(),
            ],
        ]);
    }

    // ─── Student: My Courses ──────────────────────────────────────────

    /**
     * GET /api/student/courses
     */
    public function studentCourses(Request $request): JsonResponse
    {
        $student = $request->user();
        $perPage = (int) $request->get('per_page', 10);
        $courses = $this->courseService->listForStudent($student, $request->all(), $perPage);

        return response()->json([
            'data' => CourseResource::collection($courses),
            'meta' => [
                'current_page' => $courses->currentPage(),
                'last_page'    => $courses->lastPage(),
                'per_page'     => $courses->perPage(),
                'total'        => $courses->total(),
            ],
        ]);
    }

    // ─── Show ─────────────────────────────────────────────────────────

    /**
     * GET /api/courses/{course}
     */
    public function show(Course $course): JsonResponse
    {
        $course->load(['category', 'teachers', 'enrollments', 'sessions.materials', 'sessions.exercises']);
        return response()->json(['data' => new CourseResource($course)]);
    }

    // ─── Store ────────────────────────────────────────────────────────

    /**
     * POST /api/admin/courses
     */
    public function store(StoreCourseRequest $request): JsonResponse
    {
        $course = $this->courseService->create(
            $request->validated(),
            $request->hasFile('thumbnail') ? $request->file('thumbnail') : null
        );

        return response()->json([
            'message' => 'Mata kuliah berhasil dibuat.',
            'data'    => new CourseResource($course),
        ], 201);
    }

    // ─── Update ───────────────────────────────────────────────────────

    /**
     * PUT/PATCH /api/admin/courses/{course}
     */
    public function update(UpdateCourseRequest $request, Course $course): JsonResponse
    {
        $course = $this->courseService->update(
            $course,
            $request->validated(),
            $request->hasFile('thumbnail') ? $request->file('thumbnail') : null
        );

        return response()->json([
            'message' => 'Mata kuliah berhasil diperbarui.',
            'data'    => new CourseResource($course),
        ]);
    }

    // ─── Delete ───────────────────────────────────────────────────────

    /**
     * DELETE /api/admin/courses/{course}
     */
    public function destroy(Course $course): JsonResponse
    {
        $this->courseService->delete($course);

        return response()->json(['message' => 'Mata kuliah berhasil dihapus.']);
    }

    // ─── Duplicate (Buat Cadangan) ────────────────────────────────────

    /**
     * POST /api/admin/courses/{course}/duplicate
     */
    public function duplicate(Course $course): JsonResponse
    {
        $newCourse = $this->courseService->duplicate($course);

        return response()->json([
            'message' => 'Mata kuliah berhasil diduplikasi.',
            'data'    => new CourseResource($newCourse),
        ], 201);
    }

    // ─── Publish / Unpublish ──────────────────────────────────────────

    /**
     * POST /api/admin/courses/{course}/publish
     */
    public function publish(Course $course): JsonResponse
    {
        $course = $this->courseService->publish($course);
        return response()->json(['message' => 'Mata kuliah berhasil dipublikasikan.', 'data' => new CourseResource($course)]);
    }

    /**
     * POST /api/admin/courses/{course}/unpublish
     */
    public function unpublish(Course $course): JsonResponse
    {
        $course = $this->courseService->unpublish($course);
        return response()->json(['message' => 'Mata kuliah berhasil di-unpublish.', 'data' => new CourseResource($course)]);
    }

    // ─── Detail for student ───────────────────────────────────────────

    /**
     * GET /api/student/courses/{course}/detail
     * Shows Materi + Latihan tabs.
     */
    public function studentDetail(Request $request, Course $course): JsonResponse
    {
        $user = $request->user();

        // Check enrollment
        $enrollment = $course->enrollments()
                             ->where('user_id', $user->id)
                             ->where('status', 'active')
                             ->first();

        if (!$enrollment) {
            return response()->json(['message' => 'Anda tidak terdaftar di mata kuliah ini.'], 403);
        }

        $course->load(['category', 'teachers', 'sessions.materials', 'sessions.exercises']);

        return response()->json(['data' => new CourseResource($course)]);
    }
}