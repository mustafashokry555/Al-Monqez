<?php

namespace App\Http\Controllers\Admin\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Dashboard\Admins\AddAdminRequest;
use App\Http\Requests\Admin\Dashboard\Admins\UpdateAdminRequest;
use App\Http\Requests\Admin\Dashboard\Admins\ValidateAdminRequest;
use App\Models\Permission;
use App\Models\User;
use App\Models\UserPermission;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function index()
    {
        $admins = User::where('role_id', 2)->select('id', 'name', 'phone', 'blocked', 'created_at')->latest()->paginate(10);

        return view('admin.dashboard.admins.index', compact('admins'));
    }

    /*-----------------------------------------------------------------------------------------------*/

    public function create()
    {
        $permissions = Permission::select('id', 'name')->get();

        return view('admin.dashboard.admins.create', compact('permissions'));
    }

    public function store(AddAdminRequest $request)
    {
        $admin = User::create([
            'role_id' => 2,
            'name' => $request->name,
            'phone' => $request->phone,
            'password' => Hash::make($request->password)
        ]);

        $data = [];
        $date = date('Y-m-d H:i:s');
        foreach ($request->permissions as $permission) {
            $data[] = [
                'user_id' => $admin->id,
                'permission_id' =>  $permission,
                'created_at' => $date,
                'updated_at' => $date
            ];
        }

        UserPermission::insert($data);

        session()->flash('success', __('messages.add_admin'));
        return redirect(route('admin.admins.create'));
    }

    /*-----------------------------------------------------------------------------------------------*/

    public function edit($id)
    {
        $admin = User::findOrFail($id);
        $permissions = Permission::select('id', 'name')->get();
        $admin->permissions = UserPermission::where('user_id', $id)->pluck('permission_id')->all();

        return view('admin.dashboard.admins.edit', compact('admin', 'permissions'));
    }

    public function update(UpdateAdminRequest $request)
    {
        $admin = User::findOrFail($request->admin_id);

        $password = $admin->password;
        if ($request->password) {
            $password = Hash::make($request->password);
        }

        $admin->update([
            'name' => $request->name,
            'phone' => $request->phone,
            'password' => $password
        ]);

        UserPermission::where([['user_id', $admin->id]])->delete();

        $data = [];
        $date = date('Y-m-d H:i:s');
        foreach ($request->permissions as $permission) {
            $data[] = [
                'user_id' => $admin->id,
                'permission_id' =>  $permission,
                'created_at' => $date,
                'updated_at' => $date
            ];
        }

        UserPermission::insert($data);

        Cache::forget("permissions_$admin->id");

        session()->flash('success', __('messages.edit_admin'));
        return redirect()->back();
    }

    /*-----------------------------------------------------------------------------------------------*/

    public function verify(ValidateAdminRequest $request)
    {
        $admin = User::findOrFail($request->admin_id);

        $admin->update([
            'blocked' => ($admin->blocked == '1') ? 0 : 1
        ]);

        session()->flash('success', ($admin->blocked == '1') ? __('messages.deactivate_admin') : __('messages.activate_admin'));
        return redirect()->back();
    }

    /*-----------------------------------------------------------------------------------------------*/

    public function destroy(ValidateAdminRequest $request)
    {
        User::findOrFail($request->admin_id)->forceDelete();

        session()->flash('success', __('messages.delete_admin'));
        return redirect()->back();
    }
}
