<?php

namespace App\Http\Controllers;

use App\Enums\RoleName;
use App\Enums\UserStatus;
use App\Models\Role;
use App\Models\RoleUser;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    public function processLogin()
    {
        if (Auth::check()) {
            return redirect(route('admin.home'));
        }

        return view('auth.login');
    }

    public function handleLogin(Request $request)
    {
        try {
            $loginRequest = $request->input('login_request');
            $password = $request->input('password');

            $credentials = [
                'password' => $password,
            ];

            if (filter_var($loginRequest, FILTER_VALIDATE_EMAIL)) {
                $credentials['email'] = $loginRequest;
            } else {
                $credentials['phone'] = $loginRequest;
            }

            $user = User::where('email', $loginRequest)->orWhere('phone', $loginRequest)->first();
            if (!$user) {
                alert()->error('Account not found!');
                return redirect()->back();
            }

            if ($user->status == UserStatus::INACTIVE) {
                alert()->error('User is inactive!');
                return redirect()->back();
            }

            if ($user->status == UserStatus::BLOCKED) {
                alert()->error('User has been blocked!');
                return redirect()->back();
            }

            if ($user->status == UserStatus::DELETED) {
                alert()->error('User has been deleted!');
                return redirect()->back();
            }

            $roleAdmin = Role::where('name', RoleName::ADMIN)->first();
            if (!$roleAdmin){
                alert()->error('Role admin not found!');
                return redirect()->back();
            }

            $user_role = RoleUser::where('user_id', $user->id)->where('role_id', $roleAdmin->id)->first();
            if (!$user_role){
                alert()->error('User is not permission!');
                return redirect()->back();
            }

            if (Auth::attempt($credentials)) {
                $token = JWTAuth::fromUser($user);
                $user->save();

                $response = $user->toArray();
                $roleUser = RoleUser::where('user_id', $user->id)->first();
                $role = Role::find($roleUser->role_id);
                $response['role'] = $role->name;
                $response['accessToken'] = $token;

                $expiration_time = time() + 86400;
                setCookie('accessToken', $token, $expiration_time, '/');

                return redirect(route('admin.home'));
            }

            alert()->error('Login fail! Please check email or password');
            return redirect()->back();
        } catch (\Exception $exception) {
            alert()->error($exception->getMessage());
            return redirect()->back();
        }
    }

    private function setCookie($token)
    {
        $cookie = cookie('accessToken', $token, 60);
    }


    /**
     * Logs out the current user and redirects to the home page.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout()
    {
        if (Auth::check()) {
            Auth::logout();
        }

        return redirect('/');
    }
}
