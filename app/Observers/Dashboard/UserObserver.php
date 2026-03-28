<?php

namespace App\Observers\Dashboard;

use App\Http\Helpers\FileStorage;
use App\Models\Chat;
use App\Models\User;
use App\Models\UserFile;
use App\Models\UserVehicleRegistration;

class UserObserver
{
    use FileStorage;

    /**
     * Handle the User "created" event.
     */
    public function created(User $user): void
    {
        if (in_array($user->role_id, ['3', '4'])) {
            Chat::create([
                'user_id' => $user->id,
            ]);
        }
    }

    /**
     * Handle the User "deleting" event.
     */

    public function deleting(User $user): void
    {
        if ($user->role_id == '7') {
            User::where('company_id', $user->id)->update(['company_id' => null]);
        }
    }

    /**
     * Handle the User "force deleting" event.
     */
    public function forceDeleting(User $user): void
    {
        if (in_array($user->role_id, ['3', '5'])) {
            $userVehicleRegistration = UserVehicleRegistration::select('vehicle_license_image', 'driving_license_image')->where('user_id', $user->id)->first();

            $this->deleteFile($userVehicleRegistration->vehicle_license_image ?? null);
            $this->deleteFile($userVehicleRegistration->driving_license_image ?? null);

            $files = UserFile::where('user_id', $user->id)->get();
            foreach ($files as $file) {
                $this->deleteFile($file->file);
            }
        }
    }
}
