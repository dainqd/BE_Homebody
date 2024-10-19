<?php

namespace App\Http\Middleware\api;

use Closure;
use Exception;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthenticatePermission
{
    /**
     * Handle an incoming request.
     *
     * @param \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
        } catch (TokenInvalidException $e) {
            $data = returnMessage(1, 400, '', 'Token is Invalid!');
            return response($data, 400);
        } catch (TokenExpiredException $e) {
            $data = returnMessage(1, 444, '', 'Token is Expired');
            return response($data, 444);
        } catch (Exception $e) {
            $data = returnMessage(1, 401, '', 'Authorization Token not found');
            return response($data, 401);
        }
        return $next($request);
    }
}
