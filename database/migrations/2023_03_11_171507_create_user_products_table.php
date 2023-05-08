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
            $table->integer('price2')->default(0);
            $table->integer('flagPrice2')->default("");
            $table->string('img_urls', 4096);
            $table->string('options', '4096');
            $table->text('detail');
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
