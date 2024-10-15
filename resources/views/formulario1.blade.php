<html>
<head>
    <title>Insertar un nuevo articulo</title>
</head>
<body>
    <br><br>
    <form action="/formulario1" method="post">
        @csrf
        nombre<input type="text" name="nombre" id="nombre"><br>
        Categoria<input type="text" name="categoria" id="categoria"><br>
        Cantidad<input type="number" name="cantidad" id="cantidad"><br> 
        <input type="submit" value="Enviar" />
    </form>
</body>
</html>
<html>
<head>
