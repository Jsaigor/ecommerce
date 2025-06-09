<?php
// Asegurate de incluir esto donde uses las funciones
function iniciarCarrito() {
    if (!isset($_SESSION['carrito'])) {
        $_SESSION['carrito'] = [];
    }
}

function agregarAlCarrito($idProducto, $cantidad = 1) {
    iniciarCarrito();

    // Si ya existe el producto, sumar cantidad
    foreach ($_SESSION['carrito'] as &$item) {
        if ($item['id'] == $idProducto) {
            $item['cantidad'] += $cantidad;
            return;
        }
    }
    // Si no existe, agregarlo
    $_SESSION['carrito'][] = ['id' => $idProducto, 'cantidad' => $cantidad];
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
