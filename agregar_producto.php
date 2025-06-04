<?php
include 'init.php';

function guardarImagen($archivo) {
    if (isset($_FILES[$archivo]) && $_FILES[$archivo]['error'] === UPLOAD_ERR_OK) {
    $ext = pathinfo($_FILES[$archivo]['name'], PATHINFO_EXTENSION);
    $nombreArchivo = uniqid() . "." . $ext;
    $rutaDestino = "img/" . $nombreArchivo;
    move_uploaded_file($_FILES[$archivo]['tmp_name'], $rutaDestino);
    return $rutaDestino;
    }
    return null;
}

$nombre = $_POST['nombre'];
$descripcion = $_POST['descripcion'];
$precio = $_POST['precio'];
$cantidad = $_POST['cantidad'];
$category_id = $_POST['categoria_id'];
$subcategory_id = $_POST['subcategoria_id'];
$item_id = $_POST['item_id'];
$img1 = guardarImagen('imagen1');
$img2 = guardarImagen('imagen2');
$img3 = guardarImagen('imagen3');
$img4 = guardarImagen('imagen4');

$stmt = $db->prepare("INSERT INTO productos (nombre, descripcion, precio, imagen1, imagen2, imagen3, imagen4, cantidad, categoria_id, subcategoria_id, item_id)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

$stmt->bindValue(1, $nombre, SQLITE3_TEXT);
$stmt->bindValue(2, $descripcion, SQLITE3_TEXT);
$stmt->bindValue(3, $precio, SQLITE3_FLOAT);
$stmt->bindValue(4, $img1, SQLITE3_TEXT);
$stmt->bindValue(5, $img2, SQLITE3_TEXT);
$stmt->bindValue(6, $img3, SQLITE3_TEXT);
$stmt->bindValue(7, $img4, SQLITE3_TEXT);
$stmt->bindValue(8, $cantidad, SQLITE3_INTEGER);
$stmt->bindValue(9, $category_id, SQLITE3_INTEGER);
$stmt->bindValue(10, $subcategory_id, SQLITE3_INTEGER);
$stmt->bindValue(11, $item_id, SQLITE3_INTEGER);

$stmt->execute();


header("Location: admin.php");
exit;
