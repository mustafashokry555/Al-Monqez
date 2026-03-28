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
        Schema::table('order_images', function (Blueprint $table) {
            $table->boolean('type')->default(0)->comment('0: problem, 1: before, 2:after')->after('order_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('order_images', function (Blueprint $table) {
            $table->dropColumn('type');
        });
    }
};
