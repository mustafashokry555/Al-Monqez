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
        Schema::create('store_products', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('store_id');
            $table->foreign('store_id')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedBigInteger('classification_id');
            $table->foreign('classification_id')->references('id')->on('store_classifications')->onDelete('cascade');
            $table->string('name_ar');
            $table->string('name_en');
            $table->string('name_ur');
            $table->text('description_ar');
            $table->text('description_en');
            $table->text('description_ur');
            $table->string('image');
            $table->decimal('price', 8, 2);
            $table->decimal('sale_price', 8, 2)->nullable();
            $table->integer('quantity');
            $table->boolean('displayed');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('store_products');
    }
};
