<?php

namespace App\Http\Controllers\admin;

use App\Enums\PartnerRegisterStatus;
use App\Http\Controllers\Controller;
use App\Models\PartnerRegister;
use Illuminate\Http\Request;

class AdminPartnerRegisterController extends Controller
{
    public function list(Request $request)
    {
        $size = $request->input('size') ?? 20;
        $size = intval($size);
        $partners = PartnerRegister::where('status', '!=', PartnerRegisterStatus::DELETED)
            ->orderByDesc('id')
            ->paginate($size);
        return view('admin.partner_register.list', compact('partners'));
    }

    public function detail($id)
    {
        $partner = PartnerRegister::find($id);

        if (!$partner || $partner->status == PartnerRegisterStatus::DELETED) {
            return redirect(route('error.not.found'));
        }
        return view('admin.partner_register.detail', compact('partner'));
    }
}
