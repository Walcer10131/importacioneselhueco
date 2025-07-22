<?php
session_start();
include("../includes/db.php");

if (!isset($_GET['id'])) {
    echo "ID de venta no proporcionado.";
    exit();
}

$venta_id = $_GET['id'];

// Obtener venta y nombre del usuario
$sql = "SELECT ventas.*, usuarios.nombre AS nombre_usuario 
        FROM ventas 
        INNER JOIN usuarios ON ventas.usuario_id = usuarios.id 
        WHERE ventas.id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $venta_id);
$stmt->execute();
$result = $stmt->get_result();
$venta = $result->fetch_assoc();

if (!$venta) {
    echo "Venta no encontrada.";
    exit();
}

// Obtener productos de la venta
$sql_detalle = "SELECT detalle_venta.*, productos.nombre, productos.precio 
                FROM detalle_venta 
                INNER JOIN productos ON detalle_venta.producto_id = productos.id 
                WHERE detalle_venta.venta_id = ?";
$stmt = $conn->prepare($sql_detalle);
$stmt->bind_param("i", $venta_id);
$stmt->execute();
$detalles = $stmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Detalle de Venta</title>
    <link rel="stylesheet" href="../css/ver_detalle.css">
</head>
<body>
    <div class="container">
        <h2>Detalle de Venta #<?= $venta['id'] ?></h2>
        <p><strong>Fecha:</strong> <?= $venta['fecha'] ?></p>
        <p><strong>Vendedor:</strong> <?= $venta['nombre_usuario'] ?></p>
        <p><strong>Total:</strong> S/ <?= number_format($venta['total'], 2) ?></p>

        <table>
            <thead>
                <tr>
                    <th>Producto</th>
                    <th>Precio</th>
                    <th>Cantidad</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $detalles->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['nombre'] ?></td>
                    <td>S/ <?= number_format($row['precio'], 2) ?></td>
                    <td><?= $row['cantidad'] ?></td>
                    <td>S/ <?= number_format($row['subtotal'], 2) ?></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <a href="listar_ventas.php" class="btn-regresar">← Volver a la lista</a>
    </div>
</body>
</html>
