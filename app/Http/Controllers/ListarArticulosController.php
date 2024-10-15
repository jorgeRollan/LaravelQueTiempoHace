<?php


namespace App\Http\Controllers;


use Illuminate\Http\Request;


use App\Models\Articulo;


class ListarArticulosController extends Controller

{

    public function listar()

    {
        $articulos = Articulo::all();
        return view('listarArticulos', ['articulos' => $articulos]);
    }
}



?>
