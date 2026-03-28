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
        Schema::table('withdraws', function (Blueprint $table) {
            $table->string('account_holder_name')->after('user_id')->nullable();
            $table->string('account_number')->after('account_holder_name')->nullable();
            $table->string('iban_number')->after('account_number')->nullable();
            $table->string('bank_name')->after('iban_number')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('withdraws', function (Blueprint $table) {
            $table->dropColumn('account_holder_name');
            $table->dropColumn('account_number');
            $table->dropColumn('iban_number');
            $table->dropColumn('bank_name');
        });
    }
};
