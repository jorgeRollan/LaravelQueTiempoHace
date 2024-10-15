<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});


//ruta del formulario de añadir se puede 
//llamar directamente o desde el boton de la pagina de listado
Route::get('/formulario', function () {
    return view('formulario');
});


//Ruta del boton añadir
use App\Models\Articulo;

Route::post('/formulario1', function (Illuminate\Http\Request $request) {
    //Tomo el parametro del request y creo un modelo articulo y lo guardo
    $nombre = $request->input('nombre');
    $categoria = $request->input('categoria');
    $cantidad =$request->input('cantidad');
    $articulo =  new Articulo;
    $articulo ->art_nombre=$nombre;
    $articulo ->art_categoria=$categoria;
    $articulo ->art_cantidad=$cantidad;
    $articulo ->save();
    return redirect()->route('listarArticulos');
});


//Rutas de los controladores lista y borrar
use App\Http\Controllers\ListarArticulosController;

Route::get('/articulos', [ListarArticulosController::class, 'listar'])->name('listarArticulos');

use App\Http\Controllers\BorrarArticuloController;

Route::delete('/articulos/{id}', [BorrarArticuloController::class, 'eliminar'])->name('eliminarArticulo');


//En cambiar stock llamo a la vista y le paso el id
Route::get('/cambiarStock/{id}', function ($id) {
    return view('cambiarStock', ['id' => $id]);
})->name('cambiarStock');


//Ruta controlador del Cambiar Stock
use App\Http\Controllers\CambiarStockArticuloController;

Route::post('/cambiarStockArticulo/{id}', [CambiarStockArticuloController::class, 'cambiar'])->name('cambiarStockArticulo');