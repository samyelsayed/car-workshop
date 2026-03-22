<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
       // التأكد إن المستخدم مسجل دخول (وده متغطي بـ Sanctum بس زيادة تأكيد)
    // والتأكد إن الـ role بتاعه admin
    if (auth()->check() && auth()->user()->role === 'admin') {
        return $next($request);
    }

    // لو مش آدمن، اطرده بـ 403 (Forbidden)
    return response()->json([
        'status' => false,
        'message' => 'Access Denied! You do not have admin privileges.',
    ], 403);
    }
}
