<!DOCTYPE html>
<html lang="es" class="h-full">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title ?></title>
    <link href='https://fonts.googleapis.com/css?family=Capriola' rel='stylesheet'>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="./assets/base.css">

</head>

<body class="flex flex-col h-full">

    <?php require_once "oj-header.php" ?>
    <main class="oj-page">
        <header class="oj-page-header">
            <div>
                <p class="oj-eyebrow">Competencias disponibles</p>
            </div>
            <div class="text-sm text-slate-500">Hora del servidor: <span class="nowdate font-semibold text-slate-700"></span></div>
        </header>
        <div class="oj-card">
            <div class="relative w-full overflow-auto">
                <?php require __DIR__ . "/../Modules/ServerTime.php"; ?>
                <?php require __DIR__ . "/../Modules/StatusTime.php"; ?>
                <table class="oj-table">
                    <thead><tr><th>ID</th><th>Nombre</th><th>Estado</th><th>Acceso</th></tr></thead>
                    <tbody>
                        <?php
                        if (empty($contest_list)) {
                            echo '<tr><td colspan="4" class="oj-empty-state"><strong>No hay concursos disponibles</strong>Los nuevos concursos aparecerán aquí cuando sean publicados.</td></tr>';
                        }
                        foreach ($contest_list as $value) {
                            $contestId = intval($value["contest_id"]);
                            $contestTitle = htmlspecialchars($value["title"], ENT_QUOTES, 'UTF-8');
                            echo '<tr><td><span class="oj-stat">#' . $contestId . '</span></td>';
                            echo '<td><a class="oj-table-link" href="contest.php?cid=' . $contestId . '">' . $contestTitle . '</a></td>';
                            echo '<td>' . getStatusTime($value["start_time"], $value["end_time"]) . '</td>';
                            echo $value["private"] == 0
                                ? '<td><span class="oj-badge oj-badge-public">● Público</span></td>'
                                : '<td><span class="oj-badge oj-badge-private">● Privado</span></td>';
                            echo '</tr>';
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <?php require_once "oj-footer.php" ?>

</body>

</html>
