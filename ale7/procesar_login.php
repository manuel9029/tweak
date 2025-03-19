<?php
require_once 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recoger datos del formulario
    $login = limpiarDato($_POST['login_username']); // Puede ser username o email
    $password = $_POST['login_password'];
    
    // Comprobar si el usuario existe
    $sql = "SELECT id, username, password, email, telefono, edad, foto_perfil FROM usuarios WHERE username = '$login' OR email = '$login'";
    $result = $conn->query($sql);
    
    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();
        
        // Verificar contraseña
        if (password_verify($password, $user['password'])) {
            // Contraseña correcta, iniciar sesión
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['telefono'] = $user['telefono'];
            $_SESSION['edad'] = $user['edad'];
            $_SESSION['foto_perfil'] = $user['foto_perfil'];
            
            // Redirigir al inicio
            header("Location: index.php");
            exit();
        } else {
            // Contraseña incorrecta
            header("Location: login.php?error=true");
            exit();
        }
    } else {
        // Usuario no encontrado
        header("Location: login.php?error=true");
        exit();
    }
} else {
    // Si no es una solicitud POST, redirigir
    header("Location: login.php");
    exit();
}