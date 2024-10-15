<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cambiar Stock</title>
</head>
<body>
	<form action="{{ route('cambiarStockArticulo', ['id' => $id]) }}" 				method="POST">
        	@csrf
        	<input name="cantidad" placeholder="cantidad"></input>
                <button type="submit" method="POST">Cambiar Stock</button>
        </form>
</body>
</html>
