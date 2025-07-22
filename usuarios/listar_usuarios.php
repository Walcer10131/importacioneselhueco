<?php
include("../includes/db.php");

$sql = "SELECT * FROM usuarios";
$resultado = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
     <h2 class="titulo">Lista de Usuarios</h2>
    <link rel="stylesheet" href="../css/usuarios.css">
</head>
<body>
    <div class="acciones-superiores">
    <a href="agregar_usuario.php" class="btn">Agregar nuevo usuario</a>
    <a href="../dashboard.php" class="btn btn-regresar">← Regresar al Dashboard</a>
    </div>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Usuario</th>
                <th>Rol</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($fila = $resultado->fetch_assoc()): ?>
                <tr>
                    <td><?= $fila['id'] ?></td>
                    <td><?= $fila['nombre'] ?></td>
                    <td><?= $fila['username'] ?></td>
                    <td><?= $fila['rol'] ?></td>
                    <td>
                        <a href="editar_usuario.php?id=<?= $fila['id'] ?>">Editar</a>
                        <a href="eliminar_usuario.php?id=<?= $fila['id'] ?>" onclick="return confirm('¿Eliminar este usuario?')">Eliminar</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</body>
</html>
