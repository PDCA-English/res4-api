<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ReservationsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $param = [
            'user_id' => 2,
            'shop_id' => 9,
            'table_id' =>  8,
            'date_time' => '2021-07-30 20:00:00',
            'number_of_people'=> 5,
            'created_at' => '2021-07-29 20:00:00',
            'updated_at' => '2021-07-29 20:00:00',

        ];
        DB::table('reservations')->insert($param);

        $param = [
            'user_id' => 2,
            'shop_id' => 9,
            'table_id' =>  9,
            'date_time' => '2021-07-30 20:00:00',
            'number_of_people'=> 6,
            'created_at' => '2021-07-29 20:00:00',
            'updated_at' => '2021-07-29 20:00:00',
        ];
        DB::table('reservations')->insert($param);
    }
}
