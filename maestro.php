<?php
session_start();

// Validar que exista la sesión para proteger la página
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Extraer datos de la sesión o usar un fallback
$nombre_maestro = isset($_SESSION['usuario_nombre']) ? $_SESSION['usuario_nombre'] : "Prof. Alejandro García";
$rol = isset($_SESSION['rol']) ? $_SESSION['rol'] : "Maestro/Prefecto";
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Maestro - Sistema de Incidencias</title>
    
    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Phosphor Icons -->
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="css/global.css">
    <link rel="stylesheet" href="css/index.css">

    <style>
        /* Ajustes específicos para la tabla glassmorphism */
        table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
        }
        th, td {
            padding: 1rem;
            text-align: left;
            border-bottom: 1px solid var(--color-border);
        }
        th {
            font-weight: 500;
            color: var(--color-accent);
            opacity: 0.9;
        }
        tbody tr {
            transition: background-color 0.2s ease;
        }
        tbody tr:hover {
            background-color: rgba(255, 255, 255, 0.05);
        }
    </style>
</head>
<body class="p-6 md:p-10 box-border flex flex-col h-screen overflow-hidden">

    <!-- 1. HEADER -->
    <header class="flex justify-between items-center mb-8 shrink-0">
        <h1 class="text-2xl font-semibold tracking-wide text-white flex items-center gap-3">
            <i class="ph ph-shield-check text-[var(--color-accent)] text-3xl"></i>
            Panel de Maestro
        </h1>
        <div class="flex items-center gap-4 text-sm font-medium">
            <!-- Perfil simulado del maestro -->
            <span class="opacity-90"><?php echo htmlspecialchars($nombre_maestro); ?> &mdash; <span class="opacity-70 font-normal"><?php echo htmlspecialchars($rol); ?></span></span>
            <div class="h-10 w-10 rounded-full border border-[var(--color-border)] flex items-center justify-center bg-[rgba(63,163,180,0.2)]">
                <i class="ph ph-user text-[var(--color-accent)] text-lg"></i>
            </div>
            <!-- Botón Cerrar Sesión -->
            <a href="login.php" class="ml-2 text-xs opacity-70 hover:text-red-400 transition-colors" title="Cerrar sesión"><i class="ph ph-sign-out text-xl"></i></a>
        </div>
    </header>

    <main class="flex-1 overflow-y-auto pr-2 pb-8 flex flex-col gap-6">

        <!-- Fila superior: SECCIÓN 1 (Botón) y SECCIÓN 2 (Buscador) -->
        <div class="flex flex-col md:flex-row gap-6 items-start md:items-center justify-between shrink-0">
            
            <!-- SECCIÓN 1: BOTÓN DE REGISTRO INDEPENDIENTE -->
            <div class="glass-panel p-4 flex items-center shadow-none">
                <button onclick="document.getElementById('modalRegistro').classList.remove('hidden')" class="btn-accent px-6 py-3 flex items-center justify-center gap-3 text-base whitespace-nowrap">
                    <i class="ph ph-plus-circle text-2xl"></i> 
                    Registrar Nueva Incidencia
                </button>
            </div>

            <!-- SECCIÓN 2: BUSCADOR GENERAL -->
            <div class="glass-panel p-4 flex-1 w-full max-w-xl flex items-center gap-3">
                <i class="ph ph-magnifying-glass text-xl text-[var(--color-accent)]"></i>
                <input type="text" placeholder="Buscar incidencias por nombre o matrícula de alumno..." class="bg-transparent border-none outline-none text-white w-full placeholder-gray-400 text-sm">
            </div>

        </div>

        <!-- SECCIÓN 3: TABLA DE REGISTROS -->
        <section class="glass-panel w-full p-6 flex flex-col gap-4 flex-1 overflow-hidden min-h-[400px]">
            <h2 class="text-lg font-medium text-white flex items-center gap-2 mb-2">
                <i class="ph ph-clock-counter-clockwise text-[var(--color-accent)]"></i> Historial de Incidencias
            </h2>
            
            <div class="flex-1 overflow-y-auto">
                <table>
                    <thead>
                        <tr>
                            <th>Folio (#)</th>
                            <th>Alumno</th>
                            <th>Fecha de Registro</th>
                            <th>Categoría</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Datos Simulados -->
                        <tr>
                            <td class="font-mono text-sm opacity-80">INC-0842</td>
                            <td>
                                <p class="font-medium">Juan Pérez Martínez</p>
                                <p class="text-xs opacity-60">MAT8374</p>
                            </td>
                            <td class="text-sm opacity-80">22 Jun 2026, 10:15 AM</td>
                            <td>Disciplina</td>
                            <td><span class="tag-accent text-xs font-semibold px-2 py-1">En Proceso</span></td>
                            <td>
                                <button class="btn-flat px-3 py-1.5 text-xs"><i class="ph ph-eye"></i> Ver</button>
                            </td>
                        </tr>
                        <tr>
                            <td class="font-mono text-sm opacity-80">INC-0841</td>
                            <td>
                                <p class="font-medium">Ana Gómez Silva</p>
                                <p class="text-xs opacity-60">MAT9281</p>
                            </td>
                            <td class="text-sm opacity-80">20 Jun 2026, 14:30 PM</td>
                            <td>Asistencia</td>
                            <td><span class="tag-accent text-xs font-semibold px-2 py-1" style="color: #4ade80; border-color: rgba(74, 222, 128, 0.3); background-color: rgba(74, 222, 128, 0.1);">Resuelto</span></td>
                            <td>
                                <button class="btn-flat px-3 py-1.5 text-xs"><i class="ph ph-eye"></i> Ver</button>
                            </td>
                        </tr>
                        <tr>
                            <td class="font-mono text-sm opacity-80">INC-0835</td>
                            <td>
                                <p class="font-medium">Carlos Ruiz López</p>
                                <p class="text-xs opacity-60">MAT1123</p>
                            </td>
                            <td class="text-sm opacity-80">15 Jun 2026, 09:05 AM</td>
                            <td>Instalaciones</td>
                            <td><span class="tag-accent text-xs font-semibold px-2 py-1" style="color: #f87171; border-color: rgba(248, 113, 113, 0.3); background-color: rgba(248, 113, 113, 0.1);">Pendiente</span></td>
                            <td>
                                <button class="btn-flat px-3 py-1.5 text-xs"><i class="ph ph-eye"></i> Ver</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </section>

    </main>

    <!-- MODAL REGISTRO (Flotante Translúcido) -->
    <div id="modalRegistro" class="fixed inset-0 z-50 flex items-center justify-center hidden bg-[rgba(0,0,0,0.6)] backdrop-blur-sm p-4">
        <div class="glass-panel w-full max-w-2xl p-8 relative max-h-[90vh] overflow-y-auto">
            
            <button onclick="document.getElementById('modalRegistro').classList.add('hidden')" class="absolute top-6 right-6 text-gray-400 hover:text-white transition-colors">
                <i class="ph ph-x text-2xl"></i>
            </button>
            
            <h3 class="text-xl font-medium mb-6 text-[var(--color-accent)] flex items-center gap-2 border-b border-[var(--color-border)] pb-4">
                <i class="ph ph-file-plus"></i> Nueva Incidencia
            </h3>
            
            <form class="flex flex-col gap-6">
                <!-- Buscador Dinámico de Alumno -->
                <div class="flex flex-col gap-2 relative">
                    <label class="text-sm opacity-80">Buscador de Alumno</label>
                    <div class="relative">
                        <i class="ph ph-magnifying-glass absolute left-3 top-3 text-[var(--color-accent)] text-lg"></i>
                        <input type="text" placeholder="Escribe matrícula o nombre..." class="glass-input w-full pl-10 pr-4 py-3 text-sm" value="Juan P">
                        
                        <!-- Dropdown de coincidencias simulado -->
                        <div class="absolute w-full mt-1 glass-panel border border-[var(--color-accent)] rounded-lg overflow-hidden z-10" style="background-color: rgba(14, 20, 49, 0.95);">
                            <div class="p-3 border-b border-[var(--color-border)] hover:bg-[rgba(255,255,255,0.05)] cursor-pointer">
                                <p class="text-sm font-medium">Juan Pérez Martínez <span class="text-xs opacity-60 ml-2">MAT8374</span></p>
                            </div>
                            <div class="p-3 hover:bg-[rgba(255,255,255,0.05)] cursor-pointer">
                                <p class="text-sm font-medium">Juan Pablo Silva <span class="text-xs opacity-60 ml-2">MAT5521</span></p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Ficha de confirmación del alumno seleccionado -->
                <div class="p-4 rounded-lg bg-[rgba(63,163,180,0.1)] border border-[var(--color-border)] flex items-center gap-4">
                    <div class="h-12 w-12 rounded-full border border-[var(--color-border)] bg-[rgba(0,0,0,0.2)] flex items-center justify-center shrink-0">
                        <i class="ph ph-student text-[var(--color-accent)] text-2xl"></i>
                    </div>
                    <div>
                        <p class="font-medium text-white">Juan Pérez Martínez</p>
                        <p class="text-xs opacity-70">Grado: 2do | Grupo: "B" | Matrícula: MAT8374</p>
                    </div>
                </div>

                <!-- Selector de Categoría -->
                <div class="flex flex-col gap-2">
                    <label class="text-sm opacity-80">Categoría</label>
                    <div class="relative">
                        <select class="glass-input w-full pl-4 pr-10 py-3 text-sm appearance-none cursor-pointer">
                            <option value="" disabled selected>Seleccione la categoría...</option>
                            <option value="disciplina">Disciplina</option>
                            <option value="instalaciones">Instalaciones</option>
                            <option value="salud">Salud</option>
                            <option value="asistencia">Asistencia</option>
                        </select>
                        <i class="ph ph-caret-down absolute right-4 top-3.5 text-gray-400"></i>
                    </div>
                </div>

                <!-- Descripción detallada -->
                <div class="flex flex-col gap-2">
                    <label class="text-sm opacity-80">Descripción detallada</label>
                    <textarea class="glass-input w-full px-4 py-3 text-sm min-h-[100px] resize-y" placeholder="Describe claramente lo sucedido..."></textarea>
                </div>

                <!-- Botones Finales -->
                <div class="flex gap-4 mt-2">
                    <button type="button" class="btn-flat flex-1 py-3 flex items-center justify-center gap-2">
                        <i class="ph ph-image text-xl"></i> Adjuntar imagen
                    </button>
                    <button type="button" class="btn-accent flex-1 py-3 flex items-center justify-center gap-2" onclick="document.getElementById('modalRegistro').classList.add('hidden')">
                        <i class="ph ph-floppy-disk text-xl"></i> Guardar Incidencia
                    </button>
                </div>
            </form>
        </div>
    </div>

</body>
</html>
