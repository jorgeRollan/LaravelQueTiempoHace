<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class Articulo extends Model{
	use HasFactory;
	// atributo porque toma id como nombre por defecto si no y sin fechas guardadas
	protected $primaryKey = 'art_id';
	public $timestamps = false;
}
