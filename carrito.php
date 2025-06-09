<?php
session_start();
include 'init.php';
require 'menu.php';
require 'footer.php';
require 'carrito_funciones.php';

// Si recibimos datos por POST, agregamos producto
 if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    agregarAlCarrito((int)$_POST['id']);
}

// Normalizamos datos por si hay entradas sueltas
normalizarCarrito();



// Obtenemos el carrito para mostrar
$carrito = obtenerCarrito();

// Agregar producto
// if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
//     $id = (int) $_POST['id'];
//     $encontrado = false;
//     foreach ($_SESSION['carrito'] as &$item) {
//         if ($item['id'] === $id) {
//             $item['cantidad']++;
//             $encontrado = true;
//             break;
//         }
//     }
//     if (!$encontrado) $_SESSION['carrito'][] = ['id' => $id, 'cantidad' => 1];
//     header("Location: carrito.php");
//     exit;
// }

// Eliminar producto
if (isset($_GET['remove'])) {
    $_SESSION['carrito'] = array_filter($_SESSION['carrito'], fn($item) => $item['id'] !== (int) $_GET['remove']);
    header("Location: carrito.php");
    exit;
}

// Vaciar carrito
if (isset($_GET['reset'])) {
    $_SESSION['carrito'] = [];
    header("Location: carrito.php");
    exit;
}

// Actualizar cantidades en carrito
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update'], $_POST['cantidades'])) {
    foreach ($_POST['cantidades'] as $id => $cantidad) {
        foreach ($_SESSION['carrito'] as &$item) {
            if ($item['id'] == $id) {
                $item['cantidad'] = max(1, (int)$cantidad);
                break;
            }
        }
    }
    header("Location: carrito.php");
    exit;
}

// Cerramos sesión después de modificar
session_write_close();

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Carrito de Compras</title>
    <link rel="icon" href="./img/icon4.png">
    <link rel="stylesheet" href="./css/bootstrap.min.css">
    <link rel="stylesheet" href="./css/estilo_v3.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel='stylesheet' href="./css/uicons-brands.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>

</head>
<body>
<?php menu(); ?>
<hr>
<main class="main-main">
<div class="container mt-5">
    <h1 class="mb-4">🛒 Carrito de Compras</h1>
    <a href="index.php" class="btn btn-secondary mb-3">Volver al Inicio</a>
    <a href="carrito.php?reset=1" class="btn btn-outline-danger mb-3 ms-2">Vaciar Carrito</a>

    <?php
    if (empty($_SESSION['carrito'])) {
        echo "<p>El carrito está vacío.</p>";
    } else {
        $ids = array_column($_SESSION['carrito'], 'id');
        $id_str = implode(',', array_map('intval', $ids));
        $stmt = $db->query("SELECT * FROM productos WHERE id IN ($id_str)");
        $productos = [];
        while ($row = $stmt->fetchArray(SQLITE3_ASSOC)) {
            $productos[$row['id']] = $row;
        }

        echo "<form method='POST'><input type='hidden' name='update' value='1'><div class='row'>";
        $total = 0;
        foreach ($_SESSION['carrito'] as $item) {
            $prod_id = $item['id'];
            if (!isset($productos[$prod_id])) continue;
            $prod = $productos[$prod_id];
            $cantidad = $item['cantidad'];
            $imagen1 = $prod['imagen1'];
            $subtotal = $prod['precio'] * $cantidad;
            $total += $subtotal;
        ?>
        <div class="container">
            <div class="row justify-content-center">
            <div class="col-md-6 col-lg-6 mb-3">
            <div class="card h-100 shadow-sm">
                <div class="card-body">
                    <?php if ($prod['imagen1']): ?>
                    <img src="<?= htmlspecialchars($prod['imagen1']) ?>" class="mb-2 img-fluid mx-auto d-block" alt="imagen" style="width: 50%; height: auto;">
                    <?php endif; ?>
                    <h5 class="card-title"><?= htmlspecialchars($prod['nombre']) ?></h5>
                    <p class="card-text">Precio: $<?= number_format($prod['precio'], 2) ?></p>
                    <p>
                        Cantidad:
                        <input type="number" name="cantidades[<?= $prod_id ?>]" value="<?= $cantidad ?>" min="1" class="form-control w-50 d-inline">
                    </p>
                    <p><strong>Subtotal:</strong> $<?= number_format($subtotal, 2) ?></p>
                    <a href="carrito.php?remove=<?= $prod_id ?>" class="btn btn-danger btn-sm">Eliminar</a>
                </div>
            </div>
        </div>
        </div>
        </div>
    <?php } ?>
        </div>
        <div class="mt-4">
            <h4>Total: $<?= number_format($total, 2) ?></h4>
            <button type="submit" class="btn btn-primary">Actualizar cantidades</button>
        </div>
        </form>
    <?php } ?>
</div>

<?php if (!empty($_SESSION['carrito'])): ?>
<div class="container mt-5">
    <h3>Datos del comprador</h3>
    <form method="POST" class="row g-3" action="venta.php">
        <input type="hidden" name="total" value="<?= number_format($total ?? 0, 2, '.', '') ?>">
        <div class="col-md-6"><input type="text" name="nombre" class="form-control" placeholder="Nombre" required></div>
        <div class="col-md-6"><input type="text" name="apellido" class="form-control" placeholder="Apellido" required></div>
        <div class="col-md-6"><input type="email" name="correo" class="form-control" placeholder="Correo" required></div>
        <div class="col-md-6"><input type="text" name="telefono" class="form-control" placeholder="Teléfono" required></div>
        <div class="col-md-8"><input type="text" name="direccion" class="form-control" placeholder="Dirección" required></div>
        <div class="col-md-4"><input type="text" name="cp" class="form-control" placeholder="Código Postal" required></div>
        <div class="col-md-12"><textarea name="Informacion" class="form-control" placeholder="Información extra para la entrega"></textarea></div>
        <div class="col-12"><button type="submit" name="finalizar" class="btn btn-success w-100">Finalizar compra</button></div>
    </form>
</div>
<?php endif; ?>

<?php if (isset($_GET['exito']) && $_GET['exito'] == 1): ?>
<!-- Modal resumen de compra --> 
<div class="modal fade" id="resumenModal" tabindex="-1" aria-labelledby="resumenModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
        <div class="modal-header bg-success text-white">
        <h5 class="modal-title" id="resumenModalLabel">¡Compra finalizada con éxito!</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
        </div>
        <div class="modal-body" id="resumenContenido">
        <?php 
        if (isset($_SESSION['resumen'])) {
            $resumenHTML = "<ul>";
            foreach ($_SESSION['resumen'] as $item) {
                $resumenHTML .= "<li>{$item['nombre']} x{$item['cantidad']}</li>";
            }
            $resumenHTML .= "</ul>";
            $resumenHTML .= "<p><strong>Total: $" . number_format($_SESSION['total_compra'], 2) . "</strong></p>";

            echo "<script>
            document.addEventListener('DOMContentLoaded', function() {
                document.getElementById('resumenContenido').innerHTML = `" . $resumenHTML . "`;
                var resumenModal = new bootstrap.Modal(document.getElementById('resumenModal'));
                resumenModal.show();
            });
            </script>";

            // Limpiar resumen luego de mostrarlo
            unset($_SESSION['resumen']);
            unset($_SESSION['total_compra']);
            unset($_SESSION['carrito']); // Limpia carrito
        }
        ?>
        </div>
        <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">OK</button>
        <button type="button" class="btn btn-primary" onclick="descargarPDF()">Guardar comprobante</button>
        </div>
    </div>
    </div>
</div>
<?php endif; ?>

</main>

<script>
function descargarPDF() {
    const { jsPDF } = window.jspdf;
    const doc = new jsPDF();
    const contenido = document.getElementById('resumenContenido').innerText;
    doc.text(contenido, 10, 10);
    doc.save('comprobante_compra.pdf');
}
</script>
<hr>
<?php footer(); ?>
<script src="./js/bootstrap.bundle.min.js"></script>
</body>
</html>