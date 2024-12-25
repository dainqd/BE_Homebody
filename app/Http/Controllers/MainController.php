<?php

namespace App\Http\Controllers;

use App\Enums\RoleName;
use App\Models\Role;
use App\Models\RoleUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class MainController extends Controller
{

    public function checkAdmin($user_id)
    {
        $adminRole = ['ADMIN'];
        return $this->checkRoles($adminRole, $user_id);
    }

    private function checkRoles($roleNames, $user_id)
    {
        $hasRole = false;
        if (Auth::check()) {
            $user = Auth::user();
            $user_id = $user->id;
        }
        $role_user = RoleUser::where('user_id', $user_id)->first();
        if ($role_user) {
            $userRoleNames = Role::where('id', $role_user->role_id)->pluck('name');

            foreach ($roleNames as $roleName) {
                if ($userRoleNames->contains($roleName)) {
                    $hasRole = true;
                    break;
                }
            }
        }
        return $hasRole;
    }

    public function setLanguage(Request $request, $locale)
    {
        switch ($locale) {
            case 'vi':
                $lang = 'vi';
                break;
            default:
                $lang = 'en';
                break;
        }
        Session::put('locale', $lang);
        return redirect()->back();
    }

    public function generateRandomString($length)
    {
        $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijkmnopqrstuyvwxyz';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[random_int(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    public function generateRandomNumber($length)
    {
        $characters = '0123456789';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[random_int(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    function vn_to_str($str)
    {

        $unicode = array(

            'a' => 'á|à|ả|ã|ạ|ă|ắ|ặ|ằ|ẳ|ẵ|â|ấ|ầ|ẩ|ẫ|ậ',

            'd' => 'đ',

            'e' => 'é|è|ẻ|ẽ|ẹ|ê|ế|ề|ể|ễ|ệ',

            'i' => 'í|ì|ỉ|ĩ|ị',

            'o' => 'ó|ò|ỏ|õ|ọ|ô|ố|ồ|ổ|ỗ|ộ|ơ|ớ|ờ|ở|ỡ|ợ',

            'u' => 'ú|ù|ủ|ũ|ụ|ư|ứ|ừ|ử|ữ|ự',

            'y' => 'ý|ỳ|ỷ|ỹ|ỵ',

            'A' => 'Á|À|Ả|Ã|Ạ|Ă|Ắ|Ặ|Ằ|Ẳ|Ẵ|Â|Ấ|Ầ|Ẩ|Ẫ|Ậ',

            'D' => 'Đ',

            'E' => 'É|È|Ẻ|Ẽ|Ẹ|Ê|Ế|Ề|Ể|Ễ|Ệ',

            'I' => 'Í|Ì|Ỉ|Ĩ|Ị',

            'O' => 'Ó|Ò|Ỏ|Õ|Ọ|Ô|Ố|Ồ|Ổ|Ỗ|Ộ|Ơ|Ớ|Ờ|Ở|Ỡ|Ợ',

            'U' => 'Ú|Ù|Ủ|Ũ|Ụ|Ư|Ứ|Ừ|Ử|Ữ|Ự',

            'Y' => 'Ý|Ỳ|Ỷ|Ỹ|Ỵ',

        );

        foreach ($unicode as $nonUnicode => $uni) {

            $str = preg_replace("/($uni)/i", $nonUnicode, $str);

        }
        $str = str_replace(' ', '_', $str);

        return $str;

    }

    public function saveRoleUser($user_id)
    {
        $this->saveRole($user_id, RoleName::USER);
    }

    public function saveRolePartner($user_id)
    {
        $this->saveRole($user_id, RoleName::PARTNER);
    }

    private function saveRole($user_id, $role_name)
    {
        RoleUser::where('user_id', $user_id)->delete();
        $role = Role::where('name', $role_name)->first();
        $user_role = new RoleUser();
        $user_role->role_id = $role->id;
        $user_role->user_id = $user_id;
        $user_role->save();
    }

    public function returnMessage($message)
    {
        return ['message' => $message];
    }

    public function test()
    {
        try {
            $data = returnMessage(1, 200, '', 'Success');
            return response($data, 200);
        } catch (\Exception $exception) {
            $data = returnMessage(-1, 400, '', $exception->getMessage());
            return response($data, 400);
        }
    }
}
