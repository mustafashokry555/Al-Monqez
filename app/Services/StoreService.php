<?php

namespace App\Services;

use App\Http\Helpers\FileStorage;
use App\Models\Permission;
use App\Models\StoreDetail;
use App\Models\User;
use App\Models\UserPermission;
use App\Models\Wallet;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class StoreService
{
    use FileStorage;

    public function create($request)
    {
        DB::beginTransaction();

        try {
            $store = User::create([
                'role_id' => '6',
                'name' => $request->name,
                'phone' => $request->phone,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'image' => $this->uploadFile($request, 'stores'),
                'accepted' => 1
            ]);

            StoreDetail::create([
                'store_id' => $store->id,
                'city_id' => $request->city_id,
                'category_id' => $request->category_id,
                'address_ar' => $request->address_ar,
                'address_en' => $request->address_en,
                'address_ur' => $request->address_ur,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'cover_image' => $this->uploadFile($request, 'store_covers', null, 'cover_image', 'cover_image'),
               'commercial_registration' => $this->uploadFile($request, 'stores', null, 'commercial_registration', 'commercial_registration'),
                'license' => $this->uploadFile($request, 'stores', null, 'license', 'license'),
                'bank_name' => $request->bank_name,
                'account_holder_name' => $request->account_holder_name,
                'IBAN' => $request->IBAN
            ]);

            Wallet::create([
                'user_id' => $store->id
            ]);

            $permissions = Permission::select('id')->whereIn('name', [
                'classification_create',
                'classification_edit',
                'classification_delete',
                'product_create',
                'product_edit',
                'product_delete',
                'coupon_create',
                'coupon_edit',
                'coupon_delete',
                'store_order_control',
            ])->get();

            $data = [];
            foreach ($permissions as $permission) {
                array_push($data, [
                    'user_id' => $store->id,
                    'permission_id' => $permission->id,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }
            UserPermission::insert($data);

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            return false;
        }
    }
}
