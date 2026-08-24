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
  <?php require 'oj-header-course.php'; ?>
  <main class="oj-page">
    <div class="mb-4"><a href="<?php echo $assignmentId > 0 ? 'course-contest.php?id=' . $courseId . '&assignmentId=' . $assignmentId : 'course.php?id=' . $courseId; ?>" class="text-blue-600 hover:underline">← Volver a los problemas</a></div>
    <div id="submissions-loading" class="rounded-xl border bg-white p-10 text-center text-gray-500 shadow-sm">Cargando envíos...</div>
    <div id="submissions-error" class="hidden rounded-lg border border-red-200 bg-red-50 p-4 text-red-800" role="alert"></div>
    <section id="submissions-content" class="oj-card hidden">
      <div class="flex flex-col items-center space-y-1.5 p-6 text-center">
        <h1 id="assignment-title" class="oj-page-title text-center">Envíos del contest</h1>
        <p class="oj-page-description text-center">Solo se muestran los envíos realizados dentro de los contests de este curso.</p>
      </div>
      <div id="submissions-empty" class="hidden oj-empty-state">Todavía no hay envíos en este contest.</div>
      <div id="submissions-table" class="hidden overflow-x-auto">
        <table class="oj-table">
          <thead>
            <tr><th>ID</th><th>Usuario</th><th>Problema</th><th>Resultado</th><th>Lenguaje</th><th>Fecha</th><th>Acciones</th></tr>
          </thead>
          <tbody id="submissions-body"></tbody>
        </table>
      </div>
    </section>
  </main>
  <?php require 'oj-footer.php'; ?>
  <script>
    window.PATITO_SUBMISSIONS_CONFIG = <?php echo json_encode([
      'apiUrl' => $apiUrl,
      'siteId' => $siteId,
      'courseId' => $courseId,
      'assignmentId' => $assignmentId,
      'userId' => $userId,
      'canGrade' => $canGrade,
    ], JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT); ?>;
  </script>
  <script src="./assets/auth-refresh.js"></script>
  <script src="./assets/manual-judge.js" defer></script>
  <script src="./assets/course-submissions.js"></script>
</body>
</html>
