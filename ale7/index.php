<?php
require_once 'config.php';

// Verificar si el usuario está logueado
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Datos del usuario
$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];
$foto_perfil = $_SESSION['foto_perfil'] ?? 'https://via.placeholder.com/40';
$telefono = $_SESSION['telefono'] ?? 'No especificado';
$edad = $_SESSION['edad'] ?? 'No especificada';
$email = $_SESSION['email'];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tweak</title>
    <link rel="stylesheet" href="css/index.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
</head>
<body>
    <div id="app">
        <header id="header">
            <div class="logo">Tweak</div>
            <div class="profile-info">
                <span id="usernameHeader"><?php echo $username; ?></span>
                <img id="profilePicture" src="<?php echo $foto_perfil; ?>" alt="Perfil">
            </div>
        </header>

        <div id="homeView" class="view">
            <h1>Bienvenido a Tweak</h1>
            <p>¡Hola <span id="welcomeUsername"><?php echo $username; ?></span>!</p>
            <button onclick="changeView('posts')" class="main-button">Ver Publicaciones</button>
        </div>
 
        <div id="postsView" class="view">
            <div class="new-post">
                <div class="post-header">
                    <img id="postProfilePic" src="<?php echo $foto_perfil; ?>" alt="Perfil" class="post-profile-pic">
                    <span id="postUsername"><?php echo $username; ?></span>
                </div>
                <textarea id="postContent" placeholder="¿Qué estás pensando?"></textarea>
                <div id="imagePreview" class="image-preview"></div>
                <div class="post-controls">
                    <label for="postImage" class="custom-file-upload">
                        <i class="fas fa-image"></i> subir
                    </label>
                    <input type="file" id="postImage" accept="image/*">
                    <button id="publishBtn" class="publish-btn">Publicar</button>
                </div>
            </div>
            <div id="postsContainer"></div>
        </div>
        
        <div id="profileView" class="view">
            <div class="profile-container">
                <div class="profile-header">
                    <img id="profilePagePicture" src="<?php echo $foto_perfil; ?>" alt="Perfil" class="large-profile-pic">
                    <h2 id="profileUsername"><?php echo $username; ?></h2>
                </div>
                <div class="profile-details">
                    <p><i class="fas fa-envelope"></i> <span id="profileEmail"><?php echo $email; ?></span></p>
                    <p><i class="fas fa-phone"></i> <span id="profilePhoneNumber"><?php echo $telefono; ?></span></p>
                    <p><i class="fas fa-birthday-cake"></i> <span id="profileAge"><?php echo $edad; ?> años</span></p>
                    <p><i class="fas fa-newspaper"></i> <span id="postCount">0</span> publicaciones</p>
                </div>
            </div>
        </div>
        
        <div id="settingsView" class="view">
            <h2>Configuración</h2>
            <div class="settings-container">
                <div class="form-group">
                    <label for="editUsername">Cambiar nombre de usuario:</label>
                    <input type="text" id="editUsername" value="<?php echo $username; ?>">
                </div>
                <div class="form-group">
                    <label for="editEmail">Cambiar correo electrónico:</label>
                    <input type="email" id="editEmail" value="<?php echo $email; ?>">
                </div>
                <div class="form-group">
                    <label for="editPhone">Cambiar número de teléfono:</label>
                    <input type="tel" id="editPhone" value="<?php echo $telefono != 'No especificado' ? $telefono : ''; ?>">
                </div>
                <div class="form-group">
                    <label for="editAge">Cambiar edad:</label>
                    <input type="number" id="editAge" value="<?php echo $edad != 'No especificada' ? $edad : ''; ?>" min="13" max="120">
                </div>
                <div class="form-group">
                    <label for="editProfilePic">Cambiar foto de perfil:</label>
                    <input type="file" id="editProfilePic" accept="image/*">
                    <div id="editProfilePreview" class="image-preview">
                        <?php if ($foto_perfil != 'https://via.placeholder.com/40'): ?>
                            <img src="<?php echo $foto_perfil; ?>" alt="Previsualización">
                        <?php endif; ?>
                    </div>
                </div>
                <div class="form-group">
                    <label for="editPassword">Nueva contraseña:</label>
                    <input type="password" id="editPassword">
                </div>
                <div class="form-group">
                    <label for="confirmPassword">Confirmar contraseña:</label>
                    <input type="password" id="confirmPassword">
                </div>
                <button id="saveChangesBtn" class="main-button">Guardar cambios</button>
               <a href="logout.php" style="background-color: red; color: white; padding: 10px; text-decoration: none; display: inline-block; border-radius: 5px;">
    Cerrar sesión
</a>

            </div>
        </div>
        
        <nav id="bottomNav">
            <ul>
                <li id="homeBtn" onclick="changeView('home')"><i class="fas fa-home"></i></li>
                <li id="postsBtn" onclick="changeView('posts')"><i class="fas fa-images"></i></li>
                <li id="profileBtn" onclick="changeView('profile')"><i class="fas fa-user"></i></li>
                <li id="settingsBtn" onclick="changeView('settings')"><i class="fas fa-cogs"></i></li>
            </ul>
        </nav>
    </div>
    
    <script>
        // Inicialización
        let currentView = 'home';
        
        // Cambiar entre vistas
        function changeView(view) {
            document.querySelectorAll('.view').forEach(v => v.style.display = 'none');
            document.getElementById(`${view}View`).style.display = 'block';
            
            document.querySelectorAll('#bottomNav li').forEach(item => {
                item.classList.remove('active');
            });
            
            const activeBtn = document.getElementById(`${view}Btn`);
            if (activeBtn) {
                activeBtn.classList.add('active');
            }
            
            currentView = view;
            
            // Si cambiamos a la vista de publicaciones, cargar las publicaciones
            if (view === 'posts') {
                cargarPublicaciones();
            }
        }
        
        // Cargar publicaciones desde la base de datos
        function cargarPublicaciones() {
            fetch('api_get_posts.php')
                .then(response => response.json())
                .then(data => {
                    renderizarPublicaciones(data);
                    document.getElementById('postCount').textContent = data.length;
                })
                .catch(error => console.error('Error cargando publicaciones:', error));
        }
        
        // Renderizar publicaciones
        function renderizarPublicaciones(posts) {
            const container = document.getElementById('postsContainer');
            container.innerHTML = '';
            
            if (posts.length === 0) {
                container.innerHTML = '<p class="no-posts">No hay publicaciones aún. ¡Sé el primero en publicar algo!</p>';
                return;
            }
            
            posts.forEach(post => {
                const postElement = document.createElement('div');
                postElement.classList.add('post');
                
                let imagenHTML = '';
                if (post.imagen) {
                    imagenHTML = `<div class="post-image-container"><img src="${post.imagen}" alt="Imagen de publicación" class="post-image-full"></div>`;
                }
                
                postElement.innerHTML = `
                    <div class="post-header">
                        <img src="${post.foto_perfil || 'https://via.placeholder.com/40'}" alt="Perfil" class="post-profile-pic">
                        <span>${post.username}</span>
                    </div>
                    <div class="post-content">${post.contenido}</div>
                    ${imagenHTML}
                    <div class="post-actions">
                        <button class="like-btn" data-id="${post.id}">
                            <i class="far fa-heart"></i> Me gusta
                        </button>
                        <span class="like-count">${post.likes || 0} Likes</span>
                        <span class="post-date">${post.fecha_publicacion}</span>
                    </div>
                `;
                
                container.appendChild(postElement);
                
                
                // Añadir evento al botón de like
                postElement.querySelector('.like-btn').addEventListener('click', function() {
                    const postId = this.getAttribute('data-id');
                    darLike(postId);
                });
            });
        }
        
        // Función para dar like
        function darLike(postId) {
            fetch('api_like_post.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `post_id=${postId}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    cargarPublicaciones(); // Recargar para ver el like actualizado
                }
            })
            .catch(error => console.error('Error dando like:', error));
        }
        
        // Función para publicar
        document.getElementById('publishBtn').addEventListener('click', function() {
            const contenido = document.getElementById('postContent').value.trim();
            const imagen = document.getElementById('postImage').files[0];
            
            if (!contenido && !imagen) {
                alert('Por favor, escribe algo o sube una imagen para publicar');
                return;
            }
            
            const formData = new FormData();
            formData.append('contenido', contenido);
            if (imagen) {
                formData.append('imagen', imagen);
            }
            
            fetch('api_add_post.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.getElementById('postContent').value = '';
                    document.getElementById('postImage').value = '';
                    document.getElementById('imagePreview').innerHTML = '';
                    cargarPublicaciones();
                } else {
                    alert('Error al publicar: ' + data.message);
                }
            })
            .catch(error => console.error('Error publicando:', error));
        });
        
        // Previsualización de imagen para publicación
        document.getElementById('postImage').addEventListener('change', function() {
            const preview = document.getElementById('imagePreview');
            preview.innerHTML = '';
            if (this.files && this.files[0]) {
                const img = document.createElement('img');
                img.src = URL.createObjectURL(this.files[0]);
                preview.appendChild(img);
            }
        });
        
        // Previsualización de imagen para perfil
        document.getElementById('editProfilePic').addEventListener('change', function() {
            const preview = document.getElementById('editProfilePreview');
            preview.innerHTML = '';
            if (this.files && this.files[0]) {
                const img = document.createElement('img');
                img.src = URL.createObjectURL(this.files[0]);
                preview.appendChild(img);
            }
        });
        
        // Guardar cambios del perfil
        document.getElementById('saveChangesBtn').addEventListener('click', function() {
            const username = document.getElementById('editUsername').value.trim();
            const email = document.getElementById('editEmail').value.trim();
            const telefono = document.getElementById('editPhone').value.trim();
            const edad = document.getElementById('editAge').value.trim();
            const password = document.getElementById('editPassword').value;
            const confirmPassword = document.getElementById('confirmPassword').value;
            const profilePic = document.getElementById('editProfilePic').files[0];
            
            if (!username || !email) {
                alert('El nombre de usuario y correo electrónico son obligatorios');
                return;
            }
            
            if (password && password !== confirmPassword) {
                alert('Las contraseñas no coinciden');
                return;
            }
            
            const formData = new FormData();
            formData.append('username', username);
            formData.append('email', email);
            if (telefono) formData.append('telefono', telefono);
            if (edad) formData.append('edad', edad);
            if (password) formData.append('password', password);
            if (profilePic) formData.append('foto_perfil', profilePic);
            
            fetch('api_update_profile.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Perfil actualizado correctamente');
                    // Actualizar la información visible en la página
                    document.getElementById('usernameHeader').textContent = username;
                    document.getElementById('profileUsername').textContent = username;
                    document.getElementById('welcomeUsername').textContent = username;
                    document.getElementById('postUsername').textContent = username;
                    
                    document.getElementById('profileEmail').textContent = email;
                    document.getElementById('profilePhoneNumber').textContent = telefono || 'No especificado';
                    document.getElementById('profileAge').textContent = (edad ? edad + ' años' : 'No especificada');
                    
                    if (data.foto_perfil) {
                        document.getElementById('profilePicture').src = data.foto_perfil;
                        document.getElementById('profilePagePicture').src = data.foto_perfil;
                        document.getElementById('postProfilePic').src = data.foto_perfil;
                    }
                    
                    // Limpiar campos de contraseña
                    document.getElementById('editPassword').value = '';
                    document.getElementById('confirmPassword').value = '';
                } else {
                    alert('Error al actualizar perfil: ' + data.message);
                }
            })
            .catch(error => console.error('Error actualizando perfil:', error));
        });
        
        // Cerrar sesión
        document.getElementById('logoutBtn').addEventListener('click', function() {
            fetch('logout.php')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        window.location.href = 'login.php';
                    }
                })
                .catch(error => console.error('Error cerrando sesión:', error));
        });
        
        // Inicializar la aplicación
        changeView('home');
    </script>
</body>
</html>