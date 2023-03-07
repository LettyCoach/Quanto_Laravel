<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_products', function (Blueprint $table) {
            $table->id();
            $table->string('name', 256);
            $table->string('sku', 64);
            $table->string('barcode', 64)->unique();
            $table->integer('price');
            $table->string('img_url', 1024);
            $table->string('detail');
            $table->integer('category_id')->nullable();
            $table->integer('amount');
            $table->integer('amountFix');
            $table->tinyInteger('isDisplay')->default(1);
            $table->string('option');
            $table->string('other');
            $table->double('discount');
            $table->integer('user_id');
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
        Schema::dropIfExists('user_products');
    }
}
