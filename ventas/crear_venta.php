<?php
session_start();
include("../includes/db.php");

if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../index.php");
    exit();
}

// Obtener productos para el formulario
$productos_data = $conn->query("SELECT * FROM productos");
$productos_array = [];
while ($row = $productos_data->fetch_assoc()) {
    $productos_array[] = $row;
}

// Procesar formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario_id = $_SESSION['usuario_id'];
    $fecha = date("Y-m-d H:i:s");
    $total = 0;
    $detalles = [];

    foreach ($_POST['producto_id'] as $index => $producto_id) {
        $producto_id = (int)$producto_id;
        $cantidad = (int)$_POST['cantidad'][$index];

        // Obtener precio y stock actual
        $stmt = $conn->prepare("SELECT precio, stock FROM productos WHERE id = ?");
        $stmt->bind_param("i", $producto_id);
        $stmt->execute();
        $producto = $stmt->get_result()->fetch_assoc();

        if (!$producto) {
            echo "Producto no encontrado (ID: $producto_id)";
            exit();
        }

        if ($producto['stock'] < $cantidad) {
            echo "Stock insuficiente para el producto ID $producto_id.";
            exit();
        }

        $subtotal = $producto['precio'] * $cantidad;
        $total += $subtotal;

        $detalles[] = [
            'producto_id' => $producto_id,
            'cantidad' => $cantidad,
            'subtotal' => $subtotal
        ];
    }

    // Insertar venta
    $stmt = $conn->prepare("INSERT INTO ventas (usuario_id, fecha, total) VALUES (?, ?, ?)");
    $stmt->bind_param("isd", $usuario_id, $fecha, $total);
    $stmt->execute();
    $venta_id = $conn->insert_id;

    // Insertar detalle y actualizar stock
    foreach ($detalles as $item) {
        // Detalle venta
        $stmt = $conn->prepare("INSERT INTO detalle_venta (venta_id, producto_id, cantidad, subtotal) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("iiid", $venta_id, $item['producto_id'], $item['cantidad'], $item['subtotal']);
        $stmt->execute();

        // Actualizar stock
        $stmt = $conn->prepare("UPDATE productos SET stock = stock - ? WHERE id = ?");
        $stmt->bind_param("ii", $item['cantidad'], $item['producto_id']);
        $stmt->execute();
    }

    header("Location: listar_ventas.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registrar Venta</title>
    <link rel="stylesheet" href="../css/ventas.css">
</head>
<body>
    <h2>Registrar Nueva Venta</h2>

    <form method="post">
        <div id="productos">
            <div class="producto-row">
                <select name="producto_id[]" required>
                    <option value="">-- Selecciona un producto --</option>
                    <?php foreach ($productos_array as $prod): ?>
                        <option value="<?= $prod['id'] ?>">
                            <?= htmlspecialchars($prod['nombre']) ?> (Stock: <?= $prod['stock'] ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
                <input type="number" name="cantidad[]" placeholder="Cantidad" min="1" required>
            </div>
        </div>

        <button type="button" onclick="agregarProducto()">+ Agregar otro producto</button>
        <br><br>
        <input type="submit" value="Registrar Venta" class="btn">
        <a href="../dashboard.php" class="btn-regresar">← Regresar al Dashboard</a>
    </form>

    <script>
        const productos = <?= json_encode($productos_array) ?>;

        function agregarProducto() {
            const div = document.createElement("div");
            div.classList.add("producto-row");

            let opciones = '<option value="">-- Selecciona un producto --</option>';
            productos.forEach(p => {
                opciones += `<option value="${p.id}">${p.nombre} (Stock: ${p.stock})</option>`;
            });

            div.innerHTML = `
                <select name="producto_id[]" required>
                    ${opciones}
                </select>
                <input type="number" name="cantidad[]" placeholder="Cantidad" min="1" required>
            `;

            document.getElementById("productos").appendChild(div);
        }
    </script>
</body>
</html>
