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
            'tc_file_path' => 'path/to/tc/file',
            'marksheet_file_path' => 'path/to/marksheet/file',
            'latitude' => '10.0000',
            'longitude' => '76.0000',
            'admitted' => false,
        ]);
    }
}
