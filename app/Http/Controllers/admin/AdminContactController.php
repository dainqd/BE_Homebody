<?php

namespace App\Http\Controllers\admin;

use App\Enums\ContactStatus;
use App\Http\Controllers\Controller;
use App\Models\Contact;
use Illuminate\Http\Request;

class AdminContactController extends Controller
{
    public function list(Request $request)
    {
        try {
            $size = $request->input('size') ?? 10;
            $size = intval($size);
            $contacts = Contact::where('status', '!=', ContactStatus::DELETED)
                ->orderBy('id', 'desc')
                ->paginate($size);
            return view('admin.contacts.list', compact('contacts'));
        } catch (\Exception $exception) {
            $data = returnMessage(-1, 400, '', $exception->getMessage());
            return response($data, 400);
        }
    }

    public function detail(Request $request, $id)
    {
        try {
            $contact = Contact::where('id', $id)
                ->where('status', '!=', ContactStatus::DELETED)
                ->first();

            if (!$contact) {
                return redirect(route('error.not.found'));
            }

            return view('admin.contacts.detail', compact('contact'));
        } catch (\Exception $exception) {
            $data = returnMessage(-1, 400, '', $exception->getMessage());
            return response($data, 400);
        }
    }
}
