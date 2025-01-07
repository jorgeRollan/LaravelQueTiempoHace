<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserCitiesHistory extends Model
{
    protected $table ='user_Cities_history';
    public $timestamps = true;
    use HasFactory;
    protected $fillable = ['user_id', 'city_id', 'date'];
}
