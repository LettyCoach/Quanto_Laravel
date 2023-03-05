<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateSurveyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('surveys', function (Blueprint $table) {
            $table->string('gradient_color')->nullable();
            $table->string('progress_status')->nullable();
            $table->string('brand_description')->nullable();
            $table->string('brand_name')->nullable();
            $table->string('callout_color')->nullable();
            $table->string('token')->nullable();
            $table->string('settings', 2000)->nullable();
            $table->string('answer_char_color', 20)->nullable();
            $table->string('answer_selected_border_color', 20)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('surveys', function (Blueprint $table) {
            //
        });
    }
}
