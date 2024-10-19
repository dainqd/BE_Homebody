<?php

namespace App\Http\Controllers\restapi;

use App\Enums\UserStatus;
use App\Http\Controllers\Controller;
use App\Http\Controllers\MainController;
use App\Models\Role;
use App\Models\RoleUser;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use OpenApi\Annotations as OA;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthApi extends Controller
{

    /**
     * @OA\Post(
     *     path="/api/auth/login",
     *     summary="User login",
     *     description="User login",
     *     tags={"Auth"},
     *     @OA\RequestBody(
     *         description="User login info",
     *         required=true,
     *         @OA\JsonContent(
     *             required={"login_request", "password"},
     *             @OA\Property(property="login_request", type="string", example="user@example.com"),
     *             @OA\Property(property="password", type="string", format="password", example="Passw0rd"),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="full_name", type="string", example="Nguyen Van A"),
     *             @OA\Property(property="email", type="string", format="email", example="user@example.com"),
     *             @OA\Property(property="phone", type="string", example="123456789"),
     *             @OA\Property(property="role", type="string", example="admin"),
     *             @OA\Property(property="accessToken", type="string", example="abcxyz1234567890"),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad Request",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Th  t b i, Vui l ng ki m tra l i t i kho n ho c m t kh u!"),
     *         ),
     *     ),
     * )
     */
    public function login(Request $request)
    {
        $newController = (new MainController());
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
                $data = returnMessage(-1, 404, '', 'User not found!');
                return response($data, 400);
            }

            if ($user->status == UserStatus::INACTIVE) {
                $data = returnMessage(-1, 400, '', 'User not active!');
                return response($data, 400);
            }

            if ($user->status == UserStatus::BLOCKED) {
                $data = returnMessage(-1, 400, '', 'User has been blocked!');
                return response($data, 400);
            }

            if ($user->status == UserStatus::DELETED) {
                $data = returnMessage(-1, 400, '', 'User is deleted!');
                return response($data, 400);
            }

            if (Auth::attempt($credentials)) {
                $token = JWTAuth::fromUser($user);
                $user->save();

                $response = $user->toArray();
                $roleUser = RoleUser::where('user_id', $user->id)->first();
                $role = Role::find($roleUser->role_id);
                $response['role'] = $role->name;
                $response['accessToken'] = $token;
                $data = returnMessage(1, 200, $response, 'Login success!');
                return response($data, 200);
            }
            $data = returnMessage(-1, 400, '', 'Login fail! Please check email or password');
            return response($data, 400);
        } catch (\Exception $exception) {
            $data = returnMessage(-1, 400, '', $exception->getMessage());
            return response($data, 400);
        }
    }

    /**
     * Register a new user.
     *
     * @OA\Post(
     *     path="/api/auth/register",
     *     summary="Register a new user",
     *     description="Register a new user",
     *     tags={"Auth"},
     *     operationId="register",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="name", type="string", example="Nguy n V n A"),
     *             @OA\Property(property="email", type="string", format="email", example="nguyenvana@gmail.com"),
     *             @OA\Property(property="phone", type="string", example="0123456789"),
     *             @OA\Property(property="password", type="string", format="password", example="123456"),
     *             @OA\Property(property="password_confirm", type="string", format="password", example="123456"),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Register success!"),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad Request",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Email invalid!"),
     *         ),
     *     ),
     * )
     */
    public function register(Request $request)
    {
        $newController = (new MainController());
        try {
            $name = $request->input('name');
            $email = $request->input('email');
            $phone = $request->input('phone');
            $password = $request->input('password');
            $password_confirm = $request->input('password_confirm');

            $isEmail = filter_var($email, FILTER_VALIDATE_EMAIL);
            if (!$isEmail) {
                $data = returnMessage(-1, 400, '', 'Email invalid!');
                return response($data, 400);
            }

            $is_valid = User::checkEmail($email);
            if (!$is_valid) {
                $data = returnMessage(-1, 400, '', 'Email already exited!');
                return response($data, 400);
            }

            $is_valid = User::checkPhone($phone);
            if (!$is_valid) {
                $data = returnMessage(-1, 400, '', 'Phone already exited!');
                return response($data, 400);
            }

            if ($password != $password_confirm) {
                $data = returnMessage(-1, 400, '', 'Password or Password Confirm incorrect!');
                return response($data, 400);
            }

            if (strlen($password) < 5) {
                $data = returnMessage(-1, 400, '', 'Password invalid!');
                return response($data, 400);
            }

            $passwordHash = Hash::make($password);

            $user = new User();

            $user->full_name = $name ?? '';
            $user->phone = $phone;
            $user->email = $email;
            $user->password = $passwordHash;

            $user->address = '';
            $user->about = '';

            $user->status = UserStatus::ACTIVE;

            $success = $user->save();

            $newController->saveRoleUser($user->id);

            if ($success) {
                $data = returnMessage(1, 200, $user, 'Register success!');
                return response($data, 200);
            }
            $data = returnMessage(-1, 400, '', 'Register failed!');
            return response($data, 400);
        } catch (\Exception $exception) {
            $data = returnMessage(-1, 400, '', $exception->getMessage());
            return response($data, 400);
        }
    }

    public function logout(Request $request)
    {
        $newController = (new MainController());
        try {
            return response($newController->returnMessage('Logout success!'), 200);
        } catch (\Exception $exception) {
            return response($newController->returnMessage($exception->getMessage()), 400);
        }
    }
}
