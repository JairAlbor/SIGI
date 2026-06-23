<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Incidencias - Acceso</title>
    
    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Phosphor Icons -->
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Custom CSS Configuration -->
    <link rel="stylesheet" href="css/global.css">
    <link rel="stylesheet" href="css/login.css">
</head>
<body class="p-4 relative">

    <!-- Notificación de Alerta -->
    <div id="alertBox" class="fixed top-4 right-4 z-50 transform transition-transform duration-300 translate-x-full bg-[rgba(14,20,49,0.9)] border border-red-500 text-white px-4 py-3 rounded-lg shadow-lg flex items-center gap-3">
        <i class="ph ph-warning-circle text-red-500 text-xl"></i>
        <span id="alertMessage" class="text-sm">Error message</span>
        <button onclick="closeAlert()" class="ml-4 opacity-70 hover:opacity-100">
            <i class="ph ph-x"></i>
        </button>
    </div>

    <!-- Contenedor Flip -->
    <div class="flip-container" id="flipContainer">
        <div class="flipper">
            
            <!-- CARA FRONTAL: LOGIN -->
            <div class="front glass-panel p-6 md:p-8 flex flex-col items-center justify-center">
                <div class="w-20 h-20 md:w-24 md:h-24 mb-4 md:mb-6 rounded-full bg-[rgba(63,163,180,0.1)] border border-[var(--color-border)] flex items-center justify-center p-3 overflow-hidden">
                    <img src="images/zorroicon.png" alt="Zorro Logo" class="w-full h-full object-contain drop-shadow-md">
                </div>
                
                <h2 class="text-xl md:text-2xl font-semibold tracking-wide text-white mb-1 md:mb-2">Bienvenido</h2>
                <p class="text-xs md:text-sm opacity-70 mb-4 md:mb-8 text-center">Inicia sesión en el Sistema de Incidencias</p>

                <form id="loginForm" class="w-full flex flex-col gap-4">
                    <div class="flex flex-col gap-1.5 relative">
                        <label class="text-xs opacity-80 pl-1">Identificador o Correo</label>
                        <div class="relative">
                            <i class="ph ph-user absolute left-3 top-3 text-gray-400 text-lg"></i>
                            <input type="text" id="identificador" name="identificador" placeholder="Ej. MAT8374 o correo" class="glass-input w-full pl-10 pr-4 py-2.5 text-sm" required>
                        </div>
                    </div>
                    
                    <div class="flex flex-col gap-1.5 relative">
                        <label class="text-xs opacity-80 pl-1">Contraseña</label>
                        <div class="relative">
                            <i class="ph ph-lock-key absolute left-3 top-3 text-gray-400 text-lg"></i>
                            <input type="password" id="contrasena" name="contrasena" placeholder="••••••••" class="glass-input w-full pl-10 pr-4 py-2.5 text-sm" required>
                        </div>
                    </div>

                    <div class="flex justify-between items-center mt-1">
                        <label class="flex items-center gap-2 text-xs opacity-80 cursor-pointer">
                            <input type="checkbox" class="accent-[var(--color-accent)] cursor-pointer">
                            <span>Recordarme</span>
                        </label>
                        <a href="#" class="text-xs opacity-80 hover:text-[var(--color-accent)] transition-colors">¿Olvidaste tu contraseña?</a>
                    </div>

                    <button type="submit" id="btnLogin" class="btn-accent w-full py-3 mt-4 flex items-center justify-center gap-2">
                        <span>Iniciar Sesión</span> <i class="ph ph-sign-in text-lg"></i>
                    </button>
                </form>

                <div class="mt-6 md:mt-8 text-sm opacity-80 text-center">
                    ¿No tienes cuenta? <span class="text-link font-medium" onclick="toggleFlip()">Regístrate aquí</span>
                </div>
            </div>

            <!-- CARA TRASERA: REGISTRO -->
            <div class="back glass-panel p-6 md:p-8 flex flex-col items-center justify-center">
                <div class="w-16 h-16 md:w-20 md:h-20 mb-2 md:mb-4 rounded-full bg-[rgba(63,163,180,0.1)] border border-[var(--color-border)] flex items-center justify-center p-2 overflow-hidden">
                    <img src="images/zorroicon.png" alt="Zorro Logo" class="w-full h-full object-contain">
                </div>

                <h2 class="text-lg md:text-xl font-semibold tracking-wide text-[var(--color-accent)] mb-0.5 md:mb-1">Crea tu cuenta</h2>
                <p class="text-[11px] md:text-xs opacity-70 mb-3 md:mb-6 text-center">Únete al sistema de gestión escolar</p>

                <!-- El form de registro enviaría a registrar.php o similar -->
                <form id="registerForm" class="w-full flex flex-col gap-3">
                    <div class="flex flex-col gap-1 relative">
                        <div class="relative">
                            <i class="ph ph-identification-card absolute left-3 top-2.5 text-gray-400 text-lg"></i>
                            <input type="text" name="nombre" placeholder="Nombre completo" class="glass-input w-full pl-10 pr-4 py-2 text-sm" required>
                        </div>
                    </div>
                    
                    <div class="flex flex-col gap-1 relative">
                        <div class="relative">
                            <i class="ph ph-user absolute left-3 top-2.5 text-gray-400 text-lg"></i>
                            <input type="text" name="identificador" placeholder="Matrícula / ID Trabajador" class="glass-input w-full pl-10 pr-4 py-2 text-sm" required>
                        </div>
                    </div>

                    <div class="flex flex-col gap-1 relative">
                        <div class="relative">
                            <i class="ph ph-envelope-simple absolute left-3 top-2.5 text-gray-400 text-lg"></i>
                            <input type="email" name="correo" placeholder="Correo electrónico" class="glass-input w-full pl-10 pr-4 py-2 text-sm" required>
                        </div>
                    </div>

                    <div class="flex flex-col gap-1 relative">
                        <select name="rol" class="glass-input w-full px-4 py-2 text-sm appearance-none" style="color-scheme: dark;" required>
                            <option value="" disabled selected>Selecciona tu rol...</option>
                            <option value="maestro">Maestro / Prefecto</option>
                            <option value="servicios_escolares">Servicios Escolares</option>
                            <option value="psicologia">Psicología</option>
                        </select>
                        <i class="ph ph-caret-down absolute right-3 top-2.5 text-gray-400"></i>
                    </div>

                    <div class="flex flex-col gap-1 relative">
                        <div class="relative">
                            <i class="ph ph-lock-key absolute left-3 top-2.5 text-gray-400 text-lg"></i>
                            <input type="password" name="contrasena" placeholder="Contraseña" class="glass-input w-full pl-10 pr-4 py-2 text-sm" required>
                        </div>
                    </div>

                    <button type="submit" class="btn-accent w-full py-2.5 mt-2 flex items-center justify-center gap-2">
                        Completar Registro <i class="ph ph-check-circle text-lg"></i>
                    </button>
                </form>

                <div class="mt-4 md:mt-6 text-sm opacity-80 text-center">
                    ¿Ya tienes cuenta? <span class="text-link font-medium" onclick="toggleFlip()">Inicia Sesión</span>
                </div>
            </div>

        </div>
    </div>

    <!-- Script para voltear la tarjeta y procesar Login AJAX -->
    <script>
        function toggleFlip() {
            const container = document.getElementById('flipContainer');
            container.classList.toggle('flipped');
        }

        function showAlert(msg) {
            const alertBox = document.getElementById('alertBox');
            document.getElementById('alertMessage').innerText = msg;
            alertBox.classList.remove('translate-x-full');
            setTimeout(closeAlert, 5000);
        }

        function closeAlert() {
            document.getElementById('alertBox').classList.add('translate-x-full');
        }

        document.getElementById('loginForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const btn = document.getElementById('btnLogin');
            const originalBtnHtml = btn.innerHTML;
            btn.innerHTML = '<i class="ph ph-spinner animate-spin text-lg"></i> Validando...';
            btn.disabled = true;

            const formData = new FormData(this);

            fetch('CRUD/validarLogin.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                btn.innerHTML = originalBtnHtml;
                btn.disabled = false;
                if (data.status === 'success') {
                    // Redirigir según el script PHP (ej. maestro.php)
                    window.location.href = data.redirect;
                } else {
                    showAlert(data.message || 'Error al iniciar sesión');
                }
            })
            .catch(error => {
                btn.innerHTML = originalBtnHtml;
                btn.disabled = false;
                showAlert('Error de conexión con el servidor.');
                console.error(error);
            });
        });

        // Enviar Registro AJAX
        document.getElementById('registerForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const btn = this.querySelector('button[type="submit"]');
            const originalBtnHtml = btn.innerHTML;
            btn.innerHTML = '<i class="ph ph-spinner animate-spin text-lg"></i> Registrando...';
            btn.disabled = true;

            const formData = new FormData(this);

            fetch('CRUD/insertarUsuario.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                btn.innerHTML = originalBtnHtml;
                btn.disabled = false;
                if (data.status === 'success') {
                    showAlert('¡Registro exitoso! Ya puedes iniciar sesión.');
                    this.reset();
                    // Voltear la tarjeta hacia el login
                    setTimeout(toggleFlip, 1500);
                } else {
                    showAlert(data.message || 'Error al registrar usuario');
                }
            })
            .catch(error => {
                btn.innerHTML = originalBtnHtml;
                btn.disabled = false;
                showAlert('Error de conexión con el servidor.');
                console.error(error);
            });
        });
    </script>
</body>
</html>
