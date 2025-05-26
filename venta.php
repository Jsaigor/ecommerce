<?php
// Finalizar compra
// Guardar los datos del usuario
$db = new SQLite3('TiendaDB.sqlite');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['nombre'], $_POST['apellido'], $_POST['correo'], $_POST['telefono'], $_POST['direccion'], $_POST['cp'], $_POST['total'])) {
    $stmt = $db->prepare("INSERT INTO usuarios (nombre, apellido, correo, telefono, direccion, cp, total) 
    VALUES (:nombre, :apellido, :correo, :telefono, :direccion, :cp, :total)");

    $stmt->bindValue(':nombre', $_POST['nombre'], SQLITE3_TEXT);
    $stmt->bindValue(':apellido', $_POST['apellido'], SQLITE3_TEXT);
    $stmt->bindValue(':correo', $_POST['correo'], SQLITE3_TEXT);
    $stmt->bindValue(':telefono', $_POST['telefono'], SQLITE3_TEXT);
    $stmt->bindValue(':direccion', $_POST['direccion'], SQLITE3_TEXT);
    $stmt->bindValue(':cp', $_POST['cp'], SQLITE3_TEXT);
    $stmt->bindValue(':total', $_POST['total'], SQLITE3_FLOAT);

    $stmt->execute();



        // Enviar email a Formspree
    $url = "https://formspree.io/f/myzwaoqk";
    $correo=$_POST['correo'];
    $nombre=$_POST['nombre'];
    $apellido=$_POST['apellido'];
    $data = [
        'email' => $correo,
        'message' => "Nuevo comprador: $nombre + $apellido: ($correo) compró 
                        ",
    ];

    $options = [
        'http' => [
            'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
            'method'  => 'POST',
            'content' => http_build_query($data),
        ],
    ];

    $context = stream_context_create($options);
    $result = file_get_contents($url, false, $context);

    if ($result === FALSE) {
        error_log("Error al enviar email a Formspree");
    }

}
exit;
?>