<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class LivreurMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next)
{
    if (!$request->user() || $request->user()->role !== 'livreur') {
        return response()->json([
            'message' => 'Access denied'
        ], 403);
    }

    return $next($request);
}
}
