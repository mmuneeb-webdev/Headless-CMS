<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckApiPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $permission): Response
    {
        if (!$request->user()) {
            return response()->json([
                'message' => 'Unauthenticated.'
            ], 401);
        }

        // Switch to API guard for permission check
        if (!$request->user()->hasPermissionTo($permission, 'api')) {
            return response()->json([
                'message' => 'Forbidden. You do not have permission to access this resource.',
                'required_permission' => $permission
            ], 403);
        }

        return $next($request);
    }
}