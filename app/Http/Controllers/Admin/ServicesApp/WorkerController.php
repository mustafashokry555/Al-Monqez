<?php

namespace App\Http\Controllers\Admin\ServicesApp;

use App\Http\Controllers\Controller;
use App\Http\Helpers\FileStorage;
use App\Http\Requests\Admin\ServicesApp\Workers\AddWorkerRequest;
use App\Http\Requests\Admin\ServicesApp\Workers\DeleteVehicleEquipmentImageRequest;
use App\Http\Requests\Admin\ServicesApp\Workers\UpdateWorkerRequest;
use App\Http\Requests\Admin\ServicesApp\Workers\ValidateWorkerRequest;
use App\Models\Category;
use App\Models\City;
use App\Models\Evaluation;
use App\Models\User;
use App\Models\UserAccount;
use App\Models\UserDetail;
use App\Models\UserFile;
use App\Models\UserService;
use App\Models\UserSubCategory;
use App\Models\UserVehicleRegistration;
use App\Models\Wallet;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class WorkerController extends Controller
{
    use FileStorage;

    public function index()
    {
        $language = app()->getLocale();
        $workers = User::query()->select(
            'users.id',
            'companies.name AS company_name',
            'users.name',
            'users.email',
            'users.phone',
            'users.image',
            'users.rating',
            "cities.name_$language as city_name",
            "categories.name_$language as category_name",
            'user_vehicle_registrations.id_number',
            'user_vehicle_registrations.vehicle_license_image',
            'user_vehicle_registrations.driving_license_image',
            'user_accounts.bank_name',
            'user_accounts.iban_number',
            'wallets.balance',
            'users.blocked',
            'users.created_at'
        )
            ->withCount('evaluations')
            // ->with('files')
            ->with([
                'subCategories' => function ($query) use ($language) {
                    $query->select('sub_categories.id', 'user_sub_categories.user_id', "sub_categories.name_$language AS name")
                        ->join('sub_categories', 'user_sub_categories.sub_category_id', '=', 'sub_categories.id');
                }
            ])
            ->leftJoin('users AS companies', 'users.company_id', '=', 'companies.id')
            ->join('user_details', 'users.id', '=', 'user_details.user_id')
            ->join('cities', 'user_details.city_id', '=', 'cities.id')
            ->join('categories', 'user_details.category_id', '=', 'categories.id')
            ->leftJoin('user_vehicle_registrations', function ($join) {
                $join->on('users.id', '=', 'user_vehicle_registrations.user_id');
            })
            ->leftJoin('user_accounts', 'users.id', '=', 'user_accounts.user_id')
            ->join('wallets', 'users.id', '=', 'wallets.user_id');

        if (auth()->user()->role_id == '7') {
            $workers->where('users.company_id', auth()->id());
        }

        $workers = $workers->where('users.role_id', '3')
            ->where('users.accepted', '1')
            ->orderBy('users.created_at', 'DESC')
            ->paginate(10);

        return view('admin.services-app.workers.index', compact('workers'));
    }

    /*-----------------------------------------------------------------------------------------------*/

    public function evaluations($id)
    {
        $evaluations = Evaluation::query()->select(
            'evaluations.id',
            'evaluations.order_id',
            'clients.name AS client_name',
            'workers.name AS worker_name',
            'evaluations.message',
            'evaluations.rating',
            'evaluations.created_at'
        )
            ->join('users AS clients', 'evaluations.client_id', '=', 'clients.id')
            ->join('users AS workers', 'evaluations.worker_id', '=', 'workers.id');

        if (auth()->user()->role_id == '7') {
            $evaluations->where('workers.company_id', auth()->id());
        }

        $evaluations = $evaluations->where('evaluations.worker_id', $id)
            ->latest()
            ->paginate(10);

        return view('admin.services-app.workers.evaluations', compact('evaluations'));
    }

    /*-----------------------------------------------------------------------------------------------*/

    public function joiningRequests()
    {
        $language = app()->getLocale();
        $workers = User::select(
            'users.id',
            'users.name',
            'users.email',
            'users.phone',
            'users.image',
            "cities.name_$language as city_name",
            "categories.name_$language as category_name",
            'user_vehicle_registrations.id_number',
            'user_vehicle_registrations.vehicle_license_image',
            'user_vehicle_registrations.driving_license_image',
            'user_accounts.bank_name',
            'user_accounts.iban_number',
            'users.accepted',
            'users.created_at'
        )
            // ->with('files')
            ->with([
                'subCategories' => function ($query) use ($language) {
                    $query->select('sub_categories.id', 'user_sub_categories.user_id', "sub_categories.name_$language AS name")
                        ->join('sub_categories', 'user_sub_categories.sub_category_id', '=', 'sub_categories.id');
                }
            ])
            ->join('user_details', 'users.id', '=', 'user_details.user_id')
            ->join('cities', 'user_details.city_id', '=', 'cities.id')
            ->join('categories', 'user_details.category_id', '=', 'categories.id')
            ->leftJoin('user_vehicle_registrations', function ($join) {
                $join->on('users.id', '=', 'user_vehicle_registrations.user_id');
            })
            ->leftJoin('user_accounts', 'users.id', '=', 'user_accounts.user_id')
            ->where('users.role_id', '3')
            ->where('users.accepted', '0')
            ->orderBy('users.created_at', 'DESC')
            ->paginate(10);

        return view('admin.services-app.workers.joining_requests', compact('workers'));
    }

    /*-----------------------------------------------------------------------------------------------*/

    public function acceptJoiningRequest(ValidateWorkerRequest $request)
    {
        $worker = User::findOrFail($request->worker_id);

        $worker->update([
            'accepted' => 1
        ]);

        session()->flash('success', __('messages.accept_worker'));
        return redirect()->back();
    }

    /*-----------------------------------------------------------------------------------------------*/

    public function create()
    {
        $language = app()->getLocale();
        $companies = User::query()->select('id', 'name')->where('role_id', '7');

        if (auth()->user()->role_id == '7') {
            $companies->where('id', auth()->id());
        }

        $companies = $companies->get();
        $cities = City::select('id', "name_$language AS name")->where('displayed', 1)->get();
        $categories = Category::select('id', "name_$language AS name")->where('displayed', 1)->get();

        return view('admin.services-app.workers.create', compact('companies', 'cities', 'categories'));
    }

    public function store(AddWorkerRequest $request)
    {
        DB::beginTransaction();
        try {
            $worker = User::create([
                'role_id' => '3',
                'company_id' => $request->company_id,
                'name' => $request->name,
                'phone' => $request->phone,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'image' => $this->uploadFile($request, 'workers', null, 'image'),
                'accepted' => 1
            ]);

            UserDetail::create([
                'user_id' => $worker->id,
                'city_id' => $request->city_id,
                'category_id' => $request->category_id,
                'description' => $request->description
            ]);

            $data = [];
            foreach ($request->sub_category_ids as $subCategoryId) {
                $data[] = [
                    'user_id' => $worker->id,
                    'sub_category_id' => $subCategoryId,
                    'created_at' => now(),
                    'updated_at' => now()
                ];
            }
            UserSubCategory::insert($data);

            $data = [];
            foreach ($request->service_ids as $serviceId) {
                $data[] = [
                    'user_id' => $worker->id,
                    'service_id' => $serviceId,
                    'created_at' => now(),
                    'updated_at' => now()
                ];
            }
            UserService::insert($data);

            UserVehicleRegistration::create([
                'user_id' => $worker->id,
                'id_number' => $request->id_number,
                'vehicle_license_image' => $request->is_vehicle_sub_category ? $this->uploadFile($request, 'licenses', null, 'vehicle_license_image', 'vehicle_license_image') : null,
                'driving_license_image' => $request->is_vehicle_sub_category ? $this->uploadFile($request, 'licenses', null, 'driving_license_image', 'driving_license_image') : null
            ]);

            UserAccount::create([
                'user_id' => $worker->id,
                'bank_name' => $request->bank_name,
                'iban_number' => $request->iban_number
            ]);

            $paths = $this->uploadMultipleFiles($request, 'vehicle_equipments', 'vehicle_equipment_images');
            $data = [];
            foreach ($paths as $path) {
                $data[] = [
                    'user_id' => $worker->id,
                    'file' => $path,
                    'created_at' => now(),
                    'updated_at' => now()
                ];
            }
            UserFile::insert($data);

            Wallet::create([
                'user_id' => $worker->id
            ]);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', __('messages.something_went_wrong'));
            return redirect()->back();
        }

        session()->flash('success', __('messages.create_worker'));
        return redirect()->back();
    }

    /*-----------------------------------------------------------------------------------------------*/

    public function edit($id)
    {
        $language = app()->getLocale();
        $worker = User::query()->select(
            'users.*',
            'user_details.city_id',
            'user_details.category_id',
            'user_details.description',
            'user_vehicle_registrations.id_number',
            'user_vehicle_registrations.vehicle_license_image',
            'user_vehicle_registrations.driving_license_image',
            'user_accounts.bank_name',
            'user_accounts.iban_number'
        )
            ->with([
                'subCategories' => function ($query) {
                    $query->select('user_sub_categories.user_id', 'user_sub_categories.sub_category_id');
                },
                'services' => function ($query) use ($language) {
                    $query->select('user_services.user_id', 'user_services.service_id');
                },
                'files' => function ($query) {
                    $query->select('id', 'user_id', 'file');
                }
            ])
            ->join('user_details', 'users.id', '=', 'user_details.user_id')
            ->leftJoin('user_vehicle_registrations', function ($join) {
                $join->on('users.id', '=', 'user_vehicle_registrations.user_id');
            })
            ->leftJoin('user_accounts', 'users.id', '=', 'user_accounts.user_id')
            ->where('users.role_id', '3');

        if (auth()->user()->role_id == '7') {
            $worker->where('users.company_id', auth()->id());
        }

        $worker = $worker->findOrFail($id);

        $companies = User::query()->select('id', 'name')->where('role_id', '7');
        if (auth()->user()->role_id == 7) {
            $companies->where('id', auth()->id());
        }
        $companies = $companies->get();
        $cities = City::where('displayed', 1)->select('id', "name_$language AS name")->get();
        $categories = Category::select('id', "name_$language AS name")->where('displayed', 1)->get();

        return view('admin.services-app.workers.edit', compact('worker', 'companies', 'cities', 'categories'));
    }

    public function update(UpdateWorkerRequest $request)
    {
        DB::beginTransaction();
        try {
            $worker = User::findOrFail($request->worker_id);

            $worker->update([
                'company_id' => $request->company_id,
                'name' => $request->name,
                'phone' => $request->phone,
                'email' => $request->email ?? $worker->email,
                'password' => ($request->password) ? Hash::make($request->password) : $worker->password,
                'image' => $this->uploadFile($request, 'workers', $worker)
            ]);

            $workerDetails = UserDetail::where('user_id', $worker->id)->first();
            $workerDetails->update([
                'city_id' => $request->city_id,
                'category_id' => $request->category_id,
                'description' => $request->description
            ]);

            $data = [];
            foreach ($request->sub_category_ids as $subCategoryId) {
                $data[] = [
                    'user_id' => $worker->id,
                    'sub_category_id' => $subCategoryId,
                    'created_at' => now(),
                    'updated_at' => now()
                ];
            }
            UserSubCategory::where('user_id', $worker->id)->delete();
            UserSubCategory::insert($data);

            $data = [];
            foreach ($request->service_ids as $serviceId) {
                $data[] = [
                    'user_id' => $worker->id,
                    'service_id' => $serviceId,
                    'created_at' => now(),
                    'updated_at' => now()
                ];
            }
            UserService::where('user_id', $worker->id)->delete();
            UserService::insert($data);

            if ($request->is_vehicle_sub_category) {
                $userVehicleRegistration = UserVehicleRegistration::where('user_id', $worker->id)->first();
                UserVehicleRegistration::updateOrCreate([
                    'user_id' => $worker->id
                ], [
                    'id_number' => $request->id_number,
                    'vehicle_license_image' => $this->uploadFile($request, 'licenses', $userVehicleRegistration, 'vehicle_license_image', 'vehicle_license_image'),
                    'driving_license_image' => $this->uploadFile($request, 'licenses', $userVehicleRegistration, 'driving_license_image', 'driving_license_image')
                ]);
            } else {
                $userVehicleRegistration = UserVehicleRegistration::where('user_id', $worker->id)->first();
                if ($userVehicleRegistration) {
                    $this->deleteFile($userVehicleRegistration->vehicle_license_image);
                    $this->deleteFile($userVehicleRegistration->driving_license_image);
                }

                UserVehicleRegistration::updateOrCreate([
                    'user_id' => $worker->id
                ], [
                    'id_number' => $request->id_number,
                    'vehicle_license_image' => null,
                    'driving_license_image' => null
                ]);
            }

            UserAccount::updateOrCreate([
                'user_id' => $worker->id
            ], [
                'bank_name' => $request->bank_name,
                'iban_number' => $request->iban_number
            ]);

            if ($request->vehicle_equipment_images && count($request->vehicle_equipment_images) > 0) {
                $paths = $this->uploadMultipleFiles($request, 'vehicle_equipments', 'vehicle_equipment_images');
                $data = [];
                foreach ($paths as $path) {
                    $data[] = [
                        'user_id' => $worker->id,
                        'file' => $path,
                        'created_at' => now(),
                        'updated_at' => now()
                    ];
                }
                UserFile::insert($data);
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', __('messages.something_went_wrong'));
            return redirect()->back();
        }

        session()->flash('success', __('messages.edit_worker'));
        return redirect()->back();
    }

    /*-----------------------------------------------------------------------------------------------*/

    public function verify(ValidateWorkerRequest $request)
    {
        $worker = User::findOrFail($request->worker_id);

        $worker->update([
            'blocked' => ($worker->blocked == '1') ? 0 : 1
        ]);

        session()->flash('success', ($worker->blocked == '1') ? __('messages.deactivate_worker') : __('messages.activate_worker'));
        return redirect()->back();
    }

    /*-----------------------------------------------------------------------------------------------*/

    public function destroy(ValidateWorkerRequest $request)
    {
        $worker = User::findOrFail($request->worker_id);

        $worker->delete();
        $worker->update([
            'phone' => $worker->phone . '_deleted_' . $worker->id,
            'email' => $worker->email ? $worker->email . '_deleted_' . $worker->id : null,
            'device_token' => null
        ]);

        session()->flash('success', __('messages.delete_worker'));
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
