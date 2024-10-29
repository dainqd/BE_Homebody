<?php


use App\Enums\RoleName;
use App\Models\Role;
use App\Models\RoleUser;
use App\Models\User;

function getRoleUser($id)
{
    $user = User::find($id);
    if ($user) {
        $role_user = RoleUser::where('user_id', $id)->first();
        if ($role_user) {
            $role = Role::where('id', $role_user->role_id)->first();
            return $role->name;
        }
    }
    return null;
}

function isAdmin($id)
{
    $user = User::find($id);
    if ($user) {
        $role_user = RoleUser::where('user_id', $id)->first();
        if ($role_user) {
            $roleNames = Role::where('id', $role_user->role_id)->pluck('name');
            if ($roleNames->contains(RoleName::ADMIN)) {
                return true;
            }
        }
    }
    return false;

}

function isPartner($id)
{
    $user = User::find($id);
    if ($user) {
        $role_user = RoleUser::where('user_id', $id)->first();
        if ($role_user) {
            $roleNames = Role::where('id', $role_user->role_id)->pluck('name');
            if ($roleNames->contains(RoleName::PARTNER)) {
                return true;
            }
        }
    }
    return false;
}
