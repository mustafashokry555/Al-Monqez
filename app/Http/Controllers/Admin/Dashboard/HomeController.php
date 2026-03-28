<?php

namespace App\Http\Controllers\Admin\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use App\Models\User;

class HomeController extends Controller
{
    public function index()
    {
        $total_admins = User::where('role_id', 2)->count();
        $total_clients = User::where('role_id', 4)->count();

        $total_contacts = Contact::count();
        $total_read_contacts = Contact::where('read', 1)->count();
        $total_non_read_contacts = Contact::where('read', 0)->count();

        $admins = User::where('role_id', 2)->select('id', 'name', 'phone', 'email', 'blocked', 'created_at')->latest()->take(5)->get();
        $clients = User::where('role_id', 4)->select('id', 'name', 'phone', 'email', 'blocked', 'created_at')->latest()->take(5)->get();

        return view('admin.dashboard.index', get_defined_vars());
    }
}
