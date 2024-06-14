<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('students')->insert([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'gender' => 'Male',
            'age' => 20,
            'address' => '123 Main St, Anytown, USA',
            'tc_file' => 'path/to/tc/file',
            'marksheet_file' => 'path/to/marksheet/file',
            'gps_coordinates' => '10.0143499, 76.3921148',
            'free_bus_fare' => false,
            'admitted' => false,
        ]);
    }
}
