<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WorkTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('work_types')->insert([
            'name' => 'W biurze',
        ]);

        DB::table('work_types')->insert([
            'name' => 'Zdalnie',
        ]);

        DB::table('work_types')->insert([
            'name' => 'Urlop',
        ]);

        DB::table('work_types')->insert([
            'name' => 'Zwolnienie lekarskie',
        ]);
    }
}
