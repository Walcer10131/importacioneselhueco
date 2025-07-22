<?php
include("../includes/db.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['nombre'];
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $rol = $_POST['rol'];

    $sql = "INSERT INTO usuarios (nombre, username, password, rol)
            VALUES ('$nombre', '$username', '$password', '$rol')";

    if ($conn->query($sql)) {
        header("Location: listar_usuarios.php");
        exit();
    } else {
        echo "Error al agregar el usuario.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Agregar Usuario</title>
    <link rel="stylesheet" href="../css/usuarios.css">
</head>
<body>
    <h2>Agregar Usuario</h2>
    <form method="post">
        <label>Nombre:</label><br>
        <input type="text" name="nombre" required><br><br>

        <label>Usuario:</label><br>
        <input type="text" name="username" required><br><br>

        <label>Contraseña:</label><br>
        <input type="password" name="password" required><br><br>

        <label>Rol:</label><br>
        <select name="rol" required>
            <option value="admin">Admin</option>
            <option value="vendedor">Vendedor</option>
        </select><br><br>

        <input type="submit" value="Guardar">
        <a href="listar_usuarios.php" class="btn-regresar">← Cancelar</a>
    </form>
</body>
</html>
