<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\TermsAndPolicies;
use Illuminate\Http\Request;

class AdminTermsAndPolicyController extends Controller
{
    public function list(Request $request)
    {
        try {
            $data = TermsAndPolicies::all();
            return view('admin.terms_and_policies.list', compact('data'));
        } catch (\Exception $exception) {
            \Log::error($exception->getMessage());
            alert()->error('Error, Please try again!');
            return back();
        }
    }

    public function detail(Request $request, $id)
    {
        try {
            $data = TermsAndPolicies::where('id', $id)->first();

            if (!$data) {
                return redirect(route('error.not.found'));
            }

            return view('admin.terms_and_policies.detail', compact('data'));
        } catch (\Exception $exception) {
            \Log::error($exception->getMessage());
            alert()->error('Error, Please try again!');
            return back();
        }
    }


    public function create(Request $request)
    {
        try {
            return view('admin.terms_and_policies.create');
        } catch (\Exception $exception) {
            \Log::error($exception->getMessage());
            alert()->error('Error, Please try again!');
            return back();
        }
    }

    public function store(Request $request)
    {
        try {
            $title = $request->input('title');
            $type = $request->input('type');
            $content = $request->input('content');

            $term = TermsAndPolicies::where('type', $type)->first();
            if ($term) {
                alert()->error('Terms and policies already exist!');
                return back();
            }

            $term = new TermsAndPolicies();
            $term->title = $title;
            $term->content = $content;
            $term->type = $type;

            $term->save();

            alert()->success('Create successfully!');
            return redirect(route(''));
        } catch (\Exception $exception) {
            \Log::error($exception->getMessage());
            alert()->error('Error, Please try again!');
            return back();
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $data = TermsAndPolicies::where('id', $id)->first();

            if (!$data) {
                return redirect(route('error.not.found'));
            }

            $title = $request->input('title');
            $type = $request->input('type');
            $content = $request->input('content');

            if ($data->type != $type) {
                $term = TermsAndPolicies::where('type', $type)->first();
                if ($term) {
                    alert()->error('Terms and policies already exist!');
                    return back();
                }
                $data->type = $type;
            }

            $data->title = $title;
            $data->content = $content;

            $data->save();

            alert()->success('Update successfully!');
            return redirect(route(''));
        } catch (\Exception $exception) {
            \Log::error($exception->getMessage());
            alert()->error('Error, Please try again!');
            return back();
        }
    }

    public function delete(Request $request, $id)
    {
        try {
            $data = TermsAndPolicies::where('id', $id)->first();

            if (!$data) {
                return redirect(route('error.not.found'));
            }
            TermsAndPolicies::where('id', $id)->delete();
            alert()->success('Delete successfully!');
            return redirect(route(''));
        } catch (\Exception $exception) {
            \Log::error($exception->getMessage());
            alert()->error('Error, Please try again!');
            return back();
        }
    }
}
