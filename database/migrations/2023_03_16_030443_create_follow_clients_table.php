<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFollowClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('follow_clients', function (Blueprint $table) {
            $table->id();
            $table->foreignId('buyer_id')
                ->constrained('users')
                ->onUpdate('cascade');
            $table->foreignId('supplyer_id')
                ->constrained('users')
                ->onUpdate('cascade');
            $table->integer('type')->default(0); // type = 0 follow, type = 1 favorite
            $table->integer('agreeState')->default(0); // 0 : Not Agree, 1 : agree
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
        Schema::dropIfExists('follow_clients');
    }
}