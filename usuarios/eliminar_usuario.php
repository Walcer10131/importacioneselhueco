<?php
include("../includes/db.php");
session_start();

if ($_SESSION['rol'] !== 'admin') {
    header("Location: ../dashboard.php");
    exit();
}

$id = $_GET['id'] ?? null;

if ($id) {
    $conn->query("DELETE FROM usuarios WHERE id = $id");
}

header("Location: listar_usuarios.php");
exit();
?>
