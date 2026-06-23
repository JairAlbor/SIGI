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
            padding: 0.875rem 1rem;
            text-align: left;
            border-bottom: 1px solid var(--color-border);
        }
        th {
            font-weight: 600;
            color: var(--color-accent);
            opacity: 0.9;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        tbody tr {
            transition: background-color 0.2s ease;
        }
        tbody tr:hover {
            background-color: rgba(255, 255, 255, 0.04);
        }
        .tbl-scroll::-webkit-scrollbar { height:6px; width:6px; }
        .tbl-scroll::-webkit-scrollbar-track { background:rgba(14, 20, 49, .3); border-radius:3px; }
        .tbl-scroll::-webkit-scrollbar-thumb { background:rgba(63, 163, 180, .35); border-radius:3px; }
    </style>
</head>
<body class="p-4 md:p-8 box-border flex flex-col h-screen overflow-hidden">

    <!-- 1. HEADER -->
    <header class="flex justify-between items-center mb-6 md:mb-8 shrink-0 gap-2">
        <h1 class="text-lg md:text-2xl font-semibold tracking-wide text-white flex items-center gap-2 shrink-0">
            <i class="ph ph-shield-check text-[var(--color-accent)] text-2xl md:text-3xl"></i>
            <span class="hidden sm:inline">Panel de Maestro</span>
            <span class="sm:hidden">Maestro</span>
        </h1>
        <div class="flex items-center gap-2 md:gap-4 text-xs md:text-sm font-medium ml-auto min-w-0">
            <div class="flex flex-col text-right hidden sm:flex">
                <span class="opacity-90 truncate max-w-[120px] md:max-w-[200px]"><?php echo htmlspecialchars($nombre_maestro); ?></span>
                <span class="opacity-65 text-[10px]"><?php echo htmlspecialchars($rol); ?></span>
            </div>
            <div class="h-8 w-8 md:h-10 md:w-10 rounded-full border border-[var(--color-border)] flex items-center justify-center bg-[rgba(63,163,180,0.2)] shrink-0">
                <i class="ph ph-user text-[var(--color-accent)] text-base md:text-lg"></i>
            </div>
            <a href="login.php" class="text-xs opacity-70 hover:text-red-400 transition-colors shrink-0 p-1 flex items-center gap-1.5" title="Cerrar sesión">
                <i class="ph ph-sign-out text-lg md:text-xl"></i>
            </a>
        </div>
    </header>

    <main class="flex-1 overflow-y-auto pr-1 pb-8 flex flex-col gap-6">

        <!-- Fila superior: SECCIÓN 1 (Botón) y SECCIÓN 2 (Buscador) -->
        <div class="flex flex-col md:flex-row gap-4 md:gap-6 items-stretch md:items-center justify-between shrink-0">
            
            <!-- SECCIÓN 1: BOTÓN DE REGISTRO INDEPENDIENTE -->
            <div class="glass-panel p-3 md:p-4 flex items-center shadow-none w-full md:w-auto">
                <button onclick="document.getElementById('modalRegistro').classList.remove('hidden')" class="btn-accent w-full md:w-auto px-4 md:px-6 py-2.5 md:py-3 flex items-center justify-center gap-2 md:gap-3 text-sm md:text-base whitespace-nowrap">
                    <i class="ph ph-plus-circle text-xl md:text-2xl"></i> 
                    Registrar Nueva Incidencia
                </button>
            </div>

            <!-- SECCIÓN 2: BUSCADOR GENERAL -->
            <div class="glass-panel p-3 md:p-4 flex-1 w-full max-w-xl flex items-center gap-3">
                <i class="ph ph-magnifying-glass text-lg md:text-xl text-[var(--color-accent)]"></i>
                <input type="text" id="buscadorMaestro" placeholder="Buscar por alumno o matrícula..." class="bg-transparent border-none outline-none text-white w-full placeholder-gray-400 text-sm">
            </div>

        </div>

        <!-- SECCIÓN 3: TABLA DE REGISTROS -->
        <section class="glass-panel w-full p-4 md:p-6 flex flex-col gap-4 flex-1 overflow-hidden min-h-[300px]">
            <h2 class="text-base md:text-lg font-medium text-white flex items-center gap-2 mb-1 md:mb-2">
                <i class="ph ph-clock-counter-clockwise text-[var(--color-accent)] text-lg md:text-xl"></i> Historial de Incidencias
            </h2>
            
            <div class="flex-1 overflow-auto tbl-scroll">
                <table class="min-w-[650px] w-full">
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
                                <p class="font-medium text-sm">Juan Pérez Martínez</p>
                                <p class="text-xs opacity-60">MAT8374</p>
                            </td>
                            <td class="text-sm opacity-80">22 Jun 2026, 10:15 AM</td>
                            <td class="text-sm opacity-80">Disciplina</td>
                            <td><span class="tag-accent text-xs font-semibold px-2 py-1">En Proceso</span></td>
                            <td>
                                <button class="btn-flat px-3 py-1.5 text-xs flex items-center gap-1" onclick="verDetalleSimulado('INC-0842', 'Juan Pérez Martínez', 'MAT8374', '22 Jun 2026, 10:15 AM', 'Disciplina', 'En Proceso', 'Reporte por indisciplina recurrente en el aula de cómputo.')"><i class="ph ph-eye"></i> Ver</button>
                            </td>
                        </tr>
                        <tr>
                            <td class="font-mono text-sm opacity-80">INC-0841</td>
                            <td>
                                <p class="font-medium text-sm">Ana Gómez Silva</p>
                                <p class="text-xs opacity-60">MAT9281</p>
                            </td>
                            <td class="text-sm opacity-80">20 Jun 2026, 14:30 PM</td>
                            <td class="text-sm opacity-80">Asistencia</td>
                            <td><span class="tag-accent text-xs font-semibold px-2 py-1" style="color: #4ade80; border-color: rgba(74, 222, 128, 0.3); background-color: rgba(74, 222, 128, 0.1);">Resuelto</span></td>
                            <td>
                                <button class="btn-flat px-3 py-1.5 text-xs flex items-center gap-1" onclick="verDetalleSimulado('INC-0841', 'Ana Gómez Silva', 'MAT9281', '20 Jun 2026, 14:30 PM', 'Asistencia', 'Resuelto', 'Falta injustificada por más de 3 días consecutivos.')"><i class="ph ph-eye"></i> Ver</button>
                            </td>
                        </tr>
                        <tr>
                            <td class="font-mono text-sm opacity-80">INC-0835</td>
                            <td>
                                <p class="font-medium text-sm">Carlos Ruiz López</p>
                                <p class="text-xs opacity-60">MAT1123</p>
                            </td>
                            <td class="text-sm opacity-80">15 Jun 2026, 09:05 AM</td>
                            <td class="text-sm opacity-80">Instalaciones</td>
                            <td><span class="tag-accent text-xs font-semibold px-2 py-1" style="color: #f87171; border-color: rgba(248, 113, 113, 0.3); background-color: rgba(248, 113, 113, 0.1);">Pendiente</span></td>
                            <td>
                                <button class="btn-flat px-3 py-1.5 text-xs flex items-center gap-1" onclick="verDetalleSimulado('INC-0835', 'Carlos Ruiz López', 'MAT1123', '15 Jun 2026, 09:05 AM', 'Instalaciones', 'Pendiente', 'Daños accidentales a mesa de trabajo.')"><i class="ph ph-eye"></i> Ver</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </section>

    </main>

    <!-- ── MODAL NUEVA INCIDENCIA ───────────────────────────────────── -->
    <div id="modalRegistro" class="fixed inset-0 z-50 flex items-center justify-center hidden bg-[rgba(0,0,0,0.6)] backdrop-blur-sm p-2 md:p-4">
        <div class="glass-panel w-full max-w-2xl p-5 md:p-8 relative max-h-[95vh] overflow-y-auto">

            <button onclick="cerrarModalRegistro()" class="absolute top-4 right-4 md:top-6 md:right-6 text-gray-400 hover:text-white transition-colors">
                <i class="ph ph-x text-xl md:text-2xl"></i>
            </button>

            <h3 class="text-lg md:text-xl font-medium mb-4 md:mb-6 text-[var(--color-accent)] flex items-center gap-2 border-b border-[var(--color-border)] pb-3 md:pb-4">
                <i class="ph ph-file-plus"></i> Nueva Incidencia
            </h3>

            <!-- Mensaje de estado -->
            <div id="msgIncidencia" class="hidden mb-4 px-4 py-3 rounded-lg text-sm font-medium"></div>

            <form id="formNuevaIncidencia" class="flex flex-col gap-4" novalidate>
                <!-- Campo oculto con el id_alumno seleccionado -->
                <input type="hidden" id="fi_id_alumno" name="id_alumno" value="">

                <!-- ── SECCIÓN 1: BÚSQUEDA DE ALUMNO ─────────────────────── -->
                <fieldset class="flex flex-col gap-3 p-3 md:p-4 rounded-lg border border-[var(--color-border)] bg-[rgba(255,255,255,0.02)]">
                    <legend class="text-xs opacity-60 px-1 uppercase tracking-wider">Datos del Alumno</legend>

                    <!-- Buscador con dropdown AJAX -->
                    <div class="flex flex-col gap-1.5 relative">
                        <label for="buscadorAlumnosModal" class="text-xs md:text-sm opacity-80">Buscar alumno existente</label>
                        <div class="relative">
                            <i class="ph ph-magnifying-glass absolute left-3 top-3.5 text-[var(--color-accent)] text-lg"></i>
                            <input type="text" id="buscadorAlumnosModal" placeholder="Matrícula o nombre del alumno..." class="glass-input w-full pl-10 pr-4 py-3 text-sm" autocomplete="off">
                            <!-- Spinner de búsqueda -->
                            <i class="ph ph-spinner-gap animate-spin absolute right-3 top-3.5 text-[var(--color-accent)] text-lg hidden" id="spinnerBusqueda"></i>
                        </div>
                        <!-- Dropdown de resultados -->
                        <div id="dropdownAlumnosModal" class="absolute top-full left-0 w-full mt-1 glass-panel border border-[var(--color-accent)] rounded-lg overflow-hidden z-20 hidden" style="background-color: rgba(10, 15, 40, 0.97);">
                        </div>
                    </div>

                    <!-- Ficha del alumno seleccionado -->
                    <div id="fichaAlumno" class="p-3 rounded-lg bg-[rgba(63,163,180,0.08)] border border-[var(--color-border)] flex items-center gap-3">
                        <div class="h-10 w-10 rounded-full border border-[var(--color-border)] bg-[rgba(0,0,0,0.2)] flex items-center justify-center shrink-0">
                            <i class="ph ph-student text-[var(--color-accent)] text-xl"></i>
                        </div>
                        <div class="min-w-0">
                            <p class="font-medium text-white text-sm truncate" id="modalAlumnoNombre">Sin alumno seleccionado</p>
                            <p class="text-[11px] opacity-60" id="modalAlumnoDetalle">Busca o llena los campos manualmente abajo</p>
                        </div>
                    </div>

                    <!-- Campos del alumno (editables / auto-llenables) -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div class="flex flex-col gap-1">
                            <label class="text-xs opacity-70">Matrícula <span class="text-red-400">*</span></label>
                            <input type="text" id="fi_matricula" name="matricula" class="glass-input w-full px-4 py-2.5 text-sm" placeholder="Ej. MAT0001" required>
                        </div>
                        <div class="flex flex-col gap-1">
                            <label class="text-xs opacity-70">Nombre(s) <span class="text-red-400">*</span></label>
                            <input type="text" id="fi_nombre" name="nombre_alumno" class="glass-input w-full px-4 py-2.5 text-sm" placeholder="Nombre(s)" required>
                        </div>
                        <div class="flex flex-col gap-1">
                            <label class="text-xs opacity-70">Apellidos <span class="text-red-400">*</span></label>
                            <input type="text" id="fi_apellidos" name="apellidos" class="glass-input w-full px-4 py-2.5 text-sm" placeholder="Apellidos" required>
                        </div>
                        <div class="flex flex-col gap-1">
                            <label class="text-xs opacity-70">Correo del alumno</label>
                            <input type="email" id="fi_correo" name="correo_alumno" class="glass-input w-full px-4 py-2.5 text-sm" placeholder="correo@escuela.edu.mx">
                        </div>
                        <div class="flex flex-col gap-1">
                            <label class="text-xs opacity-70">Grado <span class="text-red-400">*</span></label>
                            <input type="number" id="fi_grado" name="grado" min="1" max="6" class="glass-input w-full px-4 py-2.5 text-sm" placeholder="Ej. 2">
                        </div>
                        <div class="flex flex-col gap-1">
                            <label class="text-xs opacity-70">Grupo <span class="text-red-400">*</span></label>
                            <input type="text" id="fi_grupo" name="grupo" class="glass-input w-full px-4 py-2.5 text-sm" placeholder="Ej. A">
                        </div>
                        <div class="flex flex-col gap-1 sm:col-span-2">
                            <label class="text-xs opacity-70">Datos del tutor</label>
                            <input type="text" id="fi_tutor" name="datos_tutor" class="glass-input w-full px-4 py-2.5 text-sm" placeholder="Nombre y teléfono del tutor">
                        </div>
                    </div>
                </fieldset>

                <!-- ── SECCIÓN 2: DATOS DE LA INCIDENCIA ─────────────────── -->
                <fieldset class="flex flex-col gap-3 p-3 md:p-4 rounded-lg border border-[var(--color-border)] bg-[rgba(255,255,255,0.02)]">
                    <legend class="text-xs opacity-60 px-1 uppercase tracking-wider">Datos de la Incidencia</legend>

                    <div class="flex flex-col gap-1.5">
                        <label for="fi_tipo" class="text-xs md:text-sm opacity-80">Tipo de incidencia <span class="text-red-400">*</span></label>
                        <div class="relative">
                            <select id="fi_tipo" name="tipo_incidencia" class="glass-input w-full pl-4 pr-10 py-2.5 md:py-3 text-sm appearance-none cursor-pointer" style="color-scheme: dark;" required>
                                <option value="" disabled selected>Seleccione el tipo...</option>
                                <option value="Disciplina">Disciplina</option>
                                <option value="Asistencia">Asistencia</option>
                                <option value="Salud">Salud</option>
                                <option value="Instalaciones">Instalaciones</option>
                                <option value="Acoso escolar">Acoso escolar</option>
                                <option value="Rendimiento académico">Rendimiento académico</option>
                                <option value="Otro">Otro</option>
                            </select>
                            <i class="ph ph-caret-down absolute right-4 top-3.5 text-gray-400 pointer-events-none"></i>
                        </div>
                    </div>

                    <div class="flex flex-col gap-1.5">
                        <label for="fi_descripcion" class="text-xs md:text-sm opacity-80">Descripción detallada <span class="text-red-400">*</span></label>
                        <textarea id="fi_descripcion" name="descripcion" class="glass-input w-full px-4 py-2.5 md:py-3 text-sm min-h-[90px] md:min-h-[120px] resize-y" placeholder="Describe claramente lo sucedido..." required></textarea>
                    </div>

                    <div class="flex flex-col gap-1.5">
                        <label for="fi_evidencia" class="text-xs md:text-sm opacity-80">URL de evidencia (opcional)</label>
                        <input type="url" id="fi_evidencia" name="evidencia_url" class="glass-input w-full px-4 py-2.5 text-sm" placeholder="https://drive.google.com/...">
                    </div>
                </fieldset>

                <!-- ── BOTONES ────────────────────────────────────────────── -->
                <div class="flex flex-col sm:flex-row gap-3 mt-1">
                    <button type="button" id="btnCancelarRegistro" class="btn-flat flex-1 py-2.5 md:py-3 flex items-center justify-center gap-2 text-sm">
                        <i class="ph ph-x-circle text-lg"></i> Cancelar
                    </button>
                    <button type="submit" id="btnGuardarIncidencia" class="btn-accent flex-1 py-2.5 md:py-3 flex items-center justify-center gap-2 text-sm">
                        <i class="ph ph-floppy-disk text-lg"></i> Guardar Incidencia
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL DETALLE SIMULADO -->
    <div id="modalDetalleMaestro" class="fixed inset-0 z-50 flex items-center justify-center hidden bg-[rgba(0,0,0,0.6)] backdrop-blur-sm p-4">
        <div class="glass-panel w-full max-w-md p-6 relative">
            <button onclick="document.getElementById('modalDetalleMaestro').classList.add('hidden')" class="absolute top-4 right-4 text-gray-400 hover:text-white transition-colors">
                <i class="ph ph-x text-xl"></i>
            </button>
            <h3 class="text-base font-semibold mb-1 text-[var(--color-accent)] flex items-center gap-2">
                <i class="ph ph-file-text"></i> Detalle — <span id="detMaestroId" class="font-mono text-sm"></span>
            </h3>
            <p class="text-xs opacity-40 mb-4 pb-3 border-b border-[var(--color-border)]">Información completa de la incidencia.</p>
            
            <div id="detMaestroContenido" class="flex flex-col gap-3 text-sm text-white">
                <!-- Se llena dinámicamente -->
            </div>
            
            <button onclick="document.getElementById('modalDetalleMaestro').classList.add('hidden')" class="btn-accent w-full py-2.5 mt-6 text-xs flex items-center justify-center gap-1.5">
                <i class="ph ph-x"></i> Cerrar
            </button>
        </div>
    </div>

    <script>
        // ── Buscador local en la tabla ────────────────────────────────────────
        document.getElementById('buscadorMaestro').addEventListener('input', function() {
            const query = this.value.toLowerCase().trim();
            document.querySelectorAll('tbody tr').forEach(row => {
                row.style.display = row.textContent.toLowerCase().includes(query) ? '' : 'none';
            });
        });

        // ── Variables del modal ───────────────────────────────────────────────
        const busqModal  = document.getElementById('buscadorAlumnosModal');
        const dropModal  = document.getElementById('dropdownAlumnosModal');
        const spinnerBusq = document.getElementById('spinnerBusqueda');
        let searchTimer  = null;

        function cerrarModalRegistro() {
            document.getElementById('modalRegistro').classList.add('hidden');
            resetFormIncidencia();
        }

        document.getElementById('btnCancelarRegistro').addEventListener('click', cerrarModalRegistro);

        // Cerrar dropdown al hacer clic fuera
        document.addEventListener('click', (e) => {
            if (!busqModal.contains(e.target) && !dropModal.contains(e.target)) {
                dropModal.classList.add('hidden');
            }
        });

        // ── Limpiar el formulario ─────────────────────────────────────────────
        function resetFormIncidencia() {
            document.getElementById('formNuevaIncidencia').reset();
            document.getElementById('fi_id_alumno').value = '';
            document.getElementById('modalAlumnoNombre').textContent = 'Sin alumno seleccionado';
            document.getElementById('modalAlumnoDetalle').textContent = 'Busca o llena los campos manualmente abajo';
            dropModal.classList.add('hidden');
            ocultarMsg();
        }

        // ── Mensajes de estado ────────────────────────────────────────────────
        function mostrarMsg(texto, tipo) {
            const el = document.getElementById('msgIncidencia');
            el.textContent = texto;
            el.className = `mb-4 px-4 py-3 rounded-lg text-sm font-medium ${tipo === 'success'
                ? 'bg-[rgba(52,211,153,0.15)] border border-[rgba(52,211,153,0.4)] text-green-300'
                : 'bg-[rgba(248,113,113,0.15)] border border-[rgba(248,113,113,0.4)] text-red-300'}`;
            el.classList.remove('hidden');
        }
        function ocultarMsg() {
            document.getElementById('msgIncidencia').classList.add('hidden');
        }

        // ── Autocompletar campos del alumno ───────────────────────────────────
        function rellenarFichaAlumno(a) {
            document.getElementById('fi_id_alumno').value   = a.id_alumno;
            document.getElementById('fi_matricula').value   = a.matricula;
            document.getElementById('fi_nombre').value      = a.nombre;
            document.getElementById('fi_apellidos').value   = a.apellidos;
            document.getElementById('fi_grado').value       = a.grado;
            document.getElementById('fi_grupo').value       = a.grupo;
            document.getElementById('fi_correo').value      = a.correo_alumno;
            document.getElementById('fi_tutor').value       = a.datos_tutor;
            document.getElementById('modalAlumnoNombre').textContent  = `${a.nombre} ${a.apellidos}`;
            document.getElementById('modalAlumnoDetalle').textContent = `Grado: ${a.grado} | Grupo: ${a.grupo} | Matrícula: ${a.matricula}`;
            busqModal.value = `${a.nombre} ${a.apellidos}`;
            dropModal.classList.add('hidden');
        }

        // ── Búsqueda AJAX de alumnos ──────────────────────────────────────────
        busqModal.addEventListener('input', function() {
            clearTimeout(searchTimer);
            const q = this.value.trim();
            if (q.length < 2) { dropModal.classList.add('hidden'); return; }

            spinnerBusq.classList.remove('hidden');
            searchTimer = setTimeout(() => {
                fetch(`CRUD/buscarAlumno.php?q=${encodeURIComponent(q)}`)
                    .then(r => r.json())
                    .then(data => {
                        spinnerBusq.classList.add('hidden');
                        dropModal.innerHTML = '';
                        if (!Array.isArray(data) || data.length === 0) {
                            dropModal.innerHTML = '<p class="p-3 text-xs opacity-50 text-center">Sin resultados. Llena los campos manualmente.</p>';
                        } else {
                            data.forEach(a => {
                                const item = document.createElement('div');
                                item.className = 'p-3 border-b border-[var(--color-border)] hover:bg-[rgba(255,255,255,0.06)] cursor-pointer transition-colors';
                                item.innerHTML = `<p class="text-sm font-medium">${a.nombre} ${a.apellidos} <span class="text-xs opacity-50 ml-2">${a.matricula}</span></p>
                                    <p class="text-[11px] opacity-50">${a.grado}° grado, Grupo ${a.grupo}</p>`;
                                item.addEventListener('click', () => rellenarFichaAlumno(a));
                                dropModal.appendChild(item);
                            });
                        }
                        dropModal.classList.remove('hidden');
                    })
                    .catch(() => { spinnerBusq.classList.add('hidden'); });
            }, 350);
        });

        // ── Envío del formulario ──────────────────────────────────────────────
        document.getElementById('formNuevaIncidencia').addEventListener('submit', function(e) {
            e.preventDefault();
            const btn = document.getElementById('btnGuardarIncidencia');
            btn.disabled = true;
            btn.innerHTML = '<i class="ph ph-spinner-gap animate-spin text-lg"></i> Guardando...';
            ocultarMsg();

            const data = new FormData(this);

            fetch('CRUD/insertarIncidencia.php', { method: 'POST', body: data })
                .then(r => r.json())
                .then(res => {
                    if (res.status === 'success') {
                        mostrarMsg(`✓ ${res.message}`, 'success');
                        setTimeout(() => cerrarModalRegistro(), 2200);
                    } else {
                        mostrarMsg(res.message || 'Error desconocido.', 'error');
                    }
                })
                .catch(() => mostrarMsg('Error de conexión al servidor.', 'error'))
                .finally(() => {
                    btn.disabled = false;
                    btn.innerHTML = '<i class="ph ph-floppy-disk text-lg"></i> Guardar Incidencia';
                });
        });

        // ── Ver detalle de incidencia ─────────────────────────────────────────
        function verDetalleSimulado(id, nombre, matricula, fecha, categoria, estado, descripcion) {
            document.getElementById('detMaestroId').textContent = id;
            document.getElementById('detMaestroContenido').innerHTML = `
                <div class="flex justify-between py-1 border-b border-[rgba(255,255,255,0.05)]">
                    <span class="opacity-50 text-xs">Alumno:</span>
                    <span class="font-medium text-xs text-right">${nombre} (${matricula})</span>
                </div>
                <div class="flex justify-between py-1 border-b border-[rgba(255,255,255,0.05)]">
                    <span class="opacity-50 text-xs">Fecha:</span>
                    <span class="font-medium text-xs">${fecha}</span>
                </div>
                <div class="flex justify-between py-1 border-b border-[rgba(255,255,255,0.05)]">
                    <span class="opacity-50 text-xs">Categoría:</span>
                    <span class="font-medium text-xs">${categoria}</span>
                </div>
                <div class="flex justify-between py-1 border-b border-[rgba(255,255,255,0.05)]">
                    <span class="opacity-50 text-xs">Estado:</span>
                    <span class="font-medium text-xs text-[var(--color-accent)]">${estado}</span>
                </div>
                <div class="flex flex-col gap-1 py-1">
                    <span class="opacity-50 text-xs">Descripción:</span>
                    <p class="text-xs leading-relaxed opacity-95 bg-[rgba(0,0,0,0.2)] p-2.5 rounded border border-[var(--color-border)]">${descripcion}</p>
                </div>
            `;
            document.getElementById('modalDetalleMaestro').classList.remove('hidden');
        }
    </script>
</body>
</html>
