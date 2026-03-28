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
        Schema::table('abouts', function (Blueprint $table) {
            $table->renameColumn('title', 'title_ar');
            $table->string('title_en')->after('title')->nullable();
            $table->renameColumn('description', 'description_ar');
            $table->text('description_en')->after('description')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('abouts', function (Blueprint $table) {
            $table->renameColumn('title_ar', 'title');
            $table->dropColumn('title_en');
            $table->renameColumn('description_ar', 'description');
            $table->dropColumn('description_en');
        });
    }
};
