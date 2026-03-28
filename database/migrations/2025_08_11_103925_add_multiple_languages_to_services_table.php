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
            $table->renameColumn('name', 'name_ar');
            $table->string('name_en')->after('name')->nullable();
            $table->renameColumn('brief', 'brief_ar');
            $table->string('brief_en')->after('brief')->nullable();
            $table->renameColumn('description', 'description_ar');
            $table->text('description_en')->after('description')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('services', function (Blueprint $table) {
            $table->renameColumn('name_ar', 'name');
            $table->dropColumn('name_en');
            $table->renameColumn('brief_ar', 'brief');
            $table->dropColumn('brief_en');
            $table->renameColumn('description_ar', 'description');
            $table->dropColumn('description_en');
        });
    }
};
