<?php
// Conexión a la base de datos
$db = new SQLite3('TiendaDB.sqlite');

include 'init.php'; // Conexión con la base de datos SQLite

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Preparar la sentencia DELETE
    $stmt = $db->prepare('DELETE FROM productos WHERE id = :id');
    $stmt->bindValue(':id', $id, SQLITE3_INTEGER);

    $result = $stmt->execute();

    if ($db->changes() > 0) {
        echo "<p style='color: green;'>✅ Producto eliminado correctamente.</p>";
    } else {
        echo "<p style='color: orange;'>⚠️ No se encontró un producto con ese ID.</p>";
    }
} else {
    echo "<p style='color: red;'>❌ ID de producto no proporcionado.</p>";
}
?>

