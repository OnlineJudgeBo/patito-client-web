<!DOCTYPE html>
<html lang="es" class="h-full">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo htmlspecialchars($title, ENT_QUOTES, 'UTF-8'); ?></title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="./assets/base.css">
  <?php echo file_get_contents(__DIR__ . '/partials/utils-header.php'); ?>
</head>
<body class="flex h-full flex-col">
  <?php require 'oj-header.php'; ?>

  <main class="oj-page">
    <header class="oj-page-header gap-4">
      <div>
        <h1 id="courses-page-title" class="oj-page-title"><?php echo $canCreateCourses ? 'Cursos' : 'Mis cursos'; ?></h1>
        <p id="courses-page-description" class="oj-page-description"><?php echo $canCreateCourses ? 'Administra los cursos académicos y consulta aquellos en los que estás inscrito.' : 'Consulta los contests y problemas asignados por tus docentes.'; ?></p>
      </div>
      <section class="w-full max-w-xl rounded-lg border border-slate-200 bg-slate-50 p-4">
        <h2 class="font-semibold text-slate-900">Unirme a un curso</h2>
        <div class="mt-2"><?php require __DIR__ . '/partials/course-join-form.php'; ?></div>
      </section>
    </header>

    <div id="course-message" class="hidden mb-4 rounded border p-4 text-sm" role="status" aria-live="polite"></div>

    <section class="oj-card">
      <div class="flex items-center justify-between border-b px-5 py-4">
        <h2 id="courses-section-title" class="text-lg font-semibold text-slate-900"><?php echo $canCreateCourses ? 'Cursos que administras' : 'Cursos inscritos'; ?></h2>
        <div class="flex items-center gap-4">
          <button id="refresh-courses" type="button" class="text-sm font-semibold text-blue-600 hover:underline">Actualizar</button>
          <?php if ($canCreateCourses): ?><button id="open-course-form" type="button" class="rounded bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700">+ Nuevo curso</button><?php endif; ?>
        </div>
      </div>
      <div id="courses-loading" class="oj-empty-state">Cargando cursos...</div>
      <div id="courses-empty" class="hidden oj-empty-state"><strong id="courses-empty-title"><?php echo $canCreateCourses ? 'No administras ningún curso' : 'No estás inscrito en ningún curso'; ?></strong><span id="courses-empty-description"><?php echo $canCreateCourses ? 'Crea un curso para comenzar.' : 'Usa el código de invitación para unirte.'; ?></span></div>
      <div id="courses-list-wrapper" class="hidden overflow-x-auto">
        <table class="oj-table">
          <thead>
            <tr>
              <th>Curso</th>
              <th>Responsable</th>
              <th class="text-center">Contests</th>
            </tr>
          </thead>
          <tbody id="courses-list"></tbody>
        </table>
      </div>
    </section>

    <section id="enrolled-courses-section" class="oj-card mt-6<?php echo $canCreateCourses ? '' : ' hidden'; ?>">
      <div class="border-b px-5 py-4"><h2 class="text-lg font-semibold text-slate-900">Cursos en los que participas</h2><p class="mt-1 text-sm text-slate-600">Cursos donde participas como estudiante.</p></div>
      <div id="enrolled-courses-loading" class="oj-empty-state">Cargando cursos...</div>
      <div id="enrolled-courses-empty" class="hidden oj-empty-state">No estás inscrito en otros cursos.</div>
      <div id="enrolled-courses-wrapper" class="hidden overflow-x-auto">
        <table class="oj-table">
          <thead><tr><th>Curso</th><th>Responsable</th><th class="text-center">Contests</th></tr></thead>
          <tbody id="enrolled-courses-list"></tbody>
        </table>
      </div>
    </section>

    <?php if ($canCreateCourses): ?>
      <div id="course-form-modal" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-950/60 p-4" role="dialog" aria-modal="true" aria-labelledby="course-form-title">
        <form id="create-course-form" class="w-full max-w-xl overflow-hidden rounded-xl bg-white shadow-2xl">
          <div class="flex items-start justify-between border-b px-6 py-5">
            <div><h2 id="course-form-title" class="text-xl font-bold text-slate-900">Crear un curso</h2><p class="mt-1 text-sm text-gray-600">Configura la información principal del nuevo curso.</p></div>
            <button id="close-course-form" type="button" class="rounded p-1 text-2xl leading-none text-slate-400 hover:bg-slate-100 hover:text-slate-700" aria-label="Cerrar">&times;</button>
          </div>
          <div class="space-y-4 p-6">
          <div>
            <label for="course-name" class="mb-1 block text-sm font-semibold text-slate-700">Nombre <span class="text-red-600">*</span></label>
            <input id="course-name" name="name" maxlength="160" required placeholder="Nombre del curso" class="w-full rounded-lg border px-3 py-2 focus:border-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-100">
          </div>
          <div>
            <label for="course-description" class="mb-1 block text-sm font-semibold text-slate-700">Descripción</label>
            <textarea id="course-description" name="description" maxlength="1000" rows="3" placeholder="Una breve descripción (opcional)" class="w-full resize-y rounded-lg border px-3 py-2 focus:border-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-100"></textarea>
          </div>
          </div>
          <div class="flex justify-end gap-2 border-t bg-slate-50 px-6 py-4">
            <button id="cancel-course-form" type="button" class="rounded border border-slate-300 px-5 py-2 text-sm font-semibold text-slate-700 hover:bg-white">Cancelar</button>
            <button type="submit" class="rounded bg-blue-600 px-5 py-2 text-sm font-semibold text-white hover:bg-blue-700 disabled:opacity-60">Crear curso</button>
          </div>
        </form>
      </div>
    <?php endif; ?>

    <section id="course-detail" class="oj-card hidden" aria-live="polite">
      <div class="flex flex-wrap items-start justify-between gap-3 border-b px-6 py-5">
        <div>
          <button id="close-course-detail" type="button" class="mb-3 text-sm font-semibold text-blue-700 hover:underline">← Volver a mis cursos</button>
          <h2 id="course-detail-name" class="text-2xl font-bold text-slate-900"></h2>
          <p id="course-detail-description" class="mt-2 max-w-3xl text-gray-600"></p>
        </div>
        <span id="course-detail-role" class="rounded-full bg-blue-50 px-3 py-1 text-sm font-semibold text-blue-700"></span>
      </div>
      <?php if ($canManageAdmin): ?>
        <div id="course-detail-invite" class="hidden flex-wrap items-center gap-4 border-b bg-slate-50 px-6 py-4 text-sm">
          <div><span class="font-semibold text-slate-700">Código: </span><span id="course-detail-invite-code" class="font-mono"></span></div>
          <div><span class="font-semibold text-slate-700">Invitación: </span><button id="course-detail-copy-link" type="button" class="font-semibold text-blue-700 hover:underline">Copiar link público</button></div>
        </div>
      <?php endif; ?>
      <div id="course-detail-loading" class="oj-empty-state">Cargando contenido del curso...</div>
      <div id="course-assignments-empty" class="hidden oj-empty-state">Este curso todavía no tiene tareas publicadas.</div>
      <div id="course-assignments" class="hidden space-y-4 p-6"></div>
    </section>
  </main>

  <?php require 'oj-footer.php'; ?>
  <script>
    window.PATITO_COURSES_CONFIG = <?php echo json_encode([
      'apiUrl' => $apiUrl,
      'siteId' => $siteId,
      'adminUrl' => $adminUrl,
      'canCreate' => $canCreateCourses,
      'canManageAdmin' => $canManageAdmin,
    ], JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT); ?>;
  </script>
  <script src="./assets/courses.js"></script>
</body>
</html>
