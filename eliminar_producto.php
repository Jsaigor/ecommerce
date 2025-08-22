<?php
// Establece la cabecera para devolver una respuesta JSON
header('Content-Type: application/json');

// La conexión se podría incluir en un archivo separado como init.php o conexion.php
try {
    $db = new SQLite3('TiendaDB.sqlite');
    $db->enableExceptions(true);
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => 'Error al conectar a la base de datos.']);
    exit;
}

// Usamos POST para recibir el ID, es más seguro para operaciones de borrado
if (isset($_POST['id'])) {
    $id = (int)$_POST['id'];

    $stmt = $db->prepare('DELETE FROM productos WHERE id = :id');
    $stmt->bindValue(':id', $id, SQLITE3_INTEGER);
    $stmt->execute();

    // $db->changes() devuelve el número de filas afectadas. Si es > 0, se borró.
    if ($db->changes() > 0) {
        echo json_encode(['status' => 'success', 'message' => "✅ Producto con ID {$id} eliminado correctamente."]);
    } else {
        echo json_encode(['status' => 'error', 'message' => "⚠️ No se encontró un producto con el ID {$id}."]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => '❌ No se proporcionó un ID de producto.']);
}
?>