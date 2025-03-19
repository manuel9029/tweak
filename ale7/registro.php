<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro - Tweak</title>
    <link rel="stylesheet" href="css/index.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
    <style>
        .registration-container {
            max-width: 600px;
            margin: 20px auto;
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
        }
        .success-message {
            color: green;
            margin-bottom: 15px;
        }
        .image-preview {
            width: 150px;
            height: 150px;
            border: 1px dashed #ccc;
            margin: 10px 0;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }
        .image-preview img {
            max-width: 100%;
            max-height: 100%;
        }
    </style>
</head>
<body>
    <div class="registration-container">
        <h2>Crear Cuenta en Tweak</h2>
        
        <div id="mensajes"></div>
        
        <form id="registroForm" action="procesar_registro.php" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="username">Nombre de usuario:</label>
                <input type="text" id="username" name="username" required>
            </div>
            
            <div class="form-group">
                <label for="email">Correo electrónico:</label>
                <input type="email" id="email" name="email" required>
            </div>
            
            <div class="form-group">
                <label for="password">Contraseña:</label>
                <input type="password" id="password" name="password" required>
            </div>
            
            <div class="form-group">
                <label for="confirm_password">Confirmar contraseña:</label>
                <input type="password" id="confirm_password" name="confirm_password" required>
            </div>
            
            <div class="form-group">
                <label for="telefono">Número de teléfono:</label>
                <input type="tel" id="telefono" name="telefono">
            </div>
            
            <div class="form-group">
                <label for="edad">Edad:</label>
                <input type="number" id="edad" name="edad" min="13" max="120">
            </div>
            
            <div class="form-group">
                <label for="foto_perfil">Foto de perfil:</label>
                <input type="file" id="foto_perfil" name="foto_perfil" accept="image/*">
                <div id="imagePreview" class="image-preview"></div>
            </div>
            
            <div class="form-actions">
                <button type="submit">Registrarse</button>
                <a href="login.php">¿Ya tienes cuenta? Inicia sesión</a>
            </div>
        </form>
    </div>

    <script>
        // Previsualización de imagen
        document.getElementById('foto_perfil').addEventListener('change', function() {
            const preview = document.getElementById('imagePreview');
            preview.innerHTML = '';
            if (this.files && this.files[0]) {
                const img = document.createElement('img');
                img.src = URL.createObjectURL(this.files[0]);
                preview.appendChild(img);
            }
        });

        // Validación del formulario
        document.getElementById('registroForm').addEventListener('submit', function(e) {
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('confirm_password').value;
            const mensajes = document.getElementById('mensajes');
            
            if (password !== confirmPassword) {
                e.preventDefault();
                mensajes.innerHTML = '<div class="error-message">Las contraseñas no coinciden</div>';
                return false;
            }
            if (password.length < 6) {
                e.preventDefault();
                mensajes.innerHTML = '<div class="error-message">La contraseña debe tener al menos 6 caracteres</div>';
                return false;
            }
        });
    </script>
</body>
</html>