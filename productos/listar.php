<?php
include("../includes/db.php");

// Obtener los productos
$sql = "SELECT * FROM productos";
$resultado = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <h2 class="titulo">Lista de Productos</h2>
    <link rel="stylesheet" href="../css/productos.css">
</head>
<body>
     <div class="acciones-superiores">
        <a href="agregar.php" class="btn">Agregar nuevo producto</a>
        <a href="../dashboard.php" class="btn-regresar">← Regresar al Dashboard</a>
    </div>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Descripción</th>
                <th>Precio</th>
                <th>Stock</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($fila = $resultado->fetch_assoc()): ?>
                <tr>
                    <td><?= $fila['id'] ?></td>
                    <td><?= $fila['nombre'] ?></td>
                    <td><?= $fila['descripcion'] ?></td>
                    <td><?= number_format($fila['precio'], 2) ?></td>
                    <td><?= $fila['stock'] ?></td>
                    <td>
                        <a href="editar.php?id=<?= $fila['id'] ?>">Editar</a>
                        <a href="eliminar.php?id=<?= $fila['id'] ?>" onclick="return confirm('¿Eliminar este producto?')">Eliminar</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</body>
</html>

