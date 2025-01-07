<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserCities extends Model
{
    protected $table ='user_Cities';
    public $timestamps = false;
    use HasFactory;
}
