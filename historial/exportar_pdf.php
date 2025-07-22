<?php
require_once('../librerias/tcpdf/tcpdf.php');
include("../includes/db.php");

session_start();
if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../index.php");
    exit();
}

// Filtros si vienen por GET
$where = "";
$params = [];

if (!empty($_GET['usuario_id'])) {
    $where .= " AND v.usuario_id = ?";
    $params[] = $_GET['usuario_id'];
}

if (!empty($_GET['desde']) && !empty($_GET['hasta'])) {
    $where .= " AND DATE(v.fecha) BETWEEN ? AND ?";
    $params[] = $_GET['desde'];
    $params[] = $_GET['hasta'];
}

$sql = "
    SELECT v.id, v.fecha, v.total, u.nombre AS vendedor
    FROM ventas v
    INNER JOIN usuarios u ON v.usuario_id = u.id
    WHERE 1=1 $where
    ORDER BY v.fecha DESC
";

$stmt = $conn->prepare($sql);

if (!empty($params)) {
    $types = str_repeat("s", count($params));
    $stmt->bind_param($types, ...$params);
}

$stmt->execute();
$result = $stmt->get_result();

// Crear PDF
$pdf = new TCPDF();
$pdf->AddPage();
$pdf->SetFont('helvetica', '', 12);
$pdf->Cell(0, 10, 'Historial de Ventas', 0, 1, 'C');
$pdf->Ln(5);

// Encabezados
$html = '
<table border="1" cellpadding="5">
    <thead>
        <tr style="background-color:#f2f2f2;">
            <th><b>ID</b></th>
            <th><b>Fecha</b></th>
            <th><b>Vendedor</b></th>
            <th><b>Total (S/)</b></th>
        </tr>
    </thead>
    <tbody>
';

while ($row = $result->fetch_assoc()) {
    $html .= "<tr>
                <td>{$row['id']}</td>
                <td>{$row['fecha']}</td>
                <td>" . htmlspecialchars($row['vendedor']) . "</td>
                <td>" . number_format($row['total'], 2) . "</td>
              </tr>";
}

$html .= '</tbody></table>';
$pdf->writeHTML($html, true, false, true, false, '');
$pdf->Output('historial_ventas.pdf', 'I');
?>
