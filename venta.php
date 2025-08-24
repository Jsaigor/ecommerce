<?php
session_start();

// Validar que la solicitud sea POST y que el carrito no esté vacío
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_SESSION['carrito'])) {
    // Si no es POST o el carrito está vacío, redirigir al inicio.
    header("Location: index.php");
    exit;
}

// Conexión a la base de datos
try {
    $db = new SQLite3('TiendaDB.sqlite');
    $db->enableExceptions(true);
} catch (Exception $e) {
    // Manejar error de conexión
    error_log("Error de conexión a la base de datos: " . $e->getMessage());
    // Podrías redirigir a una página de error para el usuario
    die("Hubo un problema al procesar tu pedido. Por favor, intenta más tarde.");
}

// ---- PASO 1: GUARDAR DATOS DEL COMPRADOR ----
$stmt = $db->prepare("INSERT INTO usuarios (nombre, apellido, correo, telefono, direccion, cp, total) 
                    VALUES (:nombre, :apellido, :correo, :telefono, :direccion, :cp, :total)");

$stmt->bindValue(':nombre', $_POST['nombre'], SQLITE3_TEXT);
$stmt->bindValue(':apellido', $_POST['apellido'], SQLITE3_TEXT);
$stmt->bindValue(':correo', $_POST['correo'], SQLITE3_TEXT);
$stmt->bindValue(':telefono', $_POST['telefono'], SQLITE3_TEXT);
$stmt->bindValue(':direccion', $_POST['direccion'], SQLITE3_TEXT);
$stmt->bindValue(':cp', $_POST['cp'], SQLITE3_TEXT);
$stmt->bindValue(':total', (float)$_POST['total'], SQLITE3_FLOAT);
$stmt->execute();


// ---- PASO 2: ACTUALIZAR EL STOCK DE PRODUCTOS ----
foreach ($_SESSION['carrito'] as $item) {
    $stmt = $db->prepare("UPDATE productos SET cantidad = cantidad - :cantidad WHERE id = :id");
    $stmt->bindValue(':cantidad', $item['cantidad'], SQLITE3_INTEGER);
    $stmt->bindValue(':id', $item['id'], SQLITE3_INTEGER);
    $stmt->execute();
}


// ---- PASO 3: ENVIAR CORREO DE NOTIFICACIÓN (FORMSPREE) ----
function obtenerCarritoComoTexto() {
    $texto = "";
    foreach ($_SESSION['carrito'] as $producto) {
        $texto .= sprintf(
            "ID: %s - %s (%d x $%.2f)\n",
            $producto['id'],
            $producto['nombre'],
            $producto['cantidad'],
            $producto['precio']
        );
    }
    return $texto;
}

$data = [
    'email'   => $_POST['correo'],
    'message' => "Nuevo comprador: {$_POST['nombre']} {$_POST['apellido']} ({$_POST['correo']}) compró:\n\n" . obtenerCarritoComoTexto(),
    'Total de la compra:' => '$' . $_POST['total']
];

$options = [
    'http' => [
        'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
        'method'  => 'POST',
        'content' => http_build_query($data),
    ],
];
$context = stream_context_create($options);
@file_get_contents("https://formspree.io/f/myzwaoqk", false, $context); // Usamos @ para suprimir warnings si falla


// ---- PASO 4: PREPARAR LA SESIÓN Y REDIRIGIR ----

// 1. Guardar el resumen para el modal
$_SESSION['resumen'] = $_SESSION['carrito'];
$_SESSION['total_compra'] = (float)$_POST['total'];

// 2. Vaciar el carrito AHORA
$_SESSION['carrito'] = [];

// 3. Redirigir al usuario
header("Location: carrito.php?exito=1");
exit; 