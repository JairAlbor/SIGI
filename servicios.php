<?php
session_start();
if (!isset($_SESSION['user_id'])) { header("Location: login.php"); exit(); }
$nombre_usuario = isset($_SESSION['usuario_nombre']) ? $_SESSION['usuario_nombre'] : "Coordinador Escolar";
$rol = isset($_SESSION['rol']) ? $_SESSION['rol'] : "servicios_escolares";
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Servicios Escolares - Sistema de Incidencias</title>
    <meta name="description" content="Panel de Servicios Escolares para gestión y seguimiento de incidencias.">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="css/global.css">
    <link rel="stylesheet" href="css/index.css">
    <style>
        table { width:100%; border-collapse:separate; border-spacing:0; }
        th, td { padding:.875rem 1rem; text-align:left; border-bottom:1px solid var(--color-border); }
        th { font-weight:600; font-size:.7rem; text-transform:uppercase; letter-spacing:.08em; color:var(--color-accent); opacity:.85; }
        tbody tr { transition:background-color .2s ease; }
        tbody tr:hover { background-color:rgba(255,255,255,.04); }
        tbody tr:last-child td { border-bottom:none; }

        .badge { display:inline-flex; align-items:center; gap:.3rem; padding:.25rem .65rem; border-radius:999px; font-size:.72rem; font-weight:600; white-space:nowrap; }
        .badge-abierta  { background:rgba(96,165,250,.15); color:#60a5fa; border:1px solid rgba(96,165,250,.3); }
        .badge-proceso  { background:rgba(167,139,250,.15); color:#a78bfa; border:1px solid rgba(167,139,250,.3); }
        .badge-resuelta { background:rgba(74,222,128,.15); color:#4ade80; border:1px solid rgba(74,222,128,.3); }
        .badge-critica  { background:rgba(248,113,113,.15); color:#f87171; border:1px solid rgba(248,113,113,.3); }
        .badge-media    { background:rgba(251,191,36,.15); color:#fbbf24; border:1px solid rgba(251,191,36,.3); }
        .badge-alta     { background:rgba(251,146,60,.15); color:#fb923c; border:1px solid rgba(251,146,60,.3); }
        .badge-baja     { background:rgba(148,163,184,.15); color:#94a3b8; border:1px solid rgba(148,163,184,.3); }

        .pill { padding:.3rem .85rem; border-radius:999px; font-size:.78rem; font-weight:500; cursor:pointer; border:1px solid var(--color-border); background:transparent; color:rgba(248,250,252,.65); transition:all .2s ease; }
        .pill:hover { background:rgba(255,255,255,.06); color:#f8fafc; }
        .pill.active { background:#f8fafc; color:#0e1431; border-color:#f8fafc; font-weight:600; }

        .stat-card { flex:1; min-width:130px; }
        .tbl-scroll::-webkit-scrollbar { height:6px; width:6px; }
        .tbl-scroll::-webkit-scrollbar-track { background:rgba(14,20,49,.3); border-radius:3px; }
        .tbl-scroll::-webkit-scrollbar-thumb { background:rgba(63,163,180,.35); border-radius:3px; }
    </style>
</head>
<body class="p-4 md:p-6 box-border flex flex-col h-screen overflow-hidden">

    <!-- HEADER -->
    <header class="flex flex-col sm:flex-row justify-between items-stretch sm:items-center mb-5 shrink-0 gap-3">
        <div class="flex justify-between items-center w-full sm:w-auto">
            <div>
                <h1 class="text-lg md:text-xl font-semibold tracking-wide text-white flex items-center gap-2 md:gap-2.5">
                    <i class="ph ph-shield-check text-[var(--color-accent)] text-2xl"></i>
                    Servicios Escolares
                </h1>
                <p class="text-[10px] md:text-xs opacity-50 mt-0.5 pl-7 md:pl-8">Gestión y seguimiento</p>
            </div>
            <!-- Notification bell & user icon visible on mobile header row right side -->
            <div class="flex items-center gap-2 sm:hidden">
                <button class="relative w-8 h-8 rounded-full border border-[var(--color-border)] flex items-center justify-center hover:bg-[rgba(255,255,255,0.07)]">
                    <i class="ph ph-bell text-base text-white"></i>
                    <span class="absolute top-0.5 right-0.5 w-1.5 h-1.5 rounded-full bg-[var(--color-accent)]"></span>
                </button>
                <div class="w-8 h-8 rounded-full border border-[var(--color-border)] bg-[rgba(63,163,180,0.25)] flex items-center justify-center">
                    <i class="ph ph-user text-[var(--color-accent)] text-sm"></i>
                </div>
                <a href="login.php" class="text-xs opacity-50 p-1 hover:text-red-400" title="Cerrar sesión">
                    <i class="ph ph-sign-out text-lg"></i>
                </a>
            </div>
        </div>
        
        <div class="flex items-center gap-2.5 justify-between sm:justify-end">
            <!-- Full Profile display shown only on sm+ screen width -->
            <div class="hidden sm:flex items-center gap-2">
                <button id="btnNotif" class="relative w-9 h-9 rounded-full border border-[var(--color-border)] flex items-center justify-center hover:bg-[rgba(255,255,255,0.07)] transition-colors">
                    <i class="ph ph-bell text-lg text-white"></i>
                    <span class="absolute top-1 right-1 w-2 h-2 rounded-full bg-[var(--color-accent)]"></span>
                </button>
                <div class="flex items-center gap-2 px-3 py-1.5 rounded-lg border border-[var(--color-border)] bg-[rgba(0,0,0,0.2)]">
                    <div class="w-7 h-7 rounded-full bg-[rgba(63,163,180,0.25)] flex items-center justify-center">
                        <i class="ph ph-user text-[var(--color-accent)] text-sm"></i>
                    </div>
                    <span class="text-sm opacity-85 truncate max-w-[100px]"><?php echo htmlspecialchars($nombre_usuario); ?></span>
                </div>
            </div>
            
            <button id="btnNuevaIncidencia" onclick="document.getElementById('modalNueva').classList.remove('hidden')"
                class="btn-accent flex-1 sm:flex-none px-4 py-2.5 flex items-center justify-center gap-2 text-xs md:text-sm font-semibold">
                <i class="ph ph-plus text-sm md:text-base"></i> Nueva Incidencia
            </button>
            
            <a href="login.php" class="hidden sm:inline-flex text-xs opacity-50 hover:text-red-400 hover:opacity-100 transition-all p-1" title="Cerrar sesión">
                <i class="ph ph-sign-out text-xl"></i>
            </a>
        </div>
    </header>

    <main class="flex-1 overflow-y-auto pb-8 flex flex-col gap-5 tbl-scroll">

        <!-- TARJETAS ESTADÍSTICAS -->
        <div class="grid grid-cols-2 sm:grid-cols-5 gap-3 md:gap-4 shrink-0">
            <div class="glass-panel p-3 md:p-4 flex items-center gap-3">
                <div class="w-9 h-9 md:w-10 md:h-10 rounded-lg bg-[rgba(248,250,252,0.08)] flex items-center justify-center shrink-0">
                    <i class="ph ph-file-text text-lg md:text-xl text-white opacity-70"></i>
                </div>
                <div><p class="text-xl md:text-2xl font-bold text-white leading-none">10</p><p class="text-[10px] md:text-xs opacity-55 mt-1">Total</p></div>
            </div>
            <div class="glass-panel p-3 md:p-4 flex items-center gap-3">
                <div class="w-9 h-9 md:w-10 md:h-10 rounded-lg bg-[rgba(96,165,250,0.12)] flex items-center justify-center shrink-0">
                    <i class="ph ph-circle-dashed text-lg md:text-xl text-blue-400"></i>
                </div>
                <div><p class="text-xl md:text-2xl font-bold text-white leading-none">4</p><p class="text-[10px] md:text-xs opacity-55 mt-1">Abiertas</p></div>
            </div>
            <div class="glass-panel p-3 md:p-4 flex items-center gap-3">
                <div class="w-9 h-9 md:w-10 md:h-10 rounded-lg bg-[rgba(167,139,250,0.12)] flex items-center justify-center shrink-0">
                    <i class="ph ph-arrows-clockwise text-lg md:text-xl text-violet-400"></i>
                </div>
                <div><p class="text-xl md:text-2xl font-bold text-white leading-none">4</p><p class="text-[10px] md:text-xs opacity-55 mt-1">En Proceso</p></div>
            </div>
            <div class="glass-panel p-3 md:p-4 flex items-center gap-3">
                <div class="w-9 h-9 md:w-10 md:h-10 rounded-lg bg-[rgba(74,222,128,0.12)] flex items-center justify-center shrink-0">
                    <i class="ph ph-check-circle text-lg md:text-xl text-green-400"></i>
                </div>
                <div><p class="text-xl md:text-2xl font-bold text-white leading-none">2</p><p class="text-[10px] md:text-xs opacity-55 mt-1">Resueltas</p></div>
            </div>
            <div class="glass-panel p-3 md:p-4 flex items-center gap-3 col-span-2 sm:col-span-1">
                <div class="w-9 h-9 md:w-10 md:h-10 rounded-lg bg-[rgba(248,113,113,0.12)] flex items-center justify-center shrink-0">
                    <i class="ph ph-warning text-lg md:text-xl text-red-400"></i>
                </div>
                <div><p class="text-xl md:text-2xl font-bold text-white leading-none">2</p><p class="text-[10px] md:text-xs opacity-55 mt-1">Críticas</p></div>
            </div>
        </div>

        <!-- FILTROS Y BUSCADOR -->
        <div class="glass-panel p-4 flex flex-col gap-3 shrink-0">
            <div class="flex flex-col lg:flex-row gap-3 items-stretch lg:items-center">
                <div class="relative flex-1 min-w-0">
                    <i class="ph ph-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-base"></i>
                    <input id="inputBuscar" type="text" placeholder="Buscar por título, ID o reportante..."
                        class="glass-input w-full pl-9 pr-4 py-2 text-sm" oninput="filtrarTabla()">
                </div>
                <div class="flex items-center gap-1.5 overflow-x-auto w-full lg:w-auto pb-1.5 lg:pb-0 scroll-smooth scrollbar-thin">
                    <span class="text-xs opacity-50 flex items-center gap-1 mr-1 shrink-0"><i class="ph ph-funnel text-sm"></i> Estado:</span>
                    <button class="pill active shrink-0" onclick="setFiltro(this,'estado','todas')" id="estadoTodas">Todas</button>
                    <button class="pill shrink-0" onclick="setFiltro(this,'estado','abierta')" id="estadoAbierta">Abierta</button>
                    <button class="pill shrink-0" onclick="setFiltro(this,'estado','proceso')" id="estadoProceso">En Proceso</button>
                    <button class="pill shrink-0" onclick="setFiltro(this,'estado','resuelta')" id="estadoResuelta">Resuelta</button>
                </div>
            </div>
            <div class="flex flex-col lg:flex-row gap-3 items-stretch lg:items-center justify-between">
                <div class="flex items-center gap-1.5 overflow-x-auto w-full lg:w-auto pb-1.5 lg:pb-0 scroll-smooth scrollbar-thin">
                    <span class="text-xs opacity-50 mr-1 shrink-0">Prioridad:</span>
                    <button class="pill active shrink-0" onclick="setFiltro(this,'prioridad','todas')" id="prioTodas">Todas</button>
                    <button class="pill shrink-0" onclick="setFiltro(this,'prioridad','critica')" id="prioCritica">Crítica</button>
                    <button class="pill shrink-0" onclick="setFiltro(this,'prioridad','alta')" id="prioAlta">Alta</button>
                    <button class="pill shrink-0" onclick="setFiltro(this,'prioridad','media')" id="prioMedia">Media</button>
                    <button class="pill shrink-0" onclick="setFiltro(this,'prioridad','baja')" id="prioBaja">Baja</button>
                    <div class="relative ml-1 shrink-0">
                        <select id="selectCategoria" onchange="filtrarTabla()"
                            class="glass-input pl-3 pr-7 py-1.5 text-xs appearance-none cursor-pointer" style="color-scheme:dark;">
                            <option value="">Todas las categorías</option>
                            <option value="Disciplina">Disciplina</option>
                            <option value="Seguridad">Seguridad</option>
                            <option value="Infraestructura">Infraestructura</option>
                            <option value="Asistencia">Asistencia</option>
                            <option value="Salud">Salud</option>
                        </select>
                        <i class="ph ph-caret-down absolute right-2 top-1/2 -translate-y-1/2 text-gray-400 text-xs pointer-events-none"></i>
                    </div>
                </div>
                <button id="btnExportar" class="btn-flat px-4 py-2 flex items-center justify-center gap-2 text-xs w-full lg:w-auto mt-1 lg:mt-0">
                    <i class="ph ph-download-simple text-base"></i> Exportar
                </button>
            </div>
        </div>

        <!-- TABLA DE INCIDENCIAS -->
        <section class="glass-panel flex flex-col overflow-hidden flex-1 min-h-[300px]">
            <div class="flex items-center justify-between px-5 py-3 border-b border-[var(--color-border)] shrink-0">
                <p id="contadorIncidencias" class="text-sm opacity-60">10 incidencias encontradas</p>
                <button id="btnOrdenar" class="text-xs opacity-55 hover:opacity-90 flex items-center gap-1 transition-opacity">
                    <i class="ph ph-arrows-down-up text-sm"></i> Ordenar
                </button>
            </div>
            <div class="overflow-auto tbl-scroll flex-1">
                <table id="tablaIncidencias" class="min-w-[850px] w-full">
                    <thead>
                        <tr>
                            <th class="pl-5">ID</th>
                            <th>TÍTULO</th>
                            <th>CATEGORÍA</th>
                            <th>GRUPO</th>
                            <th>REPORTANTE</th>
                            <th>PRIORIDAD</th>
                            <th>ESTADO</th>
                            <th class="pr-5 text-right">ACCIONES</th>
                        </tr>
                    </thead>
                    <tbody id="tbodyIncidencias">
                        <tr data-estado="abierta" data-prioridad="alta" data-categoria="Disciplina">
                            <td class="pl-5 font-mono text-xs font-bold opacity-80">INC-001</td>
                            <td><p class="text-sm font-medium">Pelea entre alumnos en p...</p></td>
                            <td class="text-sm opacity-65">Disciplina</td><td class="text-sm opacity-65">3°A</td>
                            <td class="text-sm opacity-75">Prof. María González</td>
                            <td><span class="badge badge-alta"><span class="w-1.5 h-1.5 rounded-full bg-[#fb923c] inline-block"></span> Alta</span></td>
                            <td><span class="badge badge-abierta"><span class="w-1.5 h-1.5 rounded-full bg-[#60a5fa] inline-block"></span> Abierta</span></td>
                            <td class="pr-5 text-right"><button class="btn-flat px-3 py-1 text-xs" onclick="verDetalle('INC-001')"><i class="ph ph-eye"></i> Ver</button></td>
                        </tr>
                        <tr data-estado="proceso" data-prioridad="critica" data-categoria="Seguridad">
                            <td class="pl-5 font-mono text-xs font-bold opacity-80">INC-002</td>
                            <td><p class="text-sm font-medium">Alumno con posible susta...</p></td>
                            <td class="text-sm opacity-65">Seguridad</td><td class="text-sm opacity-65">2°B</td>
                            <td class="text-sm opacity-75">Pref. Carlos Ruiz</td>
                            <td><span class="badge badge-critica"><span class="w-1.5 h-1.5 rounded-full bg-[#f87171] inline-block"></span> Crítica</span></td>
                            <td><span class="badge badge-proceso"><i class="ph ph-arrows-clockwise text-[0.65rem]"></i> En Proceso</span></td>
                            <td class="pr-5 text-right"><button class="btn-flat px-3 py-1 text-xs" onclick="verDetalle('INC-002')"><i class="ph ph-eye"></i> Ver</button></td>
                        </tr>
                        <tr data-estado="resuelta" data-prioridad="media" data-categoria="Infraestructura">
                            <td class="pl-5 font-mono text-xs font-bold opacity-80">INC-003</td>
                            <td><p class="text-sm font-medium">Daños a mobiliario en aul...</p></td>
                            <td class="text-sm opacity-65">Infraestructura</td><td class="text-sm opacity-65">1°C</td>
                            <td class="text-sm opacity-75">Prof. Patricia Vega</td>
                            <td><span class="badge badge-media"><span class="w-1.5 h-1.5 rounded-full bg-[#fbbf24] inline-block"></span> Media</span></td>
                            <td><span class="badge badge-resuelta"><i class="ph ph-check-circle text-[0.65rem]"></i> Resuelta</span></td>
                            <td class="pr-5 text-right"><button class="btn-flat px-3 py-1 text-xs" onclick="verDetalle('INC-003')"><i class="ph ph-eye"></i> Ver</button></td>
                        </tr>
                        <tr data-estado="abierta" data-prioridad="baja" data-categoria="Asistencia">
                            <td class="pl-5 font-mono text-xs font-bold opacity-80">INC-004</td>
                            <td><p class="text-sm font-medium">Alumno con múltiples falt...</p></td>
                            <td class="text-sm opacity-65">Asistencia</td><td class="text-sm opacity-65">3°B</td>
                            <td class="text-sm opacity-75">Prof. Luis Hernández</td>
                            <td><span class="badge badge-baja"><span class="w-1.5 h-1.5 rounded-full bg-[#94a3b8] inline-block"></span> Baja</span></td>
                            <td><span class="badge badge-abierta"><span class="w-1.5 h-1.5 rounded-full bg-[#60a5fa] inline-block"></span> Abierta</span></td>
                            <td class="pr-5 text-right"><button class="btn-flat px-3 py-1 text-xs" onclick="verDetalle('INC-004')"><i class="ph ph-eye"></i> Ver</button></td>
                        </tr>
                        <tr data-estado="proceso" data-prioridad="alta" data-categoria="Salud">
                            <td class="pl-5 font-mono text-xs font-bold opacity-80">INC-005</td>
                            <td><p class="text-sm font-medium">Alumno con síntomas de i...</p></td>
                            <td class="text-sm opacity-65">Salud</td><td class="text-sm opacity-65">2°A</td>
                            <td class="text-sm opacity-75">Pref. Ana Castillo</td>
                            <td><span class="badge badge-alta"><span class="w-1.5 h-1.5 rounded-full bg-[#fb923c] inline-block"></span> Alta</span></td>
                            <td><span class="badge badge-proceso"><i class="ph ph-arrows-clockwise text-[0.65rem]"></i> En Proceso</span></td>
                            <td class="pr-5 text-right"><button class="btn-flat px-3 py-1 text-xs" onclick="verDetalle('INC-005')"><i class="ph ph-eye"></i> Ver</button></td>
                        </tr>
                        <tr data-estado="resuelta" data-prioridad="media" data-categoria="Disciplina">
                            <td class="pl-5 font-mono text-xs font-bold opacity-80">INC-006</td>
                            <td><p class="text-sm font-medium">Confrontación verbal entr...</p></td>
                            <td class="text-sm opacity-65">Disciplina</td><td class="text-sm opacity-65">1°A</td>
                            <td class="text-sm opacity-75">Prof. Roberto Díaz</td>
                            <td><span class="badge badge-media"><span class="w-1.5 h-1.5 rounded-full bg-[#fbbf24] inline-block"></span> Media</span></td>
                            <td><span class="badge badge-resuelta"><i class="ph ph-check-circle text-[0.65rem]"></i> Resuelta</span></td>
                            <td class="pr-5 text-right"><button class="btn-flat px-3 py-1 text-xs" onclick="verDetalle('INC-006')"><i class="ph ph-eye"></i> Ver</button></td>
                        </tr>
                        <tr data-estado="abierta" data-prioridad="alta" data-categoria="Seguridad">
                            <td class="pl-5 font-mono text-xs font-bold opacity-80">INC-007</td>
                            <td><p class="text-sm font-medium">Objeto peligroso encontra...</p></td>
                            <td class="text-sm opacity-65">Seguridad</td><td class="text-sm opacity-65">—</td>
                            <td class="text-sm opacity-75">Pref. Mónica Torres</td>
                            <td><span class="badge badge-alta"><span class="w-1.5 h-1.5 rounded-full bg-[#fb923c] inline-block"></span> Alta</span></td>
                            <td><span class="badge badge-abierta"><span class="w-1.5 h-1.5 rounded-full bg-[#60a5fa] inline-block"></span> Abierta</span></td>
                            <td class="pr-5 text-right"><button class="btn-flat px-3 py-1 text-xs" onclick="verDetalle('INC-007')"><i class="ph ph-eye"></i> Ver</button></td>
                        </tr>
                        <tr data-estado="proceso" data-prioridad="critica" data-categoria="Seguridad">
                            <td class="pl-5 font-mono text-xs font-bold opacity-80">INC-008</td>
                            <td><p class="text-sm font-medium">Acoso escolar reportado p...</p></td>
                            <td class="text-sm opacity-65">Seguridad</td><td class="text-sm opacity-65">2°C</td>
                            <td class="text-sm opacity-75">Prof. Silvia Mora</td>
                            <td><span class="badge badge-critica"><span class="w-1.5 h-1.5 rounded-full bg-[#f87171] inline-block"></span> Crítica</span></td>
                            <td><span class="badge badge-proceso"><i class="ph ph-arrows-clockwise text-[0.65rem]"></i> En Proceso</span></td>
                            <td class="pr-5 text-right"><button class="btn-flat px-3 py-1 text-xs" onclick="verDetalle('INC-008')"><i class="ph ph-eye"></i> Ver</button></td>
                        </tr>
                        <tr data-estado="abierta" data-prioridad="baja" data-categoria="Infraestructura">
                            <td class="pl-5 font-mono text-xs font-bold opacity-80">INC-009</td>
                            <td><p class="text-sm font-medium">Ventana rota en salón de...</p></td>
                            <td class="text-sm opacity-65">Infraestructura</td><td class="text-sm opacity-65">—</td>
                            <td class="text-sm opacity-75">Prof. Jorge Ríos</td>
                            <td><span class="badge badge-baja"><span class="w-1.5 h-1.5 rounded-full bg-[#94a3b8] inline-block"></span> Baja</span></td>
                            <td><span class="badge badge-abierta"><span class="w-1.5 h-1.5 rounded-full bg-[#60a5fa] inline-block"></span> Abierta</span></td>
                            <td class="pr-5 text-right"><button class="btn-flat px-3 py-1 text-xs" onclick="verDetalle('INC-009')"><i class="ph ph-eye"></i> Ver</button></td>
                        </tr>
                        <tr data-estado="proceso" data-prioridad="media" data-categoria="Asistencia">
                            <td class="pl-5 font-mono text-xs font-bold opacity-80">INC-010</td>
                            <td><p class="text-sm font-medium">Alumno sin tutor registra...</p></td>
                            <td class="text-sm opacity-65">Asistencia</td><td class="text-sm opacity-65">1°B</td>
                            <td class="text-sm opacity-75">Prof. Elena Fuentes</td>
                            <td><span class="badge badge-media"><span class="w-1.5 h-1.5 rounded-full bg-[#fbbf24] inline-block"></span> Media</span></td>
                            <td><span class="badge badge-proceso"><i class="ph ph-arrows-clockwise text-[0.65rem]"></i> En Proceso</span></td>
                            <td class="pr-5 text-right"><button class="btn-flat px-3 py-1 text-xs" onclick="verDetalle('INC-010')"><i class="ph ph-eye"></i> Ver</button></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </section>

    </main>

    <!-- MODAL NUEVA INCIDENCIA -->
    <div id="modalNueva" class="fixed inset-0 z-50 flex items-center justify-center hidden bg-[rgba(0,0,0,0.65)] backdrop-blur-sm p-2 md:p-4">
        <div class="glass-panel w-full max-w-xl p-5 md:p-8 relative max-h-[95vh] overflow-y-auto tbl-scroll">
            <button onclick="document.getElementById('modalNueva').classList.add('hidden')" class="absolute top-5 right-5 text-gray-400 hover:text-white transition-colors"><i class="ph ph-x text-xl"></i></button>
            <h3 class="text-lg font-semibold mb-1 text-[var(--color-accent)] flex items-center gap-2"><i class="ph ph-file-plus"></i> Nueva Incidencia</h3>
            <p class="text-xs opacity-50 mb-6 border-b border-[var(--color-border)] pb-4">Completa la información para registrar la incidencia.</p>
            <form class="flex flex-col gap-4">
                <div class="flex flex-col gap-1.5">
                    <label class="text-xs opacity-70">Título de la Incidencia</label>
                    <input type="text" placeholder="Ej. Pelea entre alumnos en pasillo..." class="glass-input w-full px-4 py-2.5 text-sm">
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="flex flex-col gap-1.5">
                        <label class="text-xs opacity-70">Categoría</label>
                        <div class="relative">
                            <select class="glass-input w-full pl-4 pr-8 py-2.5 text-sm appearance-none cursor-pointer" style="color-scheme:dark;">
                                <option value="" disabled selected>Seleccionar...</option>
                                <option>Disciplina</option><option>Seguridad</option><option>Infraestructura</option><option>Asistencia</option><option>Salud</option>
                            </select>
                            <i class="ph ph-caret-down absolute right-3 top-3 text-gray-400 text-xs pointer-events-none"></i>
                        </div>
                    </div>
                    <div class="flex flex-col gap-1.5">
                        <label class="text-xs opacity-70">Prioridad</label>
                        <div class="relative">
                            <select class="glass-input w-full pl-4 pr-8 py-2.5 text-sm appearance-none cursor-pointer" style="color-scheme:dark;">
                                <option value="" disabled selected>Seleccionar...</option>
                                <option>Crítica</option><option>Alta</option><option>Media</option><option>Baja</option>
                            </select>
                            <i class="ph ph-caret-down absolute right-3 top-3 text-gray-400 text-xs pointer-events-none"></i>
                        </div>
                    </div>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="flex flex-col gap-1.5"><label class="text-xs opacity-70">Grupo</label><input type="text" placeholder="Ej. 2°A" class="glass-input w-full px-4 py-2.5 text-sm"></div>
                    <div class="flex flex-col gap-1.5"><label class="text-xs opacity-70">Reportante</label><input type="text" placeholder="Nombre del profesor..." class="glass-input w-full px-4 py-2.5 text-sm"></div>
                </div>
                <div class="flex flex-col gap-1.5">
                    <label class="text-xs opacity-70">Descripción</label>
                    <textarea class="glass-input w-full px-4 py-2.5 text-sm min-h-[90px] resize-y" placeholder="Describe claramente lo sucedido..."></textarea>
                </div>
                <div class="flex flex-col sm:flex-row gap-3 mt-2">
                    <button type="button" onclick="document.getElementById('modalNueva').classList.add('hidden')" class="btn-flat flex-1 py-2.5 flex items-center justify-center gap-2 text-sm"><i class="ph ph-x-circle"></i> Cancelar</button>
                    <button type="button" class="btn-accent flex-1 py-2.5 flex items-center justify-center gap-2 text-sm" onclick="document.getElementById('modalNueva').classList.add('hidden')"><i class="ph ph-floppy-disk"></i> Guardar</button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL DETALLE -->
    <div id="modalDetalle" class="fixed inset-0 z-50 flex items-center justify-center hidden bg-[rgba(0,0,0,0.65)] backdrop-blur-sm p-2 md:p-4">
        <div class="glass-panel w-full max-w-lg p-5 md:p-7 relative max-h-[95vh] overflow-y-auto">
            <button onclick="document.getElementById('modalDetalle').classList.add('hidden')" class="absolute top-5 right-5 text-gray-400 hover:text-white transition-colors"><i class="ph ph-x text-xl"></i></button>
            <h3 class="text-base font-semibold mb-1 text-[var(--color-accent)] flex items-center gap-2"><i class="ph ph-file-text"></i> Detalle — <span id="detalleId" class="font-mono"></span></h3>
            <p class="text-xs opacity-40 mb-5 pb-4 border-b border-[var(--color-border)]">Información completa del reporte.</p>
            <div id="detalleContenido" class="flex flex-col gap-3 text-sm"></div>
            <div class="flex flex-col sm:flex-row gap-3 mt-6">
                <button class="btn-flat flex-1 py-2.5 text-xs flex items-center justify-center gap-1.5"><i class="ph ph-pencil"></i> Editar Estado</button>
                <button onclick="document.getElementById('modalDetalle').classList.add('hidden')" class="btn-accent flex-1 py-2.5 text-xs flex items-center justify-center gap-1.5"><i class="ph ph-x"></i> Cerrar</button>
            </div>
        </div>
    </div>

    <script>
        const filtros = { estado: 'todas', prioridad: 'todas' };

        function setFiltro(btn, tipo, valor) {
            filtros[tipo] = valor;
            const grupo = tipo === 'estado'
                ? ['estadoTodas','estadoAbierta','estadoProceso','estadoResuelta']
                : ['prioTodas','prioCritica','prioAlta','prioMedia','prioBaja'];
            grupo.forEach(id => document.getElementById(id)?.classList.remove('active'));
            btn.classList.add('active');
            filtrarTabla();
        }

        function filtrarTabla() {
            const busqueda = document.getElementById('inputBuscar').value.toLowerCase();
            const categoria = document.getElementById('selectCategoria').value.toLowerCase();
            const filas = document.querySelectorAll('#tbodyIncidencias tr');
            let visibles = 0;
            filas.forEach(fila => {
                const ok = (filtros.estado    === 'todas' || fila.dataset.estado    === filtros.estado)
                        && (filtros.prioridad === 'todas' || fila.dataset.prioridad === filtros.prioridad)
                        && (!categoria || (fila.dataset.categoria||'').toLowerCase() === categoria)
                        && (!busqueda  || fila.textContent.toLowerCase().includes(busqueda));
                fila.style.display = ok ? '' : 'none';
                if (ok) visibles++;
            });
            document.getElementById('contadorIncidencias').textContent =
                `${visibles} incidencia${visibles !== 1 ? 's' : ''} encontrada${visibles !== 1 ? 's' : ''}`;
        }

        const datos = {
            'INC-001': { titulo:'Pelea entre alumnos en pasillo',          categoria:'Disciplina',     grupo:'3°A', reportante:'Prof. María González',  prioridad:'Alta',    prioClass:'badge-alta',    estado:'Abierta',    estClass:'badge-abierta',  fecha:'22 Jun 2026, 10:15 AM' },
            'INC-002': { titulo:'Alumno con posible sustancia prohibida',   categoria:'Seguridad',      grupo:'2°B', reportante:'Pref. Carlos Ruiz',      prioridad:'Crítica', prioClass:'badge-critica', estado:'En Proceso', estClass:'badge-proceso',  fecha:'21 Jun 2026, 08:40 AM' },
            'INC-003': { titulo:'Daños a mobiliario en aula',               categoria:'Infraestructura',grupo:'1°C', reportante:'Prof. Patricia Vega',    prioridad:'Media',   prioClass:'badge-media',   estado:'Resuelta',   estClass:'badge-resuelta', fecha:'20 Jun 2026, 14:30 PM' },
            'INC-004': { titulo:'Alumno con múltiples faltas injustificadas',categoria:'Asistencia',    grupo:'3°B', reportante:'Prof. Luis Hernández',   prioridad:'Baja',    prioClass:'badge-baja',    estado:'Abierta',    estClass:'badge-abierta',  fecha:'19 Jun 2026, 09:00 AM' },
            'INC-005': { titulo:'Alumno con síntomas de intoxicación',      categoria:'Salud',          grupo:'2°A', reportante:'Pref. Ana Castillo',     prioridad:'Alta',    prioClass:'badge-alta',    estado:'En Proceso', estClass:'badge-proceso',  fecha:'18 Jun 2026, 11:05 AM' },
            'INC-006': { titulo:'Confrontación verbal entre alumnos',       categoria:'Disciplina',     grupo:'1°A', reportante:'Prof. Roberto Díaz',     prioridad:'Media',   prioClass:'badge-media',   estado:'Resuelta',   estClass:'badge-resuelta', fecha:'17 Jun 2026, 13:20 PM' },
            'INC-007': { titulo:'Objeto peligroso encontrado en patio',     categoria:'Seguridad',      grupo:'—',   reportante:'Pref. Mónica Torres',    prioridad:'Alta',    prioClass:'badge-alta',    estado:'Abierta',    estClass:'badge-abierta',  fecha:'16 Jun 2026, 07:55 AM' },
            'INC-008': { titulo:'Acoso escolar reportado por tutor',        categoria:'Seguridad',      grupo:'2°C', reportante:'Prof. Silvia Mora',      prioridad:'Crítica', prioClass:'badge-critica', estado:'En Proceso', estClass:'badge-proceso',  fecha:'15 Jun 2026, 10:00 AM' },
            'INC-009': { titulo:'Ventana rota en salón de cómputo',         categoria:'Infraestructura',grupo:'—',   reportante:'Prof. Jorge Ríos',       prioridad:'Baja',    prioClass:'badge-baja',    estado:'Abierta',    estClass:'badge-abierta',  fecha:'14 Jun 2026, 08:30 AM' },
            'INC-010': { titulo:'Alumno sin tutor registrado en sistema',   categoria:'Asistencia',     grupo:'1°B', reportante:'Prof. Elena Fuentes',    prioridad:'Media',   prioClass:'badge-media',   estado:'En Proceso', estClass:'badge-proceso',  fecha:'13 Jun 2026, 12:00 PM' },
        };

        function verDetalle(id) {
            const d = datos[id]; if (!d) return;
            document.getElementById('detalleId').textContent = id;
            document.getElementById('detalleContenido').innerHTML = `
                <div class="flex flex-col gap-2.5">
                    <div class="flex justify-between"><span class="opacity-50">Título:</span><span class="font-medium text-right max-w-[60%]">${d.titulo}</span></div>
                    <div class="flex justify-between"><span class="opacity-50">Categoría:</span><span>${d.categoria}</span></div>
                    <div class="flex justify-between"><span class="opacity-50">Grupo:</span><span>${d.grupo}</span></div>
                    <div class="flex justify-between"><span class="opacity-50">Reportante:</span><span>${d.reportante}</span></div>
                    <div class="flex justify-between"><span class="opacity-50">Fecha:</span><span>${d.fecha}</span></div>
                    <div class="flex justify-between items-center"><span class="opacity-50">Prioridad:</span><span class="badge ${d.prioClass}">${d.prioridad}</span></div>
                    <div class="flex justify-between items-center"><span class="opacity-50">Estado:</span><span class="badge ${d.estClass}">${d.estado}</span></div>
                </div>`;
            document.getElementById('modalDetalle').classList.remove('hidden');
        }

        let ordenAsc = true;
        document.getElementById('btnOrdenar').addEventListener('click', () => {
            const tbody = document.getElementById('tbodyIncidencias');
            const filas = Array.from(tbody.querySelectorAll('tr'));
            filas.sort((a, b) => {
                const idA = a.querySelector('td')?.textContent?.trim() || '';
                const idB = b.querySelector('td')?.textContent?.trim() || '';
                return ordenAsc ? idB.localeCompare(idA) : idA.localeCompare(idB);
            });
            ordenAsc = !ordenAsc;
            filas.forEach(f => tbody.appendChild(f));
        });
    </script>
</body>
</html>