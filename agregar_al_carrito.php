<?php
session_start();

if (!isset($_SESSION['carrito'])) {
    $_SESSION['carrito'] = [];
}

$id = isset($_POST['id']) ? intval($_POST['id']) : 0;

if ($id > 0) {
    $encontrado = false;
    foreach ($_SESSION['carrito'] as &$item) {
        if ($item['id'] === $id) {
            $item['cantidad']++;
            $encontrado = true;
            break;
        }
    }
    if (!$encontrado) {
        $_SESSION['carrito'][] = ['id' => $id, 'cantidad' => 1];
    }

    echo json_encode(['ok' => true, 'count' => count($_SESSION['carrito'])]);
} else {
    echo json_encode(['ok' => false]);
}
