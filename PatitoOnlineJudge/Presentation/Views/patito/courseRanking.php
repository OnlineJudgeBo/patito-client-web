<!DOCTYPE html>
<html lang="es" class="h-full">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo htmlspecialchars($title, ENT_QUOTES, 'UTF-8'); ?></title>
  <link href="https://fonts.googleapis.com/css?family=Capriola" rel="stylesheet">
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="./assets/base.css">
  <?php echo file_get_contents(__DIR__ . '/partials/utils-header.php'); ?>
</head>
<body class="flex h-full flex-col">
  <?php require 'oj-header-course.php'; ?>
  <main class="oj-page">
    <div id="ranking-loading" class="rounded-xl border bg-white p-10 text-center text-gray-500 shadow-sm">Cargando ranking...</div>
    <div id="ranking-error" class="hidden rounded-lg border border-red-200 bg-red-50 p-4 text-red-800" role="alert"></div>

    <section id="ranking-content" class="oj-card hidden w-full">
      <div class="flex flex-col items-center space-y-1.5 p-6">
        <p class="oj-eyebrow">Resultados en tiempo real</p>
        <h1 id="ranking-course-name" class="oj-page-title text-center">Ranking del curso</h1>
        <p class="oj-page-description text-center">Resultados acumulados de todos los contests del curso.</p>
      </div>
      <div id="ranking-empty" class="oj-empty-state hidden"><strong>Aún no hay participantes clasificados</strong>La tabla se actualizará cuando existan envíos en el curso.</div>
      <div id="ranking-table-wrapper" class="relative m-1 overflow-x-auto rounded-lg shadow-lg">
        <table class="oj-table oj-table-icpc text-sm">
          <thead>
            <tr>
              <th>#</th>
              <th>Nombre</th>
              <th>Usuario</th>
              <th>Resueltos</th>
              <th>Intentos</th>
              <th>Contests</th>
            </tr>
          </thead>
          <tbody id="ranking-body"></tbody>
        </table>
        <div class="oj-legend">Clasificación acumulada de todos los contests del curso.</div>
      </div>
    </section>
  </main>
  <?php require 'oj-footer.php'; ?>
  <script>
    window.PATITO_COURSE_RANKING_CONFIG = <?php echo json_encode([
      'apiUrl' => $apiUrl,
      'siteId' => $siteId,
      'courseId' => $courseId,
      'assignmentId' => $assignmentId,
    ], JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT); ?>;
  </script>
  <script src="./assets/course-ranking.js"></script>
</body>
</html>
