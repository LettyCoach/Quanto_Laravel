<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_id')->nullable();
            $table->integer('customer_id')->nullable();
            $table->string('name');
            $table->string('email');
            $table->string('postcode')->nullable();
            $table->integer('address_id')->nullable();
            $table->string('address')->nullable();
            $table->string('address2')->nullable();
            $table->string('address3')->nullable();
            $table->string('phone')->nullable();
            $table->integer('shipping_address_id')->nullable();
            $table->string('shipping_address')->nullable();
            $table->string('shipping_address2')->nullable();
            $table->string('shipping_address3')->nullable();
            $table->string('shipping_name')->nullable();
            $table->string('shipping_kana')->nullable();
            $table->string('shipping_phone')->nullable();
            $table->integer('user_id')->nullable();
            $table->integer('survey_id')->nullable();
            $table->string('survey_title')->nullable();
            $table->integer('units')->nullable();
            $table->integer('amount')->nullable();
            $table->smallInteger('accept_method')->nullable();
            $table->string('accept_place')->nullable();
            $table->dateTime('accept_time')->nullable();
            $table->smallInteger('accept_status')->nullable();
            $table->smallInteger('payment_method')->nullable();
            $table->smallInteger('payment_status')->nullable();
            $table->dateTime('paid_at')->nullable();
            $table->string('payment_id')->nullable();
            $table->string('card_id')->nullable();
            $table->smallInteger('order_status')->nullable();
            $table->longText('comment')->nullable();
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
        Schema::dropIfExists('orders');
    }
}
