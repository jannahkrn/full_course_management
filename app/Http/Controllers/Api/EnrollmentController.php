<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\{EnrollUserRequest, BulkEnrollRequest};
use App\Http\Resources\{EnrollmentResource, UserResource};
use App\Models\{Course, Enrollment};
use App\Services\EnrollmentService;
use Illuminate\Http\{JsonResponse, Request};

class EnrollmentController extends Controller
{
    public function __construct(private readonly EnrollmentService $enrollmentService) {}

    // ─── List enrollments for a course (Admin) ────────────────────────

    /**
     * GET /api/admin/courses/{course}/enrollments
     */
    public function index(Request $request, Course $course): JsonResponse
    {
        $perPage     = (int) $request->get('per_page', 10);
        $enrollments = $this->enrollmentService->getCourseEnrollments($course, $request->all(), $perPage);

        return response()->json([
            'data' => EnrollmentResource::collection($enrollments),
            'meta' => [
                'current_page' => $enrollments->currentPage(),
                'last_page'    => $enrollments->lastPage(),
                'per_page'     => $enrollments->perPage(),
                'total'        => $enrollments->total(),
            ],
        ]);
    }

    // ─── Enroll users into a course (Admin) ───────────────────────────

    /**
     * POST /api/admin/courses/{course}/enrollments
     * Body: { "user_ids": [1, 2, 3] }
     */
    public function enroll(EnrollUserRequest $request, Course $course): JsonResponse
    {
        $result = $this->enrollmentService->enrollUsers($course, $request->user_ids);

        return response()->json([
            'message'          => count($result['enrolled']) . ' pengguna berhasil didaftarkan.',
            'enrolled'         => $result['enrolled'],
            'already_enrolled' => $result['already_enrolled'],
        ]);
    }

    // ─── Bulk enroll (Admin: Add Users to Course page) ────────────────

    /**
     * POST /api/admin/enrollments/bulk
     * Body: { "user_ids": [...], "course_ids": [...] }
     */
    public function bulkEnroll(BulkEnrollRequest $request): JsonResponse
    {
        $results = $this->enrollmentService->bulkEnroll(
            $request->user_ids,
            $request->course_ids
        );

        return response()->json([
            'message' => 'Proses pendaftaran massal selesai.',
            'results' => $results,
        ]);
    }

    // ─── Unenroll a user (Admin) ──────────────────────────────────────

    /**
     * DELETE /api/admin/courses/{course}/enrollments/{user}
     */
    public function unenroll(Course $course, int $userId): JsonResponse
    {
        $enrollment = $this->enrollmentService->unenrollUser($course, $userId);

        return response()->json([
            'message' => 'Pengguna berhasil dikeluarkan dari mata kuliah.',
            'data'    => new EnrollmentResource($enrollment),
        ]);
    }

    // ─── Student self-unenroll ────────────────────────────────────────

    /**
     * DELETE /api/student/courses/{course}/unenroll
     */
    public function selfUnenroll(Request $request, Course $course): JsonResponse
    {
        $this->enrollmentService->selfUnenroll($course, $request->user());

        return response()->json(['message' => 'Anda telah berhenti dari mata kuliah ini.']);
    }

    // ─── Student: list my enrollments ────────────────────────────────

    /**
     * GET /api/student/enrollments
     */
    public function myEnrollments(Request $request): JsonResponse
    {
        $status      = $request->get('status', 'active');
        $enrollments = $this->enrollmentService->getUserEnrollments($request->user(), $status);

        return response()->json(['data' => EnrollmentResource::collection($enrollments)]);
    }

    // ─── Mark as completed ────────────────────────────────────────────

    /**
     * PATCH /api/admin/courses/{course}/enrollments/{userId}/complete
     */
    public function markCompleted(Course $course, int $userId): JsonResponse
    {
        $enrollment = $this->enrollmentService->completeEnrollment($course, $userId);

        return response()->json([
            'message' => 'Enrollment ditandai selesai.',
            'data'    => new EnrollmentResource($enrollment),
        ]);
    }
}