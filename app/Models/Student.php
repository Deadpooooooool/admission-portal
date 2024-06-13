<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'gender',
        'age',
        'address',
        'tc_file',
        'mark_sheet_file',
        'gps_coordinates',
        'admitted',
        'free_bus_fare',
    ];
}
