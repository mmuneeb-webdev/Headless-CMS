<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckApiPermission
{
    public function handle(Request $request, Closure $next, $permission)
    {
        auth()->shouldUse('sanctum');

        $user = auth()->user();

        if (!$user || !$user->can($permission)) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        return $next($request);
    }
}
