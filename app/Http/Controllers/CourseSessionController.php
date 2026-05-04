<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\{StoreCourseSessionRequest, UpdateCourseSessionRequest};
use App\Http\Resources\CourseSessionResource;
use App\Models\{Course, CourseSession};
use App\Services\CourseService;
use Illuminate\Http\{JsonResponse, Request};

class CourseSessionController extends Controller
{
    public function __construct(private readonly CourseService $courseService) {}

    /**
     * GET /api/courses/{course}/sessions
     */
    public function index(Course $course): JsonResponse
    {
        $sessions = $course->sessions()->with(['materials', 'exercises'])->get();
        return response()->json(['data' => CourseSessionResource::collection($sessions)]);
    }

    /**
     * GET /api/courses/{course}/sessions/{session}
     */
    public function show(Course $course, CourseSession $session): JsonResponse
    {
        abort_if($session->course_id !== $course->id, 404);
        $session->load(['materials', 'exercises']);
        return response()->json(['data' => new CourseSessionResource($session)]);
    }

    /**
     * POST /api/courses/{course}/sessions
     */
    public function store(StoreCourseSessionRequest $request, Course $course): JsonResponse
    {
        $session = $this->courseService->createSession($course, $request->validated());
        return response()->json([
            'message' => 'Sesi berhasil dibuat.',
            'data'    => new CourseSessionResource($session),
        ], 201);
    }

    /**
     * PUT /api/courses/{course}/sessions/{session}
     */
    public function update(UpdateCourseSessionRequest $request, Course $course, CourseSession $session): JsonResponse
    {
        abort_if($session->course_id !== $course->id, 404);
        $session->update($request->validated());
        return response()->json([
            'message' => 'Sesi berhasil diperbarui.',
            'data'    => new CourseSessionResource($session->fresh(['materials', 'exercises'])),
        ]);
    }

    /**
     * DELETE /api/courses/{course}/sessions/{session}
     */
    public function destroy(Course $course, CourseSession $session): JsonResponse
    {
        abort_if($session->course_id !== $course->id, 404);
        $session->delete();
        return response()->json(['message' => 'Sesi berhasil dihapus.']);
    }

    /**
     * POST /api/courses/{course}/sessions/reorder
     * Body: { "session_ids": [3, 1, 2] }
     */
    public function reorder(Request $request, Course $course): JsonResponse
    {
        $request->validate([
            'session_ids'   => 'required|array',
            'session_ids.*' => 'integer|exists:course_sessions,id',
        ]);

        $this->courseService->reorderSessions($course, $request->session_ids);
        return response()->json(['message' => 'Urutan sesi berhasil diperbarui.']);
    }
}