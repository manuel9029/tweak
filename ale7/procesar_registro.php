<?php
require_once 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recoger datos del formulario
    $username = limpiarDato($_POST['username']);
    $email = limpiarDato($_POST['email']);
    $password = $_POST['password'];
    $telefono = isset($_POST['telefono']) ? limpiarDato($_POST['telefono']) : null;
    $edad = isset($_POST['edad']) ? (int)$_POST['edad'] : null;
    
    // Verificar si el usuario o correo ya existen
    $checkUser = $conn->query("SELECT id FROM usuarios WHERE username = '$username' OR email = '$email'");
    
    if ($checkUser->num_rows > 0) {
        // Si ya existe, redirigir con mensaje de error
        header("Location: registro.php?error=exists");
        exit();
    }
    
    // Procesar la imagen del perfil
    $foto_perfil = null;
    if (isset($_FILES['foto_perfil']) && $_FILES['foto_perfil']['error'] == 0) {
        $allowed = array("jpg" => "image/jpg", "jpeg" => "image/jpeg", "gif" => "image/gif", "png" => "image/png");
        $filename = $_FILES["foto_perfil"]["name"];
        $filetype = $_FILES["foto_perfil"]["type"];
        $filesize = $_FILES["foto_perfil"]["size"];
    
        // Verificar extension
        $ext = pathinfo($filename, PATHINFO_EXTENSION);
        if (!array_key_exists($ext, $allowed)) {
            header("Location: registro.php?error=file");
            exit();
        }
    
        // Verificar tamaño máximo (5MB)
        $maxsize = 5 * 1024 * 1024;
        if ($filesize > $maxsize) {
            header("Location: registro.php?error=filesize");
            exit();
        }
    
        // Crear nombre único para el archivo
        $nuevo_nombre = uniqid('profile_') . '.' . $ext;
        $upload_dir = 'uploads/profiles/';
        
        // Crear directorio si no existe
        if (!file_exists($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }
        
        $upload_path = $upload_dir . $nuevo_nombre;
        
        // Mover archivo
        if (move_uploaded_file($_FILES['foto_perfil']['tmp_name'], $upload_path)) {
            $foto_perfil = $upload_path;
        }
    }
    
    // Hashear la contraseña
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    
    // Insertar usuario en la base de datos
    $sql = "INSERT INTO usuarios (username, email, password, telefono, edad, foto_perfil) 
            VALUES ('$username', '$email', '$hashed_password', " . 
            ($telefono ? "'$telefono'" : "NULL") . ", " . 
            ($edad ? "$edad" : "NULL") . ", " . 
            ($foto_perfil ? "'$foto_perfil'" : "NULL") . ")";
    
    if ($conn->query($sql) === TRUE) {
        // Registro exitoso, redirigir a login
        header("Location: login.php?registered=true");
        exit();
    } else {
        // Error en la inserción
        header("Location: registro.php?error=db");
        exit();
    }
} else {
    // Si no es una solicitud POST, redirigir
    header("Location: registro.php");
    exit();
}