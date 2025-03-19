<?php
// Detalles de conexión a la base de datos
$host = "localhost";
$username = "root";
$password = "";
$database = "tweak_db";

// Crear conexión
$conn = new mysqli($host, $username, $password, $database);

// Verificar conexión
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Establecer charset a utf8mb4
$conn->set_charset("utf8mb4");

// Iniciar sesión si no está iniciada
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Función para limpiar datos de entrada
function limpiarDato($dato) {
    global $conn;
    $dato = trim($dato);
    $dato = stripslashes($dato);
    $dato = htmlspecialchars($dato);
    return $conn->real_escape_string($dato);
}
?>

