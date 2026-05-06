<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\{Course, CourseActivityLog};
use Illuminate\Http\{JsonResponse, Request};

class ActivityLogController extends Controller
{
    /**
     * POST /api/courses/{course}/activity
     * Log a user activity on a course (e.g., visited, material viewed).
     */
    public function log(Request $request, Course $course): JsonResponse
    {
        $data = $request->validate([
            'action'        => 'required|string|max:100',
            'loggable_type' => 'nullable|string',
            'loggable_id'   => 'nullable|integer',
            'meta'          => 'nullable|array',
        ]);

        CourseActivityLog::create([
            'course_id'     => $course->id,
            'user_id'       => $request->user()->id,
            'action'        => $data['action'],
            'loggable_type' => $data['loggable_type'] ?? null,
            'loggable_id'   => $data['loggable_id'] ?? null,
            'meta'          => $data['meta'] ?? null,
            'accessed_at'   => now(),
        ]);

        return response()->json(['message' => 'Aktivitas berhasil dicatat.'], 201);
    }

    /**
     * GET /api/student/activity/recent
     * Last visited courses for the student.
     */
    public function recentCourses(Request $request): JsonResponse
    {
        $logs = CourseActivityLog::where('user_id', $request->user()->id)
            ->where('action', 'visited')
            ->with('course.category')
            ->orderByDesc('accessed_at')
            ->take(10)
            ->get()
            ->unique('course_id');

        return response()->json([
            'data' => $logs->map(fn($log) => [
                'course'      => new \App\Http\Resources\CourseResource($log->course),
                'accessed_at' => $log->accessed_at?->toDateTimeString(),
            ])->values(),
        ]);
    }

    /**
     * GET /api/student/activity/history
     */
    public function history(Request $request): JsonResponse
    {
        $logs = CourseActivityLog::where('user_id', $request->user()->id)
            ->with('course')
            ->orderByDesc('accessed_at')
            ->paginate(20);

        return response()->json([
            'data' => $logs->map(fn($log) => [
                'id'          => $log->id,
                'action'      => $log->action,
                'course'      => $log->course ? ['id' => $log->course->id, 'title' => $log->course->title] : null,
                'meta'        => $log->meta,
                'accessed_at' => $log->accessed_at?->toDateTimeString(),
            ]),
        ]);
    }
}