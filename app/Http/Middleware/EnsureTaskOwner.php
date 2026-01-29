<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureTaskOwner
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

        if ($task && $task->user_id !== $user->id) {
            return response()->json([
                'message' => 'You are not allowed to access this task.'
            ], Response::HTTP_FORBIDDEN);
        }

        return $next($request);
    }
}
