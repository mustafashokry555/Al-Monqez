<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $date = date("Y-m-d H:i:s");
        $roles = ['super_admin', 'employee', 'worker', 'client', 'delivery driver', 'store', 'company'];

        $data = [];
        foreach($roles as $role) {
            array_push($data, [
                "name" => $role,
                "created_at" => $date,
                "updated_at" => $date,
            ]);
        }

        Role::insert($data);
    }
}
