<?php

namespace App\Http\Controllers\Admin\StoreApp;

use App\Http\Controllers\Controller;
use App\Http\Helpers\FileStorage;
use App\Http\Requests\Admin\StoreApp\Drivers\AddDriverRequest;
use App\Http\Requests\Admin\StoreApp\Drivers\DeleteVehicleEquipmentImageRequest;
use App\Http\Requests\Admin\StoreApp\Drivers\UpdateDriverRequest;
use App\Http\Requests\Admin\StoreApp\Drivers\ValidateDriverRequest;
use App\Models\User;
use App\Models\UserAccount;
use App\Models\UserFile;
use App\Models\UserVehicleRegistration;
use App\Models\Wallet;
use Illuminate\Support\Facades\Hash;

class DriverController extends Controller
{
    use FileStorage;

    public function index()
    {
        $drivers = User::select(
            'users.id',
            'users.name',
            'users.email',
            'users.phone',
            'users.image',
            'user_vehicle_registrations.id_number',
            'user_vehicle_registrations.vehicle_license_image',
            'user_vehicle_registrations.driving_license_image',
            'user_accounts.bank_name',
            'user_accounts.iban_number',
            'wallets.balance',
            'users.blocked',
            'users.created_at'
        )
            ->join('wallets', 'users.id', '=', 'wallets.user_id')
            ->leftJoin('user_vehicle_registrations', 'users.id', '=', 'user_vehicle_registrations.user_id')
            ->leftJoin('user_accounts', 'users.id', '=', 'user_accounts.user_id')
            ->where('role_id', '5')
            ->orderBy('created_at', 'DESC')
            ->paginate(10);

        return view('admin.store-app.drivers.index', compact('drivers'));
    }

    /*-----------------------------------------------------------------------------------------------*/

    public function create()
    {
        return view('admin.store-app.drivers.create');
    }

    public function store(AddDriverRequest $request)
    {
        $driver = User::create([
            'role_id' => '5',
            'name' => $request->name,
            'phone' => $request->phone,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'image' => $this->uploadFile($request, 'drivers'),
            'accepted' => 1
        ]);

        UserVehicleRegistration::create([
            'user_id' => $driver->id,
            'id_number' => $request->id_number,
            'vehicle_license_image' => $this->uploadFile($request, 'licenses', null, 'vehicle_license_image', 'vehicle_license_image'),
            'driving_license_image' => $this->uploadFile($request, 'licenses', null, 'driving_license_image', 'driving_license_image')
        ]);

        UserAccount::create([
            'user_id' => $driver->id,
            'bank_name' => $request->bank_name,
            'iban_number' => $request->iban_number
        ]);

        $paths = $this->uploadMultipleFiles($request, 'vehicle_equipments', 'vehicle_equipment_images');
        $data = [];
        foreach ($paths as $path) {
            $data[] = [
                'user_id' => $driver->id,
                'file' => $path,
                'created_at' => now(),
                'updated_at' => now()
            ];
        }
        UserFile::insert($data);

        Wallet::create([
            'user_id' => $driver->id
        ]);

        session()->flash('success', __('messages.create_driver'));
        return redirect()->back();
    }

    /*-----------------------------------------------------------------------------------------------*/

    public function edit($id)
    {
        $driver = User::select(
            'users.*',
            'user_vehicle_registrations.id_number',
            'user_vehicle_registrations.vehicle_license_image',
            'user_vehicle_registrations.driving_license_image',
            'user_accounts.bank_name',
            'user_accounts.iban_number'
        )
            ->with([
                'files' => function ($query) {
                    $query->select('id', 'user_id', 'file');
                }
            ])
            ->leftJoin('user_vehicle_registrations', 'users.id', '=', 'user_vehicle_registrations.user_id')
            ->leftJoin('user_accounts', 'users.id', '=', 'user_accounts.user_id')
            ->where('role_id', '5')
            ->findOrFail($id);

        return view('admin.store-app.drivers.edit', compact('driver'));
    }

    public function update(UpdateDriverRequest $request)
    {
        $driver = User::findOrFail($request->driver_id);

        $driver->update([
            'name' => $request->name,
            'phone' => $request->phone,
            'email' => $request->email ?? $driver->email,
            'password' => ($request->password) ? Hash::make($request->password) : $driver->password,
            'image' => $this->uploadFile($request, 'drivers', $driver)
        ]);

        $userVehicleRegistration = UserVehicleRegistration::where('user_id', $driver->id)->first();
        UserVehicleRegistration::updateOrCreate([
            'user_id' => $driver->id
        ], [
            'id_number' => $request->id_number,
            'vehicle_license_image' => $this->uploadFile($request, 'licenses', $userVehicleRegistration, 'vehicle_license_image', 'vehicle_license_image'),
            'driving_license_image' => $this->uploadFile($request, 'licenses', $userVehicleRegistration, 'driving_license_image', 'driving_license_image')
        ]);

        UserAccount::updateOrCreate([
            'user_id' => $driver->id
        ], [
            'bank_name' => $request->bank_name,
            'iban_number' => $request->iban_number
        ]);

        if ($request->vehicle_equipment_images && count($request->vehicle_equipment_images) > 0) {
            $paths = $this->uploadMultipleFiles($request, 'vehicle_equipments', 'vehicle_equipment_images');
            $data = [];
            foreach ($paths as $path) {
                $data[] = [
                    'user_id' => $driver->id,
                    'file' => $path,
                    'created_at' => now(),
                    'updated_at' => now()
                ];
            }
            UserFile::insert($data);
        }

        session()->flash('success', __('messages.edit_driver'));
        return redirect()->back();
    }

    /*-----------------------------------------------------------------------------------------------*/

    public function verify(ValidateDriverRequest $request)
    {
        $driver = User::findOrFail($request->driver_id);

        $driver->update([
            'blocked' => ($driver->blocked == '1') ? 0 : 1
        ]);

        session()->flash('success', ($driver->blocked == '1') ? __('messages.deactivate_driver') : __('messages.activate_driver'));
        return redirect()->back();
    }

    /*-----------------------------------------------------------------------------------------------*/

    public function destroy(ValidateDriverRequest $request)
    {
        $driver = User::findOrFail($request->driver_id);

        $driver->delete();
        $driver->update([
            'phone' => $driver->phone . '_deleted_' . $driver->id,
            'email' => $driver->email ? $driver->email . '_deleted_' . $driver->id : null,
            'device_token' => null
        ]);

        session()->flash('success', __('messages.delete_driver'));
        return redirect()->back();
    }

    /*-----------------------------------------------------------------------------------------------*/

    public function destroyImage(DeleteVehicleEquipmentImageRequest $request)
    {
        $image = UserFile::find($request->image_id);

        if (!$image) {
            return response()->json(['message' => __('messages.something_went_wrong')], 400);
        }

        $this->deleteFile($image->file);
        $image->delete();

        return response()->json(['message' => __('messages.delete_vehicle_equipment_image')]);
    }
}
