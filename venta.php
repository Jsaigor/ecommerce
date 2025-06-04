<?php
// Finalizar compra session_start();
session_start();
// Asegurarse de que el carrito esté definido
$_SESSION['carrito'] = $_SESSION['carrito'] ?? [];

$db = new SQLite3('TiendaDB.sqlite');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['nombre'], $_POST['apellido'], $_POST['correo'], $_POST['telefono'], $_POST['direccion'], $_POST['cp'], $_POST['total'])){
    $stmt = $db->prepare("INSERT INTO usuarios (nombre, apellido, correo, telefono, direccion, cp, total) 
    VALUES (:nombre, :apellido, :correo, :telefono, :direccion, :cp, :total)");

    $stmt->bindValue(':nombre', $_POST['nombre'], SQLITE3_TEXT);
    $stmt->bindValue(':apellido', $_POST['apellido'], SQLITE3_TEXT);
    $stmt->bindValue(':correo', $_POST['correo'], SQLITE3_TEXT);
    $stmt->bindValue(':telefono', $_POST['telefono'], SQLITE3_TEXT);
    $stmt->bindValue(':direccion', $_POST['direccion'], SQLITE3_TEXT);
    $stmt->bindValue(':cp', $_POST['cp'], SQLITE3_TEXT);
    $stmt->bindValue(':total', $_POST['total'], SQLITE3_FLOAT);

    $stmt->execute();
}

    // Actualización de cantidades en la tabla productos
    if (isset($_POST['finalizar'])) {
    foreach ($_SESSION['carrito'] as $id => $item) {
        $stmt = $db->prepare("UPDATE productos SET cantidad = cantidad - ? WHERE id = ?");
        $stmt->bindValue(1, $item['cantidad'], SQLITE3_INTEGER);
        $stmt->bindValue(2, $item['id'], SQLITE3_INTEGER);
        $stmt->execute();
    }   
    }

// Enviar email a Formspree
$url = "https://formspree.io/f/myzwaoqk";
$correo = $_POST['correo'];
$nombre = $_POST['nombre'];
$apellido = $_POST['apellido'];

function obtenerCarritoComoTexto() {
    $texto = "";
    foreach ($_SESSION['carrito'] as $producto) {
        $texto .= sprintf("ID: %s - %s (%d x \$%.2f)\n", 
            $producto['id'], 
            $producto['nombre'], 
            $producto['cantidad'], 
            $producto['precio']);
    }
    return $texto;
}

$carritoTexto = obtenerCarritoComoTexto();

$data = [
    'email' => $correo,
    'message' => "Nuevo comprador: $nombre $apellido ($correo) compró:\n\n$carritoTexto"
];

$options = [
    'http' => [
        'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
        'method'  => 'POST',
        'content' => http_build_query($data),
    ],
];

$context = stream_context_create($options);
$result = file_get_contents($url, false, $context);

if ($result === FALSE) {
    error_log('Error al enviar email a Formspree');
}

// Vaciar el carrito
$_SESSION['carrito'] = [];

// Redirigir al carrito con mensaje de éxito
$_SESSION['resumen'] = $_SESSION['carrito'];  // Guarda los productos
$_SESSION['total_compra'] = $_POST['total'];  // Guarda el total
header("Location: carrito.php?exito=1");
exit;

?>