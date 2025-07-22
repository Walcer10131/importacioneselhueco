<?php
session_start();
include("includes/db.php");

if (!isset($_SESSION['usuario_id'])) {
    header("Location: index.php");
    exit();
}

// Obtener usuarios para el filtro
$usuarios = $conn->query("SELECT id, nombre FROM usuarios");

// Filtros
$condiciones = [];
$parametros = [];

if (!empty($_GET['usuario_id'])) {
    $condiciones[] = "v.usuario_id = ?";
    $parametros[] = $_GET['usuario_id'];
}

if (!empty($_GET['desde']) && !empty($_GET['hasta'])) {
    $condiciones[] = "DATE(v.fecha) BETWEEN ? AND ?";
    $parametros[] = $_GET['desde'];
    $parametros[] = $_GET['hasta'];
}

$where = "";
if (!empty($condiciones)) {
    $where = "WHERE " . implode(" AND ", $condiciones);
}

$sql = "
    SELECT v.id, v.fecha, v.total, u.nombre AS vendedor
    FROM ventas v
    INNER JOIN usuarios u ON v.usuario_id = u.id
    $where
    ORDER BY v.fecha DESC
";

$stmt = $conn->prepare($sql);
if ($parametros) {
    $tipos = str_repeat("s", count($parametros));
    $stmt->bind_param($tipos, ...$parametros);
}
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Historial de Ventas</title>
    <link rel="stylesheet" href="css/historial_ventas.css">
</head>
<body>
    <h2>Historial de Ventas</h2>

    <form method="get">
        <label for="usuario_id">Filtrar por Usuario:</label>
        <select name="usuario_id" id="usuario_id">
            <option value="">-- Todos --</option>
            <?php while ($u = $usuarios->fetch_assoc()): ?>
                <option value="<?= $u['id'] ?>" <?= isset($_GET['usuario_id']) && $_GET['usuario_id'] == $u['id'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($u['nombre']) ?>
                </option>
            <?php endwhile; ?>
        </select>

        <label for="desde">Desde:</label>
        <input type="date" name="desde" id="desde" value="<?= htmlspecialchars($_GET['desde'] ?? '') ?>">

        <label for="hasta">Hasta:</label>
        <input type="date" name="hasta" id="hasta" value="<?= htmlspecialchars($_GET['hasta'] ?? '') ?>">

        <button type="submit">Filtrar</button>
    </form>

    <br>
<form action="historial/exportar_pdf.php" method="get" target="_blank">
    <input type="hidden" name="usuario_id" value="<?= isset($_GET['usuario_id']) ? $_GET['usuario_id'] : '' ?>">
    <input type="hidden" name="desde" value="<?= isset($_GET['desde']) ? $_GET['desde'] : '' ?>">
    <input type="hidden" name="hasta" value="<?= isset($_GET['hasta']) ? $_GET['hasta'] : '' ?>">
    <button type="submit" class="btn">Exportar PDF</button>
</form>

    <table>
        <thead>
            <tr>
                <th>ID Venta</th>
                <th>Fecha</th>
                <th>Vendedor</th>
                <th>Total</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result->num_rows > 0): ?>
                <?php while ($venta = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= $venta['id'] ?></td>
                        <td><?= $venta['fecha'] ?></td>
                        <td><?= htmlspecialchars($venta['vendedor']) ?></td>
                        <td>S/ <?= number_format($venta['total'], 2) ?></td>
                        <td><a href="ventas/ver_detalle_his.php?id=<?= $venta['id'] ?>" class="btn">Ver Detalle</a></td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr><td colspan="5">No hay ventas encontradas.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
    
    <a href="dashboard.php" class="btn btn-regresar">Volver al Dashboard</a>
 
</body>
</html>
