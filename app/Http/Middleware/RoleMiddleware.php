<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\{Request, JsonResponse};

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     * Usage: middleware('role:admin') or middleware('role:admin,teacher')
     */
    public function handle(Request $request, Closure $next, string ...$roles): mixed
    {
        $user = $request->user();

        if (!$user) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Unauthenticated.'], 401);
            }
            return redirect()->route('login');
        }

        if (!in_array($user->role, $roles, true)) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Anda tidak memiliki akses ke halaman ini.',
                ], 403);
            }
            return match($user->role) {
                'admin'   => redirect()->route('admin.courses.index'),
                'teacher' => redirect()->route('teacher.courses.index'),
                default   => redirect()->route('student.courses.index'),
            };
        }

        return $next($request);
    }
}