<?php
include("../includes/db.php");

if (!isset($_GET['id'])) {
    echo "ID no especificado.";
    exit();
}

$id = $_GET['id'];

// Ejecutar eliminación
$sql = "DELETE FROM productos WHERE id = $id";

if ($conn->query($sql)) {
    header("Location: listar.php");
    exit();
} else {
    echo "Error al eliminar el producto.";
}
?>
