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
    <div id="contest-loading" class="rounded-xl border bg-white p-10 text-center text-gray-500 shadow-sm">Cargando contest...</div>
    <div id="contest-error" class="hidden rounded-lg border border-red-200 bg-red-50 p-4 text-red-800" role="alert"></div>
    <div id="contest-detail" class="hidden">
      <div id="contest-card"></div>
    </div>
  </main>

  <?php require 'oj-footer.php'; ?>
  <script>
    window.PATITO_COURSE_CONTEST_CONFIG = <?php echo json_encode([
      'apiUrl' => $apiUrl,
      'siteId' => $siteId,
      'courseId' => $courseId,
      'assignmentId' => $assignmentId,
    ], JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT); ?>;
  </script>
  <script src="./assets/course-contest.js"></script>
</body>
</html>
