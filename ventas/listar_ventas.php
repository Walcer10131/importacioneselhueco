<?php
session_start();
include("../includes/db.php");

// Seguridad: si no ha iniciado sesión, redirigir
if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../index.php");
    exit();
}

$usuario_id = $_SESSION['usuario_id'];
$rol = $_SESSION['rol'];

// Si es admin, muestra todas. Si es vendedor, solo sus ventas
if ($rol === 'admin') {
    $sql = "SELECT v.id, u.nombre AS usuario, v.fecha, v.total 
            FROM ventas v 
            JOIN usuarios u ON v.usuario_id = u.id 
            ORDER BY v.fecha DESC";
} else {
    $sql = "SELECT v.id, u.nombre AS usuario, v.fecha, v.total 
            FROM ventas v 
            JOIN usuarios u ON v.usuario_id = u.id 
            WHERE v.usuario_id = $usuario_id
            ORDER BY v.fecha DESC";
}

$resultado = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Lista de Ventas</title>
    <link rel="stylesheet" href="../css/ventas.css">
</head>
<body>
    <h2>Ventas Registradas</h2>
    <a href="crear_venta.php" class="btn-nueva-venta">+ Nueva Venta</a>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Usuario</th>
                <th>Fecha</th>
                <th>Total</th>
                <th>Detalle</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $resultado->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['id'] ?></td>
                    <td><?= $row['usuario'] ?></td>
                    <td><?= $row['fecha'] ?></td>
                    <td>S/ <?= number_format($row['total'], 2) ?></td>
                    <td><a href="ver_detalle.php?id=<?= $row['id'] ?>" class="btn-detalle">Ver</a></td>
                </tr>
            <?php endwhile; ?>
             <a href="../dashboard.php" class="btn-regresar">←ATRAS</a>
        </tbody>
    </table>
</body>
</html>
