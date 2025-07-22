<?php
include("../includes/db.php");
session_start();

// Redirige si no es admin
if ($_SESSION['rol'] !== 'admin') {
    header("Location: ../dashboard.php");
    exit();
}

$id = $_GET['id'] ?? null;

if (!$id) {
    echo "ID no proporcionado.";
    exit();
}

// Obtener datos del usuario
$sql = "SELECT * FROM usuarios WHERE id = $id";
$resultado = $conn->query($sql);
$usuario = $resultado->fetch_assoc();

if (!$usuario) {
    echo "Usuario no encontrado.";
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['nombre'];
    $username = $_POST['username'];
    $rol = $_POST['rol'];

    $sql = "UPDATE usuarios SET nombre = '$nombre', username = '$username', rol = '$rol' WHERE id = $id";

    if ($conn->query($sql)) {
        header("Location: listar_usuarios.php");
        exit();
    } else {
        echo "Error al actualizar el usuario.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Editar Usuario</title>
    <link rel="stylesheet" href="../css/usuarios.css">
</head>
<body>
    <h2>Editar Usuario</h2>
    <form method="post">
        <label>Nombre:</label><br>
        <input type="text" name="nombre" value="<?= htmlspecialchars($usuario['nombre']) ?>" required><br><br>

        <label>Usuario:</label><br>
        <input type="text" name="username" value="<?= htmlspecialchars($usuario['username']) ?>" required><br><br>

        <label>Rol:</label><br>
        <select name="rol" required>
            <option value="admin" <?= $usuario['rol'] == 'admin' ? 'selected' : '' ?>>Admin</option>
            <option value="vendedor" <?= $usuario['rol'] == 'vendedor' ? 'selected' : '' ?>>Vendedor</option>
        </select><br><br>

        <input type="submit" value="Guardar Cambios">
        <a href="listar_usuarios.php" class="btn-regresar">← Cancelar</a>
    </form>
</body>
</html>
