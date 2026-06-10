<?php
function formatContestTitle($title)
{
    $safeTitle = htmlspecialchars($title, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    $colors = [
        'red' => 'red',
        '#ff0000' => 'red',
        'orange' => 'orange',
        '#ffa500' => 'orange',
        'green' => 'green',
        '#008000' => 'green',
        'blue' => 'blue',
        '#0000ff' => 'blue',
        'purple' => 'purple',
        'violet' => 'purple',
        '#800080' => 'purple',
    ];

    $safeTitle = preg_replace_callback(
        '/&lt;\s*font\s+color\s*=\s*(?:(?:&quot;|&#039;)([^&]+)(?:&quot;|&#039;)|([a-zA-Z#0-9]+))\s*&gt;/i',
        function ($matches) use ($colors) {
            $color = strtolower(trim($matches[1] !== '' ? $matches[1] : $matches[2]));
            if (!isset($colors[$color])) {
                return '';
            }

            return '<span class="contest-title-' . $colors[$color] . '">';
        },
        $safeTitle
    );
    $safeTitle = preg_replace('/&lt;\s*\/\s*font\s*&gt;/i', '</span>', $safeTitle);

    foreach (['b', 'strong', 'i', 'em'] as $tag) {
        $safeTitle = preg_replace(
            '/&lt;\s*(\/?)\s*' . $tag . '\s*&gt;/i',
            '<$1' . $tag . '>',
            $safeTitle
        );
    }

    return $safeTitle;
}

?>
<!DOCTYPE html>
<html lang="es" class="h-full">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title ?></title>
    <link href='https://fonts.googleapis.com/css?family=Capriola' rel='stylesheet'>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="./assets/base.css">
    <?php echo file_get_contents(__DIR__."/partials/utils-header.php"); ?>
    <style>
        #contest-table thead {
            color: #fff;
            background: #111827;
        }

        #contest-table th {
            padding: 0.55rem 1rem;
            border-right: 1px solid #d1d5db;
            border-bottom: 0;
            font-size: 1rem;
            font-weight: 600;
            letter-spacing: 0;
            text-align: center;
            text-transform: none;
        }

        #contest-table th:last-child {
            border-right: 0;
        }

        #contest-table th:nth-child(2),
        #contest-table td:nth-child(2) {
            text-align: left;
        }

        #contest-table .oj-table-link,
        #contest-table .oj-table-link b,
        #contest-table .oj-table-link strong {
            font-weight: 400;
        }

        #contest-table .oj-stat {
            padding: 0;
            color: #334155;
            background: transparent;
            font-weight: 600;
        }

        #contest-table .oj-badge {
            min-width: 6.2rem;
            justify-content: center;
            padding: 0.3rem 0.65rem;
            border: 1px solid;
            border-radius: 0.35rem;
            font-weight: 500;
        }

        #contest-table .oj-badge-public {
            color: #047857;
            border-color: #a7f3d0;
            background: #ecfdf5;
        }

        #contest-table .oj-badge-private {
            color: #9f1239;
            border-color: #fecdd3;
            background: #fff1f2;
        }

        .contest-filters {
            display: flex;
            align-items: center;
            gap: 0.6rem;
        }

        .contest-filters label {
            color: #475569;
            font-size: 0.875rem;
            font-weight: 600;
        }

        .contest-filters select {
            min-width: 7rem;
            padding: 0.4rem 2rem 0.4rem 0.65rem;
            border: 1px solid #cbd5e1;
            border-radius: 0.35rem;
            color: #334155;
            background: #fff;
            font-size: 0.875rem;
        }

        .contest-list-card {
            border-radius: 0.4rem;
            box-shadow: none;
        }

        .contest-title-red { color: #b91c1c; }
        .contest-title-orange { color: #c2410c; }
        .contest-title-green { color: #15803d; }
        .contest-title-blue { color: #1d4ed8; }
        .contest-title-purple { color: #7e22ce; }
    </style>
</head>

<body class="flex flex-col h-full">

    <?php require_once "oj-header.php" ?>
    <main class="oj-page">
        <header class="oj-page-header">
            <div>
                <h1 class="oj-page-title">Concursos</h1>
                <p class="oj-page-description"></p>
            </div>
            <div class="flex flex-wrap items-center justify-end gap-4">
                <form class="contest-filters" method="get" action="contest.php">
                    <label for="contest-year">Año</label>
                    <select id="contest-year" name="year" onchange="this.form.submit()">
                        <option value="">Todos</option>
                        <?php foreach ($availableYears as $year) { ?>
                            <option value="<?php echo $year ?>" <?php echo $selectedYear === $year ? 'selected' : '' ?>>
                                <?php echo $year ?>
                            </option>
                        <?php } ?>
                    </select>
                </form>
                <div class="text-sm text-slate-500">Hora del servidor: <span class="nowdate font-semibold text-slate-700"></span></div>
            </div>
        </header>
        <div class="oj-card contest-list-card">
            <div class="relative w-full overflow-auto">
                <?php require __DIR__ . "/../Modules/ServerTime.php"; ?>
                <?php require __DIR__ . "/../Modules/StatusTime.php"; ?>
                <table id="contest-table" class="oj-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Estado</th>
                            <th>Acceso</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (empty($contest_list)) {
                            echo '<tr><td colspan="4" class="oj-empty-state"><strong>No hay concursos disponibles</strong>Los nuevos concursos aparecerán aquí cuando sean publicados.</td></tr>';
                        }
                        foreach ($contest_list as $value) {
                            $contestId = intval($value["contest_id"]);
                            $contestTitle = formatContestTitle($value["title"]);
                            echo '<tr>';
                            echo '<td><span class="oj-stat">#' . $contestId . '</span></td>';
                            echo '<td><a class="oj-table-link" href="contest.php?cid=' . $contestId . '">' . $contestTitle . '</a></td>';
                            echo '<td>' . getStatusTime($value["start_time"], $value["end_time"]) . '</td>';
                            echo $value["private"] == 0
                                ? '<td><span class="oj-badge oj-badge-public"><span aria-hidden="true">○</span> Público</span></td>'
                                : '<td><span class="oj-badge oj-badge-private"><span aria-hidden="true">●</span> Privado</span></td>';
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
