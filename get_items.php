<?php
header('Content-Type: application/json');

if (!isset($_GET['subcategoria_id'])) {
    echo json_encode([]);
    exit;
}

try {
    $db = new SQLite3('TiendaDB.sqlite');
    $db->enableExceptions(true);
} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
    exit;
}

$subcategoria_id = (int) $_GET['subcategoria_id'];

// IMPORTANTE: el campo se llama subcategory_id
$result = $db->query("SELECT item_id, nombre FROM items WHERE subcategory_id = $subcategoria_id");

$items = [];
while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
    $items[] = $row;
}

echo json_encode($items);
