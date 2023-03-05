<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AnswerOptionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('answer_options')->delete();
        DB::table('answer_options')->insert([
            'id' => 1,
            'name' => '画像',
        ]);
        DB::table('answer_options')->insert([
            'id' => 2,
            'name' => '動画',
        ]);
    }
}
