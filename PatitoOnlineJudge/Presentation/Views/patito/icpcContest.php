<?php
// Archive grouped by year; the list already comes ordered by start date, newest first.
$contests_by_year = [];
foreach ($contest_list as $contest) {
    $year = $contest["start_time"] ? date("Y", strtotime($contest["start_time"])) : "Sin fecha";
    $contests_by_year[$year][] = $contest;
}
$year_anchor = fn($year) => "anio-" . str_replace(" ", "-", $year);
?>
<!DOCTYPE html>
<html lang="es" class="h-full">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php
    $seoTitle = "Concursos oficiales";
    $seoDescription = "Archivo de competencias pasadas de ICPC, OBI y OCE en Bolivia, organizadas por año, para practicar con los problemas originales.";
    $seoPath = "icpc_contest.php";
    require __DIR__ . "/partials/seo-meta.php";
    ?>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="./assets/base.css">
    <?php echo file_get_contents(__DIR__ . "/partials/utils-header.php"); ?>
    <style>
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

        #contest-table tr.year-row th {
            color: #0f172a;
            background: #f1f5f9;
            font-size: 1.05rem;
            font-weight: 700;
            text-align: left;
            scroll-margin-top: 1rem;
        }
    </style>
</head>

<body class="flex flex-col h-full">

    <?php require_once "oj-header.php" ?>
    <main class="oj-page">
        <header class="oj-page-header">
            <div>
                <h1 class="oj-page-title">Concursos oficiales</h1>
                <p class="oj-page-description">Explora competencias pasadas de ICPC, OBI y OCE, y practica con los problemas.</p>
            </div>
            <form class="contest-filters" onsubmit="return false">
                <label for="contest-year">Año</label>
                <select id="contest-year" onchange="if (this.value) location.hash = this.value">
                    <option value="">Todos</option>
                    <?php foreach ($contests_by_year as $year => $contests) { ?>
                        <option value="<?php echo $year_anchor($year) ?>"><?php echo $year ?></option>
                    <?php } ?>
                </select>
            </form>
        </header>
        <div class="oj-card contest-list-card">
            <div class="relative w-full overflow-auto">
                <table id="contest-table" class="oj-table">
                    <thead>
                        <tr>
                            <th>Fecha</th>
                            <th>Nombre</th>
                        </tr>
                    </thead>
                    <?php
                    if (empty($contests_by_year)) {
                        echo '<tbody><tr><td colspan="2" class="oj-empty-state"><strong>No hay concursos oficiales</strong></td></tr></tbody>';
                    }
                    foreach ($contests_by_year as $year => $contests) {
                        echo '<tbody>';
                        echo '<tr class="year-row"><th colspan="2" id="' . $year_anchor($year) . '">' . $year . '</th></tr>';
                        foreach ($contests as $contest) {
                            $contestId = intval($contest["contest_id"]);
                            $name = htmlspecialchars(trim(strip_tags($contest["title"])));
                            $date = $contest["start_time"] ? date("d-m-Y", strtotime($contest["start_time"])) : "";
                            echo '<tr>';
                            echo '<td class="whitespace-nowrap w-32"><span class="oj-stat">' . $date . '</span></td>';
                            echo '<td><a class="oj-table-link" href="contest.php?cid=' . $contestId . '&type=official_contest">' . $name . '</a></td>';
                            echo '</tr>';
                        }
                        echo '</tbody>';
                    }
                    ?>
                </table>
            </div>
        </div>
    </main>

    <?php require_once "oj-footer.php" ?>

</body>

</html>
