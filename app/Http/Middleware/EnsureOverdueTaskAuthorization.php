<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware that restricts editing of overdue tasks.
 *
 * Only authorized users (e.g. admins) are allowed
 * to modify tasks whose deadlines have already passed.
 */
class EnsureOverdueTaskAuthorization
{
    /**
     * Handle an incoming request.
     *
     * This middleware checks whether the requested task is overdue.
     * If so, only authorized users are allowed to proceed.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $task = $request->route('task');
        $user = $request->user();

        if ($task && $task->isOverdue())
        {
            if (! $user->isAdmin())
            {
                return response()->json([
                    'message' => 'Only authorized users may edit overdue tasks.'
                ], Response::HTTP_FORBIDDEN);
            }
        }
        return $next($request);
    }
}
