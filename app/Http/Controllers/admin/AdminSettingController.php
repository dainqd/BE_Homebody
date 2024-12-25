<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Settings;
use Illuminate\Http\Request;

class AdminSettingController extends Controller
{
    public function appSetting(Request $request)
    {
        try {
            $dataFields = [
                "og_title", "og_des", "og_url", "og_site", "brand_name", "home_name",
                "qna_email", "browser_title", "meta_tag", "meta_keyword", "domain_url",
                "email", "phone", "address", "address_detail", "zip",
                "owner_name", "owner_phone", "owner_email", "fax", "business_number",
            ];

            $data = array_combine($dataFields, array_map(fn($field) => $request->input($field), $dataFields));

            if ($request->hasFile('logo')) {
                $item = $request->file('logo');
                $itemPath = $item->store('setting', 'public');
                $logo = asset('storage/' . $itemPath);

                $data['logo'] = $logo;
            }

            if ($request->hasFile('og_img')) {
                $item = $request->file('og_img');
                $itemPath = $item->store('setting', 'public');
                $og_img = asset('storage/' . $itemPath);

                $data['og_img'] = $og_img;
            }

            if ($request->hasFile('favicon')) {
                $item = $request->file('favicon');
                $itemPath = $item->store('setting', 'public');
                $favicon = asset('storage/' . $itemPath);

                $data['favicon'] = $favicon;
            }

            if ($request->hasFile('thumbnail')) {
                $thumbnailPaths = array_map(function ($image) {
                    $itemPath = $image->store('setting', 'public');
                    return asset('storage/' . $itemPath);
                }, $request->file('thumbnail'));
                $thumbnail = implode(',', $thumbnailPaths);

                $data['thumbnail'] = $thumbnail;
            }

            $setting = Settings::firstOrNew([]);
            $setting->fill($data);
            $setting->save();

            alert()->success('Save successfully!');
            return redirect(route('admin.app.setting'));
        } catch (\Exception $exception) {
            \Log::error($exception->getMessage());
            alert()->error('Error, Please try again!');
            return back();
        }
    }

    public function updateTern()
    {

    }

    public function question()
    {

    }
}
