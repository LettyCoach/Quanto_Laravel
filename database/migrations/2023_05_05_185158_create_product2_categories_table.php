<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProduct2CategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product2_categories', function (Blueprint $table) {
            // $table->id();
            $table->foreignId('product_id')
                ->constrained('user_products')
                ->onUpdate('cascade');
            $table->foreignId('category_id')
                ->constrained('user_product_categories')
                ->onUpdate('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product2_categories');
    }
}
