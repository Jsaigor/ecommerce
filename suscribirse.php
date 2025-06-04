<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['correo'])) {
    $correo = trim($_POST['correo']);
    $fecha = date('Y-m-d H:i:s');

    $db = new SQLite3('TiendaDB.sqlite');

    // Verificamos si ya está registrado
    $stmt = $db->prepare("SELECT COUNT(*) as total FROM usuarios WHERE correo = ?");
    $stmt->bindValue(1, $correo, SQLITE3_TEXT);
    $result = $stmt->execute()->fetchArray(SQLITE3_ASSOC);

    if ($result['total'] == 0) {
        // Insertar correo y fecha
        $insert = $db->prepare("INSERT INTO usuarios (correo, fecha) VALUES (?, ?)");
        $insert->bindValue(1, $correo, SQLITE3_TEXT);
        $insert->bindValue(2, $fecha, SQLITE3_TEXT);
        $insert->execute();        

            $url = "https://formspree.io/f/mkgraylq";
            $data = ['email' => $correo]; // Formspree espera el campo "email" y en la DB se llama "correo"

            $options = [
            'http' => [
            'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
            'method'  => 'POST',
            'content' => http_build_query($data),],];

            $context  = stream_context_create($options);
            $result = file_get_contents($url, false, $context);

        if ($result === FALSE) {
        // Si hay fallo
        error_log("Error al enviar POST a Formspree");
        }
        // Si salió todo bien
        echo "<script>alert('¡Gracias por suscribirte!'); window.history.back();</script>";
        // Si falla en la verificación desde la DB
    } else {
        echo "<script>alert('Ese correo ya está registrado.'); window.history.back();</script>";
    }
    // Si el correo no tiene formato correo
} else {
    echo "<script>alert('Correo no válido.'); window.history.back();</script>";
}
?>