<?php


namespace App\Http\Controllers;


use Illuminate\Http\Request;


use App\Models\Articulo;


class BorrarArticuloController extends Controller{
    public function eliminar($id){
        Articulo::where('art_id', '=', $id)->delete();
        return redirect()->route('listarArticulos');
    }
}

?>
