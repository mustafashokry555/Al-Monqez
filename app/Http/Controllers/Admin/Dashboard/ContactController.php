<?php

namespace App\Http\Controllers\Admin\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Helpers\FileStorage;
use App\Http\Requests\Admin\Dashboard\Contacts\DeleteContactRequest;
use App\Models\Contact;

class ContactController extends Controller
{
    use FileStorage;

    public function index()
    {
        $contacts = Contact::select('id', 'name', 'email', 'phone', 'subject', 'read', 'created_at')->orderBy('read')->paginate(10);

        return view('admin.dashboard.contacts.index', compact('contacts'));
    }

    /*-----------------------------------------------------------------------------------------------*/

    public function show($id)
    {
        $contact = Contact::findOrFail($id);

        if ($contact->read == 0) {
            $contact->update([
                'read' => 1
            ]);
        }

        return view('admin.dashboard.contacts.show', compact('contact'));
    }

    /*-----------------------------------------------------------------------------------------------*/

    public function destroy(DeleteContactRequest $request)
    {
        $contact = Contact::findOrFail($request->contact_id);
        $contact->delete();
        $this->deleteFile($contact->image);

        session()->flash('success', __('messages.delete_contact'));
        return redirect(route('admin.contacts.index'));
    }
}
