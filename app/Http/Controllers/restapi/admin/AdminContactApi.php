<?php

namespace App\Http\Controllers\restapi\admin;

use App\Enums\ContactStatus;
use App\Http\Controllers\Controller;
use App\Models\Contact;
use Illuminate\Http\Request;

class AdminContactApi extends Controller
{
    public function update(Request $request, $id)
    {
        try {
            $contact = Contact::where('id', $id)
                ->where('status', '!=', ContactStatus::DELETED)
                ->first();

            if (!$contact) {
                $data = returnMessage(-1, 400, '', 'Contact not found!');
                return response($data, 400);
            }

            $status = $request->input('status');
            $contact->status = $status;
            $contact->save();

            $data = returnMessage(1, 200, $contact, 'Success');
            return response($data, 200);
        } catch (\Exception $exception) {
            $data = returnMessage(-1, 400, '', $exception->getMessage());
            return response($data, 400);
        }
    }

    public function delete(Request $request, $id)
    {
        try {
            $contact = Contact::where('id', $id)
                ->where('status', '!=', ContactStatus::DELETED)
                ->first();

            if (!$contact) {
                $data = returnMessage(-1, 400, '', 'Contact not found!');
                return response($data, 400);
            }

            $contact->status = ContactStatus::DELETED;
            $contact->save();

            $data = returnMessage(1, 200, $contact, 'Delete success');
            return response($data, 200);
        } catch (\Exception $exception) {
            $data = returnMessage(-1, 400, '', $exception->getMessage());
            return response($data, 400);
        }
    }
}
