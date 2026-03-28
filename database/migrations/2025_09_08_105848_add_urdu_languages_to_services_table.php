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
        Schema::table('services', function (Blueprint $table) {
            $table->string('name_ur')->nullable()->after('name_en');
            $table->string('brief_ur')->nullable()->after('brief_en');
            $table->text('description_ur')->nullable()->after('description_en');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('services', function (Blueprint $table) {
            $table->dropColumn('name_ur');
            $table->dropColumn('brief_ur');
            $table->dropColumn('description_ur');
        });
    }
};
