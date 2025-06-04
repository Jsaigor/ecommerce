<?php
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["producto_id"])) {
    $producto_id = (int) $_POST["producto_id"];

    try {
        $db = new SQLite3("TiendaDB.sqlite");
        $db->enableExceptions(true);

        $stmt = $db->prepare("UPDATE productos SET cantidad = 0 WHERE id = :id");
        $stmt->bindValue(":id", $producto_id, SQLITE3_INTEGER);
        $resultado = $stmt->execute();

        if ($db->changes() > 0) {
            echo "<script>alert('Producto desactivado (cantidad = 0)'); window.location.href = 'admin.php';</script>";
        } else {
            echo "<script>alert('No se encontró el producto'); window.location.href = 'admin.php';</script>";
        }

    } catch (Exception $e) {
        echo "Error al desactivar producto: " . $e->getMessage();
    }
} else {
    echo "ID de producto no recibido.";
}
?>

