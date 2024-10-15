<?php


namespace App\Http\Controllers;


use Illuminate\Http\Request;


use App\Models\Articulo;


class CambiarStockArticuloController extends Controller
{
    public function cambiar(Request $request, $id)
    {
	$cantidad = $request->input('cantidad');

	Articulo::where('art_id', '=', $id)->update(['art_cantidad' => $cantidad]);

        return redirect()->route('listarArticulos');
    }
}
?>
