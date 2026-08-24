<?php require_once __DIR__ . "/Modules/Utils.php"; ?>
<!DOCTYPE html>
<html lang="es" class="h-full">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo htmlspecialchars($title, ENT_QUOTES, 'UTF-8'); ?></title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="<?php echo assetVersion('./assets/base.css'); ?>">
  <link rel="stylesheet" href="https://cdn.datatables.net/2.0.0/css/dataTables.tailwindcss.css">
  <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
  <script src="https://cdn.datatables.net/2.0.0/js/dataTables.js"></script>
  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/searchpanes/2.3.0/css/searchPanes.dataTables.css">
  <script src="https://cdn.datatables.net/searchpanes/2.3.0/js/dataTables.searchPanes.js"></script>
  <script src="https://cdn.datatables.net/searchpanes/2.3.0/js/searchPanes.dataTables.js"></script>
  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/select/2.0.0/css/select.dataTables.css">
  <script src="https://cdn.datatables.net/select/2.0.0/js/dataTables.select.js"></script>
  <script src="https://cdn.datatables.net/select/2.0.0/js/select.dataTables.js"></script>
  <script src="<?php echo assetVersion('./assets/datatable-filters.js'); ?>"></script>
  <?php echo file_get_contents(__DIR__ . '/partials/utils-header.php'); ?>
  <style>
    .dt-paging.paging_full_numbers {
        display: flex;
        justify-content: flex-end;
    }

    div.dt-container .dt-paging .dt-paging-button {
        box-sizing: border-box;
        display: inline-block;
        min-width: 1.5em;
        padding: 0.5em 1em;
        margin-left: 2px;
        text-align: center;
        text-decoration: none !important;
        cursor: pointer;
        color: inherit !important;
        border: 1px solid transparent;
        border-radius: 2px;
        background: transparent;
    }

    div.dt-container .dt-paging .dt-paging-button.current,
    div.dt-container .dt-paging .dt-paging-button.current:hover {
        color: inherit !important;
        border: 1px solid rgba(0, 0, 0, 0.3);
        background-color: rgba(0, 0, 0, 0.05);
    }

    div.dt-container .dt-paging .dt-paging-button.disabled,
    div.dt-container .dt-paging .dt-paging-button.disabled:hover,
    div.dt-container .dt-paging .dt-paging-button.disabled:active {
        cursor: default;
        color: rgba(0, 0, 0, 0.5) !important;
        border: 1px solid transparent;
        background: transparent;
        box-shadow: none;
    }

    div.dt-container .dt-paging .dt-paging-button:hover {
        color: white !important;
        border: 1px solid #111;
        background-color: #111;
    }

    div.dt-container .dt-paging .dt-paging-button:active {
        outline: none;
        background-color: #0c0c0c;
        box-shadow: inset 0 0 3px #111;
    }

    div.dt-container .dt-paging .ellipsis {
        padding: 0 1em;
    }

    table.dataTable > tbody > tr.selected > * {
        box-shadow: inset 0 0 0 9999px rgba(13, 110, 253, 0.3) !important;
        box-shadow: inset 0 0 0 9999px rgba(var(--dt-row-selected), 0.3) !important;
        color: #2c3e50 !important;
        color: rgb(var(--dt-row-selected-text)) !important;
    }

    .dtsp-searchPane a {
        pointer-events: none;
        color: inherit;
    }
  </style>
</head>
<body class="flex h-full flex-col">
  <?php require 'oj-header-course.php'; ?>
  <main class="oj-page">
    <div class="mb-4"><a href="<?php echo $assignmentId > 0 ? 'course-contest.php?id=' . $courseId . '&assignmentId=' . $assignmentId : 'course.php?id=' . $courseId; ?>" class="text-blue-600 hover:underline">← Volver a los problemas</a></div>
    <div id="submissions-loading" class="rounded-xl border bg-white p-10 text-center text-gray-500 shadow-sm">Cargando envíos...</div>
    <div id="submissions-error" class="hidden rounded-lg border border-red-200 bg-red-50 p-4 text-red-800" role="alert"></div>
    <section id="submissions-content" class="hidden">
      <div class="flex flex-col items-center space-y-1.5 p-6 text-center">
        <h1 id="assignment-title" class="oj-page-title text-center">Envíos del contest</h1>
        <p class="oj-page-description text-center">Solo se muestran los envíos realizados dentro de los contests de este curso.</p>
      </div>
      <div class="relative w-full overflow-auto">
        <table class="oj-table" id="status-table">
          <thead>
            <tr><th>ID</th><th>Usuario</th><th>Problema</th><th>Lenguaje</th><th>Resultado</th><th>Fecha</th></tr>
          </thead>
          <tbody></tbody>
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
  <script src="<?php echo assetVersion('./assets/auth-refresh.js'); ?>"></script>
  <script src="<?php echo assetVersion('./assets/manual-judge.js'); ?>" defer></script>
  <script src="<?php echo assetVersion('./assets/course-submissions.js'); ?>"></script>
</body>
</html>
