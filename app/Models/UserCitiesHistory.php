<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserCitiesHistory extends Model
{
    protected $table ='user_cities_history';
    public $timestamps = true;
    use HasFactory;
}
