<?php
// Este script busca productos por item_id y devuelve una lista HTML para eliminarlos.

if (!isset($_GET['item_id']) || empty($_GET['item_id'])) {
    exit;
}

$itemId = (int)$_GET['item_id'];

try {
    $db = new SQLite3('TiendaDB.sqlite');
    $db->enableExceptions(true);
} catch (Exception $e) {
    echo '<p class="text-danger text-center">Error al conectar a la base de datos.</p>';
    exit;
}

$stmt = $db->prepare("SELECT id, nombre FROM productos WHERE item_id = :item_id ORDER BY nombre");
$stmt->bindValue(':item_id', $itemId, SQLITE3_INTEGER);
$result = $stmt->execute();

$productosEncontrados = 0;
$htmlOutput = '<ul class="list-group">';

while ($producto = $result->fetchArray(SQLITE3_ASSOC)) {
    $productosEncontrados++;
    $p_id = htmlspecialchars($producto['id']);
    $p_nombre = htmlspecialchars($producto['nombre']);
    
    $htmlOutput .= <<<HTML
    <li class="list-group-item d-flex justify-content-between align-items-center" id="producto-item-{$p_id}">
        <span>
            <strong>ID:</strong> {$p_id} &mdash; {$p_nombre}
        </span>
        <button type="button" class="btn btn-danger btn-sm btn-eliminar" data-id="{$p_id}" data-nombre="{$p_nombre}">
            Eliminar
        </button>
    </li>
HTML;
}

$htmlOutput .= '</ul>';

if ($productosEncontrados === 0) {
    echo '<p class="text-info text-center">No se encontraron productos para el ítem seleccionado.</p>';
} else {
    echo $htmlOutput;
}
?>