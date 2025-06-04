<?php
// Iniciar la sesión
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Ruta de la base de datos
define('DB_PATH', './TiendaDB.sqlite');

// Conexión con SQLite3
class TiendaDB extends SQLite3 {
    function __construct() {
        $this->open(DB_PATH);
    }
}

// Crear una instancia global para usar en otros archivos
$db = new TiendaDB();

// Validar la conexión
if (!$db) {
    die("Error al conectar con la base de datos: " . $db->lastErrorMsg());
}

// Crear tabla productos si no existe
$db->exec("CREATE TABLE IF NOT EXISTS productos (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    nombre TEXT,
    descripcion TEXT,
    precio REAL,
    imagen1 TEXT,
    imagen2 TEXT,
    imagen3 TEXT,
    imagen4 TEXT,
    cantidad INT
)");

// Crear tabla usuarios si no existe
$db->exec("CREATE TABLE IF NOT EXISTS usuarios (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    nombre TEXT,
    apellido TEXT,
    correo TEXT,
    telefono TEXT,
    direccion TEXT,
    cp TEXT,
    total REAL,
    fecha TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)");
?>