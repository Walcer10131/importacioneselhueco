<?php
include("../includes/db.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];
    $precio = $_POST['precio'];
    $stock = $_POST['stock'];

    $sql = "INSERT INTO productos (nombre, descripcion, precio, stock)
            VALUES ('$nombre', '$descripcion', '$precio', '$stock')";

    if ($conn->query($sql)) {
        header("Location: listar.php");
        exit();
    } else {
        echo "Error al agregar el producto.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Agregar Producto</title>
    <link rel="stylesheet" href="../css/productos.css">
</head>
<body>
    <h2>Agregar Producto</h2>
    <form method="post">
        <label>Nombre:</label><br>
        <input type="text" name="nombre" required><br><br>

        <label>Descripción:</label><br>
        <textarea name="descripcion" required></textarea><br><br>

        <label>Precio:</label><br>
        <input type="number" step="0.01" name="precio" required><br><br>

        <label>Stock:</label><br>
        <input type="number" name="stock" required><br><br>

        <input type="submit" value="Guardar" class="btn">
        <a href="listar.php" class="btn-regresar">← Regresar</a>
    </form>
</body>
</html>
