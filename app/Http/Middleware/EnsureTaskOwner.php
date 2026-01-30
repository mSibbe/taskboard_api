<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware that ensures the authenticated user
 * can only access tasks they own.
 */
class EnsureTaskOwner
{
    /**
     * Handle an incoming request.
     *
     * This middleware checks whether the task retrieved via
     * route model binding belongs to the currently authenticated user.
     * If not, access is denied.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $task = $request->route('task');
        $user = $request->user();

        if ($task && $task->user_id !== $user->id) {
            return response()->json([
                'message' => 'You are not allowed to access this task.'
            ], Response::HTTP_FORBIDDEN);
        }

        return $next($request);
    }
}
