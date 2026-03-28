<?php

namespace App\Http\Controllers\Admin\ServicesApp;

use App\Http\Controllers\Controller;
use App\Http\Helpers\FileStorage;
use App\Http\Requests\Admin\ServicesApp\Companies\AddCompanyRequest;
use App\Http\Requests\Admin\ServicesApp\Companies\UpdateCompanyRequest;
use App\Http\Requests\Admin\ServicesApp\Companies\ValidateCompanyRequest;
use App\Models\Permission;
use App\Models\User;
use App\Models\UserPermission;
use App\Models\Wallet;
use Illuminate\Support\Facades\Hash;

class CompanyController extends Controller
{
    use FileStorage;

    public function index()
    {
        $companies = User::select(
            'users.id',
            'users.name',
            'users.email',
            'users.phone',
            'users.image',
            'wallets.balance',
            'users.blocked',
            'users.created_at'
        )
            ->join('wallets', 'users.id', '=', 'wallets.user_id')
            ->where('role_id', '7')
            ->orderBy('created_at', 'DESC')
            ->paginate(10);

        return view('admin.services-app.companies.index', compact('companies'));
    }

    /*-----------------------------------------------------------------------------------------------*/

    public function create()
    {
        return view('admin.services-app.companies.create');
    }

    public function store(AddCompanyRequest $request)
    {
        $company = User::create([
            'role_id' => '7',
            'name' => $request->name,
            'phone' => $request->phone,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'image' => $this->uploadFile($request, 'companies'),
            'accepted' => 1
        ]);

        Wallet::create([
            'user_id' => $company->id
        ]);

        $permissions = Permission::select('id')->whereIn('name', [
            'worker_create',
            'worker_edit',
            'worker_delete',
            'order_control',
        ])->get();

        $data = [];
        foreach ($permissions as $permission) {
            array_push($data, [
                'user_id' => $company->id,
                'permission_id' => $permission->id,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
        UserPermission::insert($data);

        session()->flash('success', __('messages.create_company'));
        return redirect()->back();
    }

    /*-----------------------------------------------------------------------------------------------*/

    public function edit($id)
    {
        $company = User::where('role_id', '7')->findOrFail($id);

        return view('admin.services-app.companies.edit', compact('company'));
    }

    public function update(UpdateCompanyRequest $request)
    {
        $company = User::findOrFail($request->company_id);

        $company->update([
            'name' => $request->name,
            'phone' => $request->phone,
            'email' => $request->email ?? $company->email,
            'password' => ($request->password) ? Hash::make($request->password) : $company->password,
            'image' => $this->uploadFile($request, 'companies', $company)
        ]);

        session()->flash('success', __('messages.edit_company'));
        return redirect()->back();
    }

    /*-----------------------------------------------------------------------------------------------*/

    public function verify(ValidateCompanyRequest $request)
    {
        $company = User::findOrFail($request->company_id);

        $company->update([
            'blocked' => ($company->blocked == '1') ? 0 : 1
        ]);

        session()->flash('success', ($company->blocked == '1') ? __('messages.deactivate_company') : __('messages.activate_company'));
        return redirect()->back();
    }

    /*-----------------------------------------------------------------------------------------------*/

    public function destroy(ValidateCompanyRequest $request)
    {
        $company = User::findOrFail($request->company_id);

        $company->delete();
        $company->update([
            'phone' => $company->phone . '_deleted_' . $company->id,
            'email' => $company->email ? $company->email . '_deleted_' . $company->id : null,
            'device_token' => null
        ]);

        session()->flash('success', __('messages.delete_company'));
        return redirect()->back();
    }
}
