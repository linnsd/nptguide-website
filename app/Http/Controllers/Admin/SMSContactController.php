<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Contact;
use Flash;

class SMSContactController extends Controller
{
    public function index()
    {
        // $contacts = Contact::all();
        $contacts = new Contact();
        $contacts = $contacts->orderBy('created_at','desc')->paginate(10);
        
        return view('admin.sms_contact.index',compact('contacts'))->with('i', (request()->input('page', 1) - 1) * 10);
    }

    public function destroy($id)
    {
        // dd($id);
        $contact = Contact::find($id)->delete();

        Flash::success(__('lang.deleted_successfully', ['operator' => __('lang.contact')]));

        return redirect(route('sms_contacts.index'));
    }
}
