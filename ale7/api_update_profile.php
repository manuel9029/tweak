<?php
require_once 'config.php';

// Verificar si el usuario está logueado
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Usuario no autenticado']);
    exit();
}

$user_id = $_SESSION['user_id'];
$username = isset($_POST['username']) ? limpiarDato($_POST['username']) : '';
$email = isset($_POST['email']) ? limpiarDato($_POST['email']) : '';
$telefono = isset($_POST['telefono']) ? limpiarDato($_POST['telefono']) : null;
$edad = isset($_POST['edad']) ? (int)$_POST['edad'] : null;
$password = isset($_POST['password']) ? $_POST['password'] : '';
$foto_perfil = null;
$nueva_ruta_foto = null;

// Verificar si el usuario/email ya existen (excluyendo al usuario actual)
if ($username && $email) {
    $checkUser = $conn->prepare("SELECT id FROM usuarios WHERE (username = ? OR email = ?) AND id != ?");
    $checkUser->bind_param("ssi", $username, $email, $user_id);
    $checkUser->execute();
    $resultado = $checkUser->get_result();
    
    if ($resultado->num_rows > 0) {
        echo json_encode(['success' => false, 'message' => 'El nombre de usuario o correo ya está en uso']);
        exit();
    }
}

// Manejar la subida de foto de perfil
if (isset($_FILES['foto_perfil']) && $_FILES['foto_perfil']['error'] == 0) {
    $allowed = array("jpg" => "image/jpg", "jpeg" => "image/jpeg", "gif" => "image/gif", "png" => "image/png");
    $filename = $_FILES["foto_perfil"]["name"];
    $filetype = $_FILES["foto_perfil"]["type"];
    $filesize = $_FILES["foto_perfil"]["size"];

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
    $nuevo_nombre = uniqid('profile_') . '.' . $ext;
    $upload_dir = '../uploads/profiles/';
    
    // Crear directorio si no existe
    if (!file_exists($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }
    
    $upload_path = $upload_dir . $nuevo_nombre;
    
    // Mover archivo
    if (move_uploaded_file($_FILES['foto_perfil']['tmp_name'], $upload_path)) {
        $foto_perfil = 'uploads/profiles/' . $nuevo_nombre;
        $nueva_ruta_foto = $foto_perfil;
    } else {
        echo json_encode(['success' => false, 'message' => 'Error al subir la imagen de perfil']);
        exit();
    }
}

// Preparar la consulta SQL en función de los campos proporcionados
$updateFields = [];
$queryTypes = "";
$queryParams = [];

if ($username) {
    $updateFields[] = "username = ?";
    $queryTypes .= "s";
    $queryParams[] = $username;
}

if ($email) {
    $updateFields[] = "email = ?";
    $queryTypes .= "s";
    $queryParams[] = $email;
}

if ($telefono !== null) {
    $updateFields[] = "telefono = ?";
    $queryTypes .= "s";
    $queryParams[] = $telefono;
}

if ($edad !== null) {
    $updateFields[] = "edad = ?";
    $queryTypes .= "i";
    $queryParams[] = $edad;
}

if ($password) {
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $updateFields[] = "password = ?";
    $queryTypes .= "s";
    $queryParams[] = $hashed_password;
}

if ($foto_perfil) {
    $updateFields[] = "foto_perfil = ?";
    $queryTypes .= "s";
    $queryParams[] = $foto_perfil;
}

// Añadir el ID de usuario para la cláusula WHERE
$queryTypes .= "i";
$queryParams[] = $user_id;

// Construir la consulta SQL
$sql = "UPDATE usuarios SET " . implode(", ", $updateFields) . " WHERE id = ?";

// Preparar y ejecutar la consulta
$stmt = $conn->prepare($sql);

// Vincular parámetros dinámicamente
$stmt->bind_param($queryTypes, ...$queryParams);

if ($stmt->execute()) {
    // Actualizar la sesión con los nuevos datos
    if ($username) $_SESSION['username'] = $username;
    if ($email) $_SESSION['email'] = $email;
    if ($telefono !== null) $_SESSION['telefono'] = $telefono;
    if ($edad !== null) $_SESSION['edad'] = $edad;
    if ($foto_perfil) $_SESSION['foto_perfil'] = $foto_perfil;
    
    echo json_encode([
        'success' => true, 
        'message' => 'Perfil actualizado correctamente',
        'foto_perfil' => $nueva_ruta_foto
    ]);
} else {
    echo json_encode(['success' => false, 'message' => 'Error al actualizar el perfil: ' . $conn->error]);
}