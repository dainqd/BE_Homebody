<?php

namespace App\Http\Controllers\admin;

use App\Enums\RoleName;
use App\Enums\UserStatus;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class AdminUserController extends Controller
{
    public function list(Request $request)
    {
        $size = $request->input('size') ?? 20;
        $size = intval($size);
        $users = User::where('users.status', '!=', UserStatus::DELETED)
            ->join('role_users', 'users.id', '=', 'role_users.user_id')
            ->join('roles', 'role_users.role_id', '=', 'roles.id')
            ->orderByDesc('users.id')
            ->where('roles.name', '!=', RoleName::ADMIN)
            ->select('users.*', 'roles.name as role_name')
            ->paginate($size);

        return view('admin.users.list', compact('users'));
    }

    public function detail($id)
    {
        $user = User::where('users.status', '!=', UserStatus::DELETED)->where('id', $id)->first();
        if (!$user) {
            return redirect(route('error.not.found'));
        }
        return view('admin.users.detail', compact('user'));
    }

    public function create()
    {
        return view('admin.users.create');
    }
}
