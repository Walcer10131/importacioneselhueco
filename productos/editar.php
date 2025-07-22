<?php
include("../includes/db.php");

if (!isset($_GET['id'])) {
    echo "ID no especificado.";
    exit();
}

$id = $_GET['id'];

// Obtener producto
$sql = "SELECT * FROM productos WHERE id = $id";
$resultado = $conn->query($sql);

if ($resultado->num_rows !== 1) {
    echo "Producto no encontrado.";
    exit();
}

$producto = $resultado->fetch_assoc();

// Si se envió el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];
    $precio = $_POST['precio'];
    $stock = $_POST['stock'];

    $sql = "UPDATE productos SET 
                nombre = '$nombre',
                descripcion = '$descripcion',
                precio = '$precio',
                stock = '$stock'
            WHERE id = $id";

    if ($conn->query($sql)) {
        header("Location: listar.php");
        exit();
    } else {
        echo "Error al actualizar el producto.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Editar Producto</title>
    <link rel="stylesheet" href="../css/productos.css">
</head>
<body>
    <h2>Editar Producto</h2>
    <form method="post">
        <label>Nombre:</label><br>
        <input type="text" name="nombre" value="<?= $producto['nombre'] ?>" required><br><br>

        <label>Descripción:</label><br>
        <textarea name="descripcion" required><?= $producto['descripcion'] ?></textarea><br><br>

        <label>Precio:</label><br>
        <input type="number" step="0.01" name="precio" value="<?= $producto['precio'] ?>" required><br><br>

        <label>Stock:</label><br>
        <input type="number" name="stock" value="<?= $producto['stock'] ?>" required><br><br>

        <input type="submit" value="Actualizar">
    </form>
</body>
</html>
