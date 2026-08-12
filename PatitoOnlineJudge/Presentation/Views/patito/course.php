<!DOCTYPE html>
<html lang="es" class="h-full">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo htmlspecialchars($title, ENT_QUOTES, 'UTF-8'); ?></title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://cdn.ckeditor.com/ckeditor5/41.4.2/classic/ckeditor.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/dompurify@3.1.6/dist/purify.min.js"></script>
  <link rel="stylesheet" href="./assets/base.css">
  <?php echo file_get_contents(__DIR__ . '/partials/utils-header.php'); ?>
</head>
<body class="flex h-full flex-col">
  <?php require 'oj-header-course.php'; ?>

  <main class="oj-page">
    <div id="course-loading" class="rounded-xl border bg-white p-10 text-center text-gray-500 shadow-sm">Cargando contenido del curso...</div>
    <div id="course-error" class="hidden rounded-lg border border-red-200 bg-red-50 p-4 text-red-800" role="alert"></div>

    <section id="course-content" class="hidden">
      <header id="course-header" class="oj-page-header">
        <div>
          <h1 id="course-name" class="oj-page-title"></h1>
          <p id="course-description" class="oj-page-description"></p>
        </div>
      </header>
      <?php if ($canManageAdmin): ?>
        <div id="course-invite" class="hidden mb-5 flex-wrap items-center gap-4 rounded-lg border border-slate-200 bg-slate-50 px-4 py-3 text-sm">
          <div><span class="font-semibold text-slate-700">Código: </span><span id="course-invite-code" class="font-mono"></span></div>
          <div><span class="font-semibold text-slate-700">Invitación: </span><button id="course-copy-link" type="button" class="font-semibold text-blue-700 hover:underline">Copiar link público</button></div>
        </div>
      <?php endif; ?>
      <nav id="course-tabs" class="mb-5 hidden gap-1 border-b border-slate-200" role="tablist">
        <button type="button" data-tab="content" class="course-tab border-b-2 border-green-700 px-4 py-2 text-sm font-semibold text-slate-900" role="tab" aria-selected="true">Contenido</button>
        <button type="button" data-tab="students" class="course-tab border-b-2 border-transparent px-4 py-2 text-sm font-semibold text-slate-500 hover:text-slate-800" role="tab" aria-selected="false">Estudiantes</button>
        <button type="button" data-tab="report" class="course-tab border-b-2 border-transparent px-4 py-2 text-sm font-semibold text-slate-500 hover:text-slate-800" role="tab" aria-selected="false">Reporte</button>
      </nav>
      <div id="contest-management" class="mb-4 hidden justify-end">
        <div class="flex gap-2"><button id="toggle-material-form" type="button" class="inline-flex items-center gap-2 rounded border border-slate-300 bg-white px-5 py-2.5 text-sm font-semibold text-slate-700 shadow-sm hover:bg-slate-50"><span class="text-lg leading-none">+</span> Material</button><button id="toggle-contest-form" type="button" class="inline-flex items-center gap-2 rounded bg-green-700 px-5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-green-600"><span class="text-lg leading-none">+</span> Contest</button></div>
      </div>
      <div id="material-form-modal" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-950/60 p-4" role="dialog" aria-modal="true" aria-labelledby="material-form-title">
        <form id="create-material-form" class="w-full max-w-2xl overflow-hidden rounded-xl bg-white shadow-2xl">
          <div class="flex items-start justify-between border-b px-6 py-5"><div><h1 id="material-form-title" class="text-xl font-bold text-slate-900">Agregar material de estudio</h1><p class="mt-1 text-sm text-slate-600">El material aparecerá como el siguiente paso de la ruta.</p></div><button id="close-material-form" type="button" class="rounded p-1 text-2xl leading-none text-slate-400 hover:bg-slate-100" aria-label="Cerrar">&times;</button></div>
          <div class="space-y-4 p-6">
            <div id="material-form-message" class="hidden rounded border p-3 text-sm"></div>
            <div><label for="material-title" class="mb-1 block text-sm font-semibold text-slate-700">Título <span class="text-red-600">*</span></label><input id="material-title" required maxlength="255" class="w-full rounded-lg border border-slate-300 px-3 py-2.5 focus:border-green-600 focus:outline-none" placeholder="Ej. Introducción a condicionales"></div>
            <div><label for="material-description" class="mb-1 block text-sm font-semibold text-slate-700">Descripción</label><input id="material-description" maxlength="1000" class="w-full rounded-lg border border-slate-300 px-3 py-2.5 focus:border-green-600 focus:outline-none" placeholder="Breve resumen del material"></div>
            <div><label for="material-body" class="mb-1 block text-sm font-semibold text-slate-700">Contenido</label><textarea id="material-body" rows="7" class="w-full resize-y rounded-lg border border-slate-300 px-3 py-2.5" placeholder="Explicación, instrucciones o apuntes para el estudiante..."></textarea><p class="mt-1 text-xs text-slate-500">Puedes usar títulos, listas, enlaces, negrita y otros formatos.</p></div>
            <div><label for="material-url" class="mb-1 block text-sm font-semibold text-slate-700">Enlace de apoyo</label><input id="material-url" type="url" class="w-full rounded-lg border border-slate-300 px-3 py-2.5 focus:border-green-600 focus:outline-none" placeholder="https://..."><p class="mt-1 text-xs text-slate-500">Agrega contenido, un enlace, o ambos.</p></div>
          </div>
          <div class="flex justify-end gap-2 border-t bg-slate-50 px-6 py-4"><button id="cancel-material-form" type="button" class="rounded border border-slate-300 px-5 py-2 text-sm font-semibold text-slate-700">Cancelar</button><button type="submit" class="rounded bg-green-700 px-5 py-2 text-sm font-semibold text-white hover:bg-green-600 disabled:opacity-60">Publicar material</button></div>
        </form>
      </div>
      <div id="contest-form-modal" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-950/60 p-4" role="dialog" aria-modal="true" aria-labelledby="contest-form-title">
        <form id="create-contest-form" class="max-h-[92vh] w-full max-w-5xl overflow-y-auto rounded-xl bg-white shadow-2xl">
          <div class="flex items-start justify-between border-b px-6 py-5">
            <div><h1 id="contest-form-title" class="text-xl font-bold text-slate-900">Crear contest del curso</h1><p class="mt-1 text-sm text-slate-600">Información principal a la izquierda y selección de problemas a la derecha.</p></div>
            <button id="close-contest-form" type="button" class="rounded p-1 text-2xl leading-none text-slate-400 hover:bg-slate-100 hover:text-slate-700" aria-label="Cerrar">&times;</button>
          </div>
          <div id="contest-form-message" class="mx-6 mt-5 hidden rounded border p-3 text-sm" role="status" aria-live="polite"></div>
          <div class="grid gap-0 p-6 lg:grid-cols-2 lg:divide-x lg:divide-slate-200">
          <div class="space-y-5 lg:pr-6">
          <div><h2 class="font-semibold text-slate-900">Información del contest</h2><p class="mt-1 text-sm text-slate-500">Define el nombre, la descripción y su duración.</p></div>
          <div>
              <label for="contest-title" class="mb-1 block text-sm font-semibold text-slate-700">Nombre del contest <span class="text-red-600">*</span></label>
              <input id="contest-title" maxlength="160" required class="w-full rounded-lg border border-slate-300 px-3 py-2.5 focus:border-green-600 focus:outline-none focus:ring-2 focus:ring-green-100" placeholder="Ej. Contest clase 1">
          </div>
          <div>
            <label for="contest-description" class="mb-1 block text-sm font-semibold text-slate-700">Descripción</label>
            <textarea id="contest-description" maxlength="1000" rows="2" class="w-full resize-y rounded-lg border border-slate-300 px-3 py-2.5 focus:border-green-600 focus:outline-none focus:ring-2 focus:ring-green-100" placeholder="Instrucciones o tema del contest (opcional)"></textarea>
          </div>
          <div class="grid gap-4 md:grid-cols-2">
            <div>
              <label for="contest-start" class="mb-1 block text-sm font-semibold text-slate-700">Inicio <span class="text-red-600">*</span></label>
              <input id="contest-start" type="datetime-local" required class="w-full rounded border border-slate-300 px-3 py-2 focus:border-green-600 focus:outline-none">
            </div>
            <div>
              <label for="contest-end" class="mb-1 block text-sm font-semibold text-slate-700">Finalización <span class="text-red-600">*</span></label>
              <input id="contest-end" type="datetime-local" required class="w-full rounded border border-slate-300 px-3 py-2 focus:border-green-600 focus:outline-none">
            </div>
          </div>
          </div>
          <div class="mt-6 space-y-5 lg:mt-0 lg:pl-6">
            <div><h2 class="font-semibold text-slate-900">Agregar problemas</h2><p class="mt-1 text-sm text-slate-500">Busca por ID o título, o pega varios IDs manualmente.</p></div>
            <div class="relative">
              <label for="problem-search" class="mb-1 block text-sm font-semibold text-slate-700">Buscar problemas</label>
              <input id="problem-search" type="search" autocomplete="off" class="w-full rounded-lg border border-slate-300 px-3 py-2.5 focus:border-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-100" placeholder="Ej. 1001 o Suma de dos números">
              <div id="problem-search-results" class="absolute z-10 mt-1 hidden max-h-56 w-full overflow-y-auto rounded-lg border bg-white shadow-xl"></div>
            </div>
            <div class="flex items-center gap-3"><div class="h-px flex-1 bg-slate-200"></div><span class="text-xs font-semibold uppercase text-slate-400">o agrega IDs</span><div class="h-px flex-1 bg-slate-200"></div></div>
            <div>
              <label for="contest-problems" class="mb-1 block text-sm font-semibold text-slate-700">IDs de problemas <span class="text-red-600">*</span></label>
              <textarea id="contest-problems" required rows="7" class="w-full resize-y rounded-lg border border-slate-300 px-3 py-2.5 text-sm focus:border-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-100" placeholder="1001&#10;1003&#10;1006"></textarea>
              <p class="mt-1 text-xs text-slate-500">Uno por línea o separados por comas. Se pueden reutilizar problemas de otros contests.</p>
            </div>
            <div id="selected-problems-wrapper" class="hidden"><p id="selected-problems-count" class="mb-2 text-xs font-semibold uppercase tracking-wide text-slate-500">Problemas seleccionados</p><div id="selected-problems" class="space-y-2"></div></div>
          </div>
          </div>
          <div class="flex justify-end gap-2 border-t bg-slate-50 px-6 py-4">
            <button id="cancel-contest-form" type="button" class="rounded border border-slate-300 px-5 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">Cancelar</button>
            <button type="submit" class="rounded bg-green-700 px-5 py-2 text-sm font-semibold text-white hover:bg-green-600 disabled:opacity-60">Crear contest</button>
          </div>
        </form>
      </div>
      <div id="tab-panel-content">
        <header id="course-path-header" class="oj-page-header">
        </header>
        <div id="contests-empty" class="hidden oj-card oj-empty-state">Este curso todavía no tiene contenido publicado.</div>
        <div id="contest-list" class="space-y-2"></div>
      </div>

      <div id="tab-panel-students" class="hidden">
        <section class="rounded-2xl border bg-white p-5">
          <h2 class="font-semibold text-slate-900">Agregar estudiante</h2>
          <div id="add-member-message" class="mt-3 hidden rounded border p-3 text-sm" role="status" aria-live="polite"></div>
          <form id="add-member-form" class="mt-3 flex flex-wrap items-end gap-3">
            <div class="relative min-w-0 flex-1">
              <label for="member-search" class="mb-1 block text-sm font-semibold text-slate-700">Usuario <span class="text-red-600">*</span></label>
              <input id="member-search" type="search" autocomplete="off" required placeholder="Nombre, apellido, usuario o correo" class="w-full rounded-lg border border-slate-300 px-3 py-2.5 focus:border-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-100">
              <div id="member-search-results" class="absolute z-10 mt-1 hidden max-h-56 w-full overflow-y-auto rounded-lg border bg-white shadow-xl"></div>
            </div>
            <div>
              <label for="member-role" class="mb-1 block text-sm font-semibold text-slate-700">Rol</label>
              <select id="member-role" class="rounded-lg border border-slate-300 px-3 py-2.5 focus:border-green-600 focus:outline-none">
                <option value="estudiante">Estudiante</option>
                <option value="auxiliar">Auxiliar</option>
              </select>
            </div>
            <button type="submit" class="rounded bg-green-700 px-5 py-2.5 text-sm font-semibold text-white hover:bg-green-600 disabled:opacity-60">Agregar</button>
          </form>
        </section>

        <section class="oj-card mt-6">
          <div class="flex items-center justify-between border-b px-5 py-4">
            <h2 class="text-lg font-semibold text-slate-900">Miembros del curso</h2>
            <button id="refresh-members" type="button" class="text-sm font-semibold text-blue-600 hover:underline">Actualizar</button>
          </div>
          <div id="members-loading" class="oj-empty-state">Cargando estudiantes...</div>
          <div id="members-empty" class="hidden oj-empty-state">Todavía no hay miembros en este curso.</div>
          <div id="members-wrapper" class="hidden overflow-x-auto">
            <table class="oj-table">
              <thead><tr><th>Usuario</th><th>Nombre</th><th>Rol</th><th></th></tr></thead>
              <tbody id="members-list"></tbody>
            </table>
          </div>
        </section>
      </div>

      <div id="tab-panel-report" class="hidden">
        <div class="mb-4 flex flex-wrap items-center justify-between gap-3">
          <div class="flex items-center gap-4">
            <button id="refresh-report" type="button" class="text-sm font-semibold text-blue-600 hover:underline">Actualizar</button>
            <button id="download-report-csv" type="button" class="rounded border border-slate-300 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">Descargar CSV</button>
          </div>
        </div>
        <div id="report-loading" class="oj-card oj-empty-state">Cargando reporte...</div>
        <div id="report-empty" class="hidden oj-card oj-empty-state">Todavía no hay estudiantes inscritos en este curso.</div>
        <div id="report-wrapper" class="hidden oj-card overflow-x-auto">
          <table class="oj-table">
            <thead id="report-head"></thead>
            <tbody id="report-body"></tbody>
          </table>
        </div>
      </div>
    </section>
  </main>

  <?php require 'oj-footer.php'; ?>
  <script>
    window.PATITO_COURSE_CONFIG = <?php echo json_encode([
      'apiUrl' => $apiUrl,
      'siteId' => $siteId,
      'courseId' => $courseId,
      'canManageAdmin' => $canManageAdmin,
    ], JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT); ?>;
  </script>
  <script src="./assets/course.js"></script>
</body>
</html>
