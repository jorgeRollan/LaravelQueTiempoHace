<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listar Artículos</title>
</head>
<body>
    <form action="{{ url('/formulario1') }}" method="GET">
    	@csrf
    	<button type="submit">Añadir articulo</button>
    </form>
    @if($articulos->isEmpty())
        <p>No hay articulos</p>
    @else
        @foreach($articulos as $articulo)
            <p>Articulo<p>
            <p>Id {{ $articulo->art_id }}</p>
            <p>Nombre {{ $articulo->art_nombre }}</p>
            <p>Categoria {{ $articulo->art_categoria }}</p>
            <p>Cantidad {{ $articulo->art_cantidad }}</p>
            <form action="{{ route('eliminarArticulo', ['id' => $articulo->art_id]) }}" method="POST">
            	@csrf
                @method('DELETE')
                <button type="submit">Eliminar</button>
            </form>
            <form method="GET" action="{{ route('cambiarStock', ['id' => $articulo->art_id]) }}">
 	        @csrf
    	        <button type="submit">Cambiar Stock</button>
	    </form>

            <br><br>
        @endforeach
    @endif
</body>
</html>
