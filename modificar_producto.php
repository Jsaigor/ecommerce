<?php
try {
    $db = new SQLite3('TiendaDB.sqlite');
    $db->enableExceptions(true);
} catch (Exception $e) {
    die("Error al conectar a la base de datos: " . $e->getMessage());
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validar que los datos necesarios están presentes
    if (isset($_POST['id'], $_POST['nombre'], $_POST['descripcion'], $_POST['cantidad'], $_POST['precio'])) {
        
        $id = $_POST['id'];
        $nombre = $_POST['nombre'];
        $descripcion = $_POST['descripcion'];
        $cantidad = $_POST['cantidad'];
        $precio = $_POST['precio'];

        // Sentencia SQL actualizada para incluir todos los campos y usar la columna 'id'
        $stmt = $db->prepare("UPDATE productos SET 
                                nombre = :nombre, 
                                descripcion = :descripcion, 
                                cantidad = :cantidad, 
                                precio = :precio 
                            WHERE id = :id");

        // Vincular los parámetros
        $stmt->bindValue(':nombre', $nombre, SQLITE3_TEXT);
        $stmt->bindValue(':descripcion', $descripcion, SQLITE3_TEXT);
        $stmt->bindValue(':cantidad', $cantidad, SQLITE3_INTEGER);
        $stmt->bindValue(':precio', $precio, SQLITE3_FLOAT);
        $stmt->bindValue(':id', $id, SQLITE3_INTEGER);

        if ($stmt->execute()) {
            // Redirige de vuelta con un mensaje de éxito
            header('Location: admin.php?msg=Producto_modificado_exitosamente');
            exit;
        } else {
            echo 'Error al modificar el producto.';
        }
    } else {
        echo 'Error: Faltan datos en el formulario.';
    }
}
?>