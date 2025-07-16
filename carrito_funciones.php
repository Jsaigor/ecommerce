<?php
// Las funciones del Carrito
function iniciarCarrito() {
    if (!isset($_SESSION['carrito'])) {
        $_SESSION['carrito'] = [];
    }
}

// function agregarAlCarrito($idProducto, $cantidad = 1) {
//     iniciarCarrito();

function agregarAlCarrito($idProducto, $cantidad = 1) {
    iniciarCarrito();

    foreach ($_SESSION['carrito'] as &$item) {
        if ($item['id'] == $idProducto) {
            $item['cantidad'] += $cantidad;
            return;
        }
    }

    // Consultar datos desde la DB
    $db = new SQLite3('TiendaDB.sqlite');
    $stmt = $db->prepare("SELECT nombre, precio FROM productos WHERE id = ?");
    $stmt->bindValue(1, $idProducto, SQLITE3_INTEGER);
    $result = $stmt->execute()->fetchArray(SQLITE3_ASSOC);

    if ($result) {
        $_SESSION['carrito'][] = [
            'id' => $idProducto,
            'nombre' => $result['nombre'],
            'precio' => $result['precio'],
            'cantidad' => $cantidad
        ];
    }


    // // Si ya existe el producto, sumar cantidad
    // foreach ($_SESSION['carrito'] as &$item) {
    //     if ($item['id'] == $idProducto) {
    //         $item['cantidad'] += $cantidad;
    //         return;
    //     }
    // }
    // // Si no existe, agregarlo
    // $_SESSION['carrito'][] = ['id' => $idProducto, 'cantidad' => $cantidad];
}

function normalizarCarrito() {
    iniciarCarrito();

    foreach ($_SESSION['carrito'] as $k => $v) {
        if (is_int($v)) {
            $_SESSION['carrito'][$k] = ['id' => $v, 'cantidad' => 1];
        }
    }
}

function obtenerCarrito() {
    iniciarCarrito();
    return $_SESSION['carrito'];
}
