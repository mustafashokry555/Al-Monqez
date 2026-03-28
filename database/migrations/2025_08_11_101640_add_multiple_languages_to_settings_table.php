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
        Schema::table('settings', function (Blueprint $table) {
            $table->renameColumn('name', 'name_ar');
            $table->string('name_en')->after('name')->nullable();
            $table->renameColumn('closed_message', 'closed_message_ar');
            $table->string('closed_message_en')->after('closed_message')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->renameColumn('name_ar', 'name');
            $table->dropColumn('name_en');
            $table->renameColumn('closed_message_ar', 'closed_message');
            $table->dropColumn('closed_message_en');
        });
    }
};
