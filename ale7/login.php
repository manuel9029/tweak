<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - Tweak</title>
    <link rel="stylesheet" href="css/index.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
    <style>
        .login-container {
            max-width: 400px;
            margin: 100px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        .form-group input {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .form-actions {
            margin-top: 20px;
            text-align: center;
        }
        .form-actions button {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-weight: bold;
        }
        .form-actions a {
            display: block;
            margin-top: 10px;
            text-decoration: none;
            color: #666;
        }
        .error-message {
            color: red;
            margin-bottom: 15px;
            text-align: center;
        }
        .app-logo {
            text-align: center;
            font-size: 2.5em;
            font-weight: bold;
            margin-bottom: 20px;
            color: #4CAF50;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="app-logo">Tweak</div>
        
        <h2>Iniciar Sesión</h2>
        
        <?php
        if (isset($_GET['error'])) {
            echo '<div class="error-message">Usuario o contraseña incorrectos</div>';
        }
        if (isset($_GET['registered'])) {
            echo '<div style="color: green; margin-bottom: 15px; text-align: center;">Cuenta creada exitosamente. Ahora puedes iniciar sesión.</div>';
        }
        ?>
        
        <form action="procesar_login.php" method="post">
            <div class="form-group">
                <label for="login_username">Usuario o correo electrónico:</label>
                <input type="text" id="login_username" name="login_username" required>
            </div>
            
            <div class="form-group">
                <label for="login_password">Contraseña:</label>
                <input type="password" id="login_password" name="login_password" required>
            </div>
            
            <div class="form-actions">
                <button type="submit">Iniciar Sesión</button>
                <a href="registro.php">¿No tienes una cuenta? Regístrate</a>
            </div>
        </form>
    </div>
</body>
</html>