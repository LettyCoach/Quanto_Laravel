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
            $table->string('brandName', 256);
            $table->string('name', 256);
            $table->string('sku', 64);
            $table->integer('price')->default(0);
            $table->string('img_urls', 4096);
            $table->text('detail');
            $table->string('category_ids', 2048)->nullable();
            $table->foreignId('color_id')
                    ->constrained('user_product_colors')
                    ->onUpdate('cascade');
            $table->foreignId('size_id')
                ->constrained('user_product_sizes')
                ->onUpdate('cascade');
            $table->text('materials');
            $table->text('memo')->nullable();
            $table->integer('stock')->default(0);
            $table->string('stockLimit', 16)->default("");
            $table->string('barcode', 64)->nullable()->default('');
            $table->string('isDisplay', 16)->default("checked");
            $table->integer('user_id');
            $table->text('other')->nullable();
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
