<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureOverdueTaskAuthorization
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
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
