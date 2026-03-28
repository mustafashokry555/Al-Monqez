<?php

namespace App\Http\Controllers\Admin\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Helpers\FileStorage;
use App\Http\Requests\Admin\Dashboard\Clients\AddClientRequest;
use App\Http\Requests\Admin\Dashboard\Clients\UpdateClientRequest;
use App\Http\Requests\Admin\Dashboard\Clients\ValidateClientRequest;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Support\Facades\Hash;

class ClientController extends Controller
{
    use FileStorage;

    public function index()
    {
        $clients = User::select(
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
            ->where('role_id', '4')
            ->orderBy('created_at', 'DESC')
            ->paginate(10);

        return view('admin.dashboard.clients.index', compact('clients'));
    }

    /*-----------------------------------------------------------------------------------------------*/

    public function create()
    {
        return view('admin.dashboard.clients.create');
    }

    public function store(AddClientRequest $request)
    {
        $client = User::create([
            'role_id' => '4',
            'name' => $request->name,
            'phone' => $request->phone,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'image' => $this->uploadFile($request, 'clients'),
            'accepted' => 1
        ]);

        Wallet::create([
            'user_id' => $client->id
        ]);

        session()->flash('success', __('messages.create_client'));
        return redirect()->back();
    }

    /*-----------------------------------------------------------------------------------------------*/

    public function edit($id)
    {
        $client = User::where('role_id', '4')->findOrFail($id);

        return view('admin.dashboard.clients.edit', compact('client'));
    }

    public function update(UpdateClientRequest $request)
    {
        $client = User::findOrFail($request->client_id);

        $client->update([
            'name' => $request->name,
            'phone' => $request->phone,
            'email' => $request->email ?? $client->email,
            'password' => ($request->password) ? Hash::make($request->password) : $client->password,
            'image' => $this->uploadFile($request, 'clients', $client)
        ]);

        session()->flash('success', __('messages.edit_client'));
        return redirect()->back();
    }

    /*-----------------------------------------------------------------------------------------------*/

    public function verify(ValidateClientRequest $request)
    {
        $client = User::findOrFail($request->client_id);

        $client->update([
            'blocked' => ($client->blocked == '1') ? 0 : 1
        ]);

        session()->flash('success', ($client->blocked == '1') ? __('messages.deactivate_client') : __('messages.activate_client'));
        return redirect()->back();
    }

    /*-----------------------------------------------------------------------------------------------*/

    public function destroy(ValidateClientRequest $request)
    {
        $client = User::findOrFail($request->client_id);

        $client->delete();
        $client->update([
            'phone' => $client->phone . '_deleted_' . $client->id,
            'email' => $client->email ? $client->email . '_deleted_' . $client->id : null,
            'device_token' => null
        ]);

        session()->flash('success', __('messages.delete_client'));
        return redirect()->back();
    }
}
