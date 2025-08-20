<?php
// Este script busca productos por item_id y devuelve formularios HTML para editarlos.

if (!isset($_GET['item_id']) || empty($_GET['item_id'])) {
    echo '<p class="text-warning text-center">Debe seleccionar un ítem para buscar.</p>';
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

// Usamos sentencias preparadas para mayor seguridad
$stmt = $db->prepare("SELECT * FROM productos WHERE item_id = :item_id ORDER BY nombre");
$stmt->bindValue(':item_id', $itemId, SQLITE3_INTEGER);
$result = $stmt->execute();

$htmlOutput = '';
$productosEncontrados = 0;

while ($producto = $result->fetchArray(SQLITE3_ASSOC)) {
    $productosEncontrados++;
    // Escapamos los datos con htmlspecialchars para evitar problemas de seguridad (XSS)
    $p_id = htmlspecialchars($producto['id']);
    $p_nombre = htmlspecialchars($producto['nombre']);
    $p_descripcion = htmlspecialchars($producto['descripcion']);
    $p_cantidad = htmlspecialchars($producto['cantidad']);
    $p_precio = htmlspecialchars($producto['precio']);

    // Usamos la sintaxis HEREDOC para generar el HTML de forma más limpia
    $htmlOutput .= <<<HTML
    <form action="modificar_producto.php" method="POST" class="border p-3 mb-4 rounded shadow-sm">
        <input type="hidden" name="id" value="{$p_id}">
        
        <h5 class="mb-3">Editando: {$p_nombre} <small class="text-muted">(ID: {$p_id})</small></h5>
        
        <div class="row g-3">
            <div class="col-md-12">
                <label class="form-label">Nombre</label>
                <input type="text" name="nombre" class="form-control" value="{$p_nombre}" required>
            </div>
            <div class="col-md-12">
                <label class="form-label">Descripción</label>
                <textarea name="descripcion" class="form-control" rows="3" required>{$p_descripcion}</textarea>
            </div>
            <div class="col-md-6">
                <label class="form-label">Cantidad (Stock)</label>
                <input type="number" name="cantidad" class="form-control" value="{$p_cantidad}" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Precio</label>
                <input type="number" step="0.01" name="precio" class="form-control" value="{$p_precio}" required>
            </div>
            <div class="col-12 mt-3">
                <button type="submit" class="btn btn-warning">Guardar Cambios</button>
            </div>
        </div>
    </form>
HTML;
}

if ($productosEncontrados === 0) {
    echo '<p class="text-info text-center">No se encontraron productos para el ítem seleccionado.</p>';
} else {
    echo $htmlOutput;
}
?>