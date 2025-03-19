<?php
require_once 'config.php';

// Verificar si el usuario está logueado
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Usuario no autenticado']);
    exit();
}

$user_id = $_SESSION['user_id'];
$post_id = isset($_POST['post_id']) ? (int)$_POST['post_id'] : 0;

if (!$post_id) {
    echo json_encode(['success' => false, 'message' => 'ID de publicación no válido']);
    exit();
}

// Verificar si el usuario ya dio like a esta publicación
$checkLike = $conn->prepare("SELECT id FROM likes WHERE publicacion_id = ? AND usuario_id = ?");
$checkLike->bind_param("ii", $post_id, $user_id);
$checkLike->execute();
$resultado = $checkLike->get_result();

if ($resultado->num_rows > 0) {
    // Ya dio like, así que lo quitamos
    $sql = "DELETE FROM likes WHERE publicacion_id = ? AND usuario_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $post_id, $user_id);
    
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'action' => 'unliked']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error al quitar like']);
    }
} else {
    // No ha dado like, así que lo agregamos
    $sql = "INSERT INTO likes (publicacion_id, usuario_id) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $post_id, $user_id);
    
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'action' => 'liked']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error al dar like']);
    }
}