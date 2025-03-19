<?php
require_once 'config.php';

// Verificar si el usuario está logueado
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => 'Usuario no autenticado']);
    exit();
}

$user_id = $_SESSION['user_id'];

// Obtener todas las publicaciones con información del usuario y conteo de likes
$sql = "SELECT p.id, p.contenido, p.imagen, p.fecha_publicacion, 
        u.username, u.foto_perfil, 
        (SELECT COUNT(*) FROM likes WHERE publicacion_id = p.id) AS likes,
        (SELECT COUNT(*) FROM likes WHERE publicacion_id = p.id AND usuario_id = ?) AS user_liked
        FROM publicaciones p
        JOIN usuarios u ON p.usuario_id = u.id
        ORDER BY p.fecha_publicacion DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$posts = [];
while ($row = $result->fetch_assoc()) {
    // Formatear fecha
    $fecha = new DateTime($row['fecha_publicacion']);
    $row['fecha_publicacion'] = $fecha->format('d/m/Y H:i');
    
    // Añadir al array
    $posts[] = $row;
}

// Devolver como JSON
header('Content-Type: application/json');
echo json_encode($posts);