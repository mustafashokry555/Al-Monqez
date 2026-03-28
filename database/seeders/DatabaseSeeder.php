<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Category;
use App\Models\Service;
use App\Models\SubCategory;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            PermissionSeeder::class,
        ]);

        User::create([
            'role_id' => 1,
            'name' => 'Super Admin',
            'phone' => '00000000',
            'email' => 'super.admin@gmail.com',
            'password' => Hash::make('12345678'),
        ]);

        Category::factory()
            ->count(3)
            ->create()
            ->each(
                function ($category) {
                    SubCategory::factory()
                        ->count(rand(2,4))
                        ->create(['category_id' => $category->id])
                        ->each(
                            function ($sub_category) {
                                Service::factory()
                                    ->count(rand(1, 3))
                                    ->create([
                                        'sub_category_id' => $sub_category->id,
                                    ]);
                            }
                        );
                }
            );
    }
}
