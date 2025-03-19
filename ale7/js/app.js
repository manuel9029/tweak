// Almacenar datos en LocalStorage
let currentUser = JSON.parse(localStorage.getItem('currentUser')) || null;
let posts = JSON.parse(localStorage.getItem('posts')) || [];

// Función para inicializar la aplicación
function initApp() {
    if (!currentUser) {
        changeView('login');
    } else {
        updateUserInfo();
        changeView('home');
    }
}

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
}

// Actualizar la información del usuario en el header y el perfil
function updateUserInfo() {
    if (currentUser) {
        document.getElementById('usernameHeader').textContent = currentUser.username;
        document.getElementById('profileUsername').textContent = currentUser.username;
    }
}

// Manejo del inicio de sesión
function loginUser() {
    const username = document.getElementById('loginUsername').value.trim();
    const password = document.getElementById('loginPassword').value.trim();

    if (username && password) {
        currentUser = { username: username };
        localStorage.setItem('currentUser', JSON.stringify(currentUser));
        updateUserInfo();
        changeView('home');
    } else {
        alert('Por favor, ingresa un nombre de usuario y una contraseña');
    }
}

// Manejo del cierre de sesión
function logoutUser() {
    localStorage.removeItem('currentUser');
    currentUser = null;
    changeView('login');
    alert('Has cerrado sesión correctamente');
}

// Cambiar la vista de perfil y mostrar detalles
function updateProfile() {
    const newUsername = document.getElementById('editUsername').value.trim();
    const newPhone = document.getElementById('editPhone').value.trim();
    const profilePic = document.getElementById('editProfilePic').files[0];

    if (newUsername) {
        currentUser.username = newUsername;
        localStorage.setItem('currentUser', JSON.stringify(currentUser));
        updateUserInfo();
    }

    if (newPhone) {
        document.getElementById('profilePhoneNumber').textContent = newPhone;
    }

    if (profilePic) {
        const reader = new FileReader();
        reader.onload = function() {
            document.getElementById('profilePagePicture').src = reader.result;
            document.getElementById('profilePicture').src = reader.result;
        };
        reader.readAsDataURL(profilePic);
    }

    alert('Cambios guardados');
}

// Agregar nueva publicación
function addPost() {
    const postContent = document.getElementById('postContent').value.trim();
    const postImage = document.getElementById('postImage').files[0];

    if (postContent) {
        const newPost = {
            id: Date.now(), 
            username: currentUser.username,
            content: postContent,
            image: postImage ? URL.createObjectURL(postImage) : null,
            likes: 0
        };

        posts.push(newPost);
        localStorage.setItem('posts', JSON.stringify(posts));

        renderPosts();
        document.getElementById('postContent').value = '';
        document.getElementById('postImage').value = '';
    } else {
        alert('Por favor, escribe algo en tu publicación');
    }
}

// Mostrar las publicaciones
function renderPosts() {
    const postsContainer = document.getElementById('postsContainer');
    postsContainer.innerHTML = '';

    posts.forEach(post => {
        const postElement = document.createElement('div');
        postElement.classList.add('post');
        
        postElement.innerHTML = `
            <div class="post-header">
                <img src="${post.image || 'https://via.placeholder.com/40'}" alt="Post Image" class="post-image">
                <span>${post.username}</span>
            </div>
            <div class="post-content">${post.content}</div>
            <div class="post-actions">
                <button onclick="likePost(${post.id})">Me gusta</button>
                <span>${post.likes} Likes</span>
            </div>
        `;
        postsContainer.appendChild(postElement);
    });
}

// Función para dar like a una publicación
function likePost(postId) {
    const post = posts.find(p => p.id === postId);
    if (post) {
        post.likes++;
        localStorage.setItem('posts', JSON.stringify(posts));
        renderPosts();
    }
}

// Cambio de imagen en vista previa al subir archivo
function setupImagePreview(inputId, previewId) {
    const input = document.getElementById(inputId);
    const preview = document.getElementById(previewId);

    input.addEventListener('change', function() {
        preview.innerHTML = '';
        if (this.files && this.files[0]) {
            const img = document.createElement('img');
            img.src = URL.createObjectURL(this.files[0]);
            preview.appendChild(img);
        }
    });
}

// Inicializar la aplicación al cargar la página
window.addEventListener('DOMContentLoaded', initApp);
