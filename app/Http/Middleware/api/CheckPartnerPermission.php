<?php

namespace App\Http\Middleware\api;

use App\Enums\RoleName;
use App\Models\Role;
use App\Models\RoleUser;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Facades\JWTAuth;

class CheckPartnerPermission
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
            $role_user = RoleUser::where('user_id', $user->id)->first();
            $roleNames = Role::where('id', $role_user->role_id)->pluck('name');
            if ($roleNames->contains(RoleName::PARTNER) || $roleNames->contains(RoleName::ADMIN)) {
                return $next($request);
            }
        } catch (TokenInvalidException $e) {
            $data = returnMessage(1, 400, '', 'Token is Invalid!');
            return response($data, 400);
        } catch (TokenExpiredException $e) {
            $data = returnMessage(1, 444, '', 'Token is Expired');
            return response($data, 444);
        } catch (\Exception $e) {
            $data = returnMessage(1, 401, '', 'Authorization Token not found');
            return response($data, 401);
        }
        $data = returnMessage(1, 403, '', 'Forbidden: You don’t have permission to access [directory] on this server');
        return response($data, 403);
    }
}
