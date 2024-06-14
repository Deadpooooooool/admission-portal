<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStudentsTable extends Migration
{
    public function up()
    {
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('gender');
            $table->integer('age');
            $table->text('address');
            $table->string('tc_file')->nullable();  // Correct field name with nullable
            $table->string('marksheet_file')->nullable();  // Correct field name with nullable
            $table->string('gps_coordinates')->nullable();
            $table->boolean('admitted')->default(false);
            $table->boolean('free_bus_fare')->default(false);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('students');
    }
}
