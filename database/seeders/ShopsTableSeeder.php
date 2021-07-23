<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ShopsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $param = [
            'name' => '',
            'email' => '',
            'password' => '',
            'type' => 2,
            'region' => '',
            'genre' => '',
            'info' => '',
            'img_url' => '',
            'open' => '10:00',
            'close' => '23:00',
            'period' => 30,
            'created_at' => '2021-07-17 15:37:31',
            'updated_at' => '2021-07-17 15:37:31',
        ];
        DB::table('users')->insert($param);
    }
}
