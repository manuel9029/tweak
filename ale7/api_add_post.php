<?php
require_once 'config.php';

// Verificar si el usuario está logueado
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Usuario no autenticado']);
    exit();
}

$user_id = $_SESSION['user_id'];
$contenido = isset($_POST['contenido']) ? limpiarDato($_POST['contenido']) : '';
$imagen = null;

// Manejar la subida de imágenes
if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] == 0) {
    $allowed = array("jpg" => "image/jpg", "jpeg" => "image/jpeg", "gif" => "image/gif", "png" => "image/png");
    $filename = $_FILES["imagen"]["name"];
    $filetype = $_FILES["imagen"]["type"];
    $filesize = $_FILES["imagen"]["size"];

    // Verificar extensión
    $ext = pathinfo($filename, PATHINFO_EXTENSION);
    if (!array_key_exists($ext, $allowed)) {
        echo json_encode(['success' => false, 'message' => 'Formato de imagen no permitido']);
        exit();
    }

    // Verificar tamaño máximo (5MB)
    $maxsize = 5 * 1024 * 1024;
    if ($filesize > $maxsize) {
        echo json_encode(['success' => false, 'message' => 'La imagen excede el tamaño máximo (5MB)']);
        exit();
    }

    // Crear nombre único para el archivo
    $nuevo_nombre = uniqid('post_') . '.' . $ext;
    $upload_dir = '../uploads/posts/';
    
    // Crear directorio si no existe
    if (!file_exists($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }
    
    $upload_path = $upload_dir . $nuevo_nombre;
    
    // Mover archivo
    if (move_uploaded_file($_FILES['imagen']['tmp_name'], $upload_path)) {
        $imagen = 'uploads/posts/' . $nuevo_nombre;
    } else {
        echo json_encode(['success' => false, 'message' => 'Error al subir la imagen']);
        exit();
    }
}

// Insertar publicación en la base de datos
$sql = "INSERT INTO publicaciones (usuario_id, contenido, imagen) VALUES (?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("iss", $user_id, $contenido, $imagen);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Publicación creada correctamente']);
} else {
    echo json_encode(['success' => false, 'message' => 'Error al crear la publicación: ' . $conn->error]);
}