<?php

namespace App\Http\Middleware;

use Closure;
use Exception;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;

class JWTMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // Memeriksa apakah token JWT valid
        $token = $request->bearerToken();
        if (!$token) {
            return response()->json(['error' => 'Token tidak ditemukan'], 401);
        }

        try {
            // Verifikasi token
            $user = JWTAuth::setToken($token)->authenticate();
        } catch (TokenExpiredException $e) {
            return response()->json(['error' => 'Token telah kadaluarsa'], 401);
        } catch (TokenInvalidException $e) {
            return response()->json(['error' => 'Token tidak valid'], 401);
        } catch (JWTException $e) {
            return response()->json(['error' => 'Token tidak dapat diproses'], 401);
        }

        if (!$user) {
            return response()->json(['error' => 'User tidak ditemukan'], 401);
        }

        // Set user ke dalam request untuk akses lebih lanjut
        $request->attributes->set('user', $user);

        return $next($request);
    }
}
