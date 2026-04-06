<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $date = date("Y-m-d H:i:s");
        $permissions = [
            'client_create', 'client_edit', 'client_delete',
            'company_create', 'company_edit', 'company_delete',
            'worker_create', 'worker_edit', 'worker_delete',
            'admin_create', 'admin_edit', 'admin_delete',
            'control_panel_control', 'users_activity_log_control', 'chat_control',
            'contact_control', 'notification_control', 'report_control',
            'partner_create', 'partner_edit', 'partner_delete',
            'order_control', 'withdraw_control', 'setting_change',
            'category_create', 'category_edit', 'category_delete',
            'sub_category_create', 'sub_category_edit', 'sub_category_delete',
            'service_create', 'service_edit', 'service_delete',
            'city_create', 'city_edit', 'city_delete',
            'slider_create', 'slider_edit', 'slider_delete',
            'social_create', 'social_edit', 'social_delete',
            'term_create', 'term_edit', 'term_delete',
            'about_create', 'about_edit', 'about_delete',
            'store_create', 'store_edit', 'store_delete',
            'delivery_driver_create', 'delivery_driver_edit', 'delivery_driver_delete',
            'classification_create', 'classification_edit', 'classification_delete',
            'product_create', 'product_edit', 'product_delete',
            'coupon_create', 'coupon_edit', 'coupon_delete',
            'store_order_control',
            'patches_control',
        ];

        $data = [];
        foreach($permissions as $permission) {
            array_push($data, [
                "name" => $permission,
                "created_at" => $date,
                "updated_at" => $date,
            ]);
        }

        Permission::insert($data);
    }
}
