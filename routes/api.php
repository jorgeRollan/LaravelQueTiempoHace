<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Models\Usuario;
use App\Models\CiudadUsuario;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::get('/ciudades', function () {
    $ciudades = CiudadUsuario::where('idUsuario', '=', '1')->get();

    // Verifica si se obtienen resultados
    if ($ciudades->isEmpty()) {
        return response()->json(['message' => 'No se encontraron ciudades'], 404);
    }

    return response()->json($ciudades, 200);
});

