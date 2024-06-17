<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class HandleApiExceptions
{
    public function handle(Request $request, Closure $next)
    {
        try {
            return $next($request);
        } catch (\Exception $e) {
            if ($e instanceof NotFoundHttpException) {
                return response()->json(['error' => 'Resource not found'], 404);
            }

            if ($e instanceof UnauthorizedHttpException) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }

            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
