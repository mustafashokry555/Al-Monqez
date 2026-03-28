<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('user_vehicle_registrations', function (Blueprint $table) {
            $table->string('driving_license_image')->nullable()->after('vehicle_license_image');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_vehicle_registrations', function (Blueprint $table) {
            $table->dropColumn('driving_license_image');
        });
    }
};
