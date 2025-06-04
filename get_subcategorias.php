<?php
header('Content-Type: application/json');

if (!isset($_GET['categoria_id'])) {
    echo json_encode([]);
    exit;
}

try {
    $db = new SQLite3('TiendaDB.sqlite');
    $db->enableExceptions(true);
} catch (Exception $e) {
    die("Error al conectar a la base de datos: " . $e->getMessage());
}

$categoria_id = (int) $_GET['categoria_id'];

$result = $db->query("SELECT subcategory_id, nombre FROM subcategorias WHERE category_id = $categoria_id");

$subcategorias = [];
while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
    $subcategorias[] = $row;
}

echo json_encode($subcategorias);
?>
