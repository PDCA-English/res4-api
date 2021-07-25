<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TablesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $param = [
            'shop_id' => 9,
            'capacity' => 3,
            'created_at' => '2021-07-29 20:00:00',
            'updated_at' => '2021-07-29 20:00:00',
        ];
        DB::table('tables')->insert($param);

        $param = [
            'shop_id' => 9,
            'capacity' => 4,
            'created_at' => '2021-07-29 20:00:00',
            'updated_at' => '2021-07-29 20:00:00',
        ];
        DB::table('tables')->insert($param);

        $param = [
            'shop_id' => 9,
            'capacity' => 5,
            'created_at' => '2021-07-29 20:00:00',
            'updated_at' => '2021-07-29 20:00:00',
        ];
        DB::table('tables')->insert($param);

        $param = [
            'shop_id' => 9,
            'capacity' => 6,
            'created_at' => '2021-07-29 20:00:00',
            'updated_at' => '2021-07-29 20:00:00',
        ];
        DB::table('tables')->insert($param);

        $param = [
            'shop_id' => 9,
            'capacity' => 7,
            'created_at' => '2021-07-29 20:00:00',
            'updated_at' => '2021-07-29 20:00:00',
        ];
        DB::table('tables')->insert($param);
    }
}
