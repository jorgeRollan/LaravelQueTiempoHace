<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CiudadUsuario extends Model
{
    protected $table ='ciudadesusuario';
    public $timestamps = false;
    use HasFactory;
}
