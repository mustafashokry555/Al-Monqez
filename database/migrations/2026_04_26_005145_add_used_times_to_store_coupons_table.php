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
        Schema::table('store_coupons', function (Blueprint $table) {
            $table->unsignedInteger('used_times')->default(0)->after('valid_until');
            $table->unsignedInteger('max_used_times')->nullable()->after('used_times');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('store_coupons', function (Blueprint $table) {
            $table->dropColumn(['used_times', 'max_used_times']);
        });
    }
};
