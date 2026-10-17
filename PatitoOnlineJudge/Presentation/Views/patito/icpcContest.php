<!DOCTYPE html>
<html lang="es" class="h-full">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="./assets/base.css">
    <?php echo file_get_contents(__DIR__ . "/partials/utils-header.php"); ?>
</head>

<body class="flex flex-col h-full">

    <?php require_once "oj-header.php" ?>
    <main class="container mx-auto p-4">
        <div class="flex flex-col items-center">
            <div class="rounded-lg border bg-card text-card-foreground shadow-sm w-full">
                <div class="flex flex-col space-y-1.5 p-6">
                    <h2 class="text-4xl font-bold leading-tight text-center mb-4">
                        Bienvenido a la sección de archivos de concursos.<br>
                        Explora competencias pasadas de ICPC, OBI y OCE, y practica con los problemas.
                    </h2>
                </div>
                <?php
                // Archive grouped by year, newest first; the list already comes ordered by start date.
                $contests_by_year = [];
                foreach ($contest_list as $contest) {
                    $year = $contest["start_time"] ? date("Y", strtotime($contest["start_time"])) : "Sin fecha";
                    $contests_by_year[$year][] = $contest;
                }
                ?>
                <nav class="flex flex-wrap justify-center gap-2 px-6 pb-4">
                    <?php foreach ($contests_by_year as $year => $contests) { ?>
                        <a href="#anio-<?php echo str_replace(" ", "-", $year) ?>" class="rounded-full border px-3 py-1 text-sm font-medium hover:bg-blue-50 hover:border-blue-400">
                            <?php echo $year ?> <span class="text-gray-500">(<?php echo count($contests) ?>)</span>
                        </a>
                    <?php } ?>
                </nav>
                <div class="px-6 pb-6">
                    <?php foreach ($contests_by_year as $year => $contests) { ?>
                        <section id="anio-<?php echo str_replace(" ", "-", $year) ?>" class="scroll-mt-4">
                            <h3 class="sticky top-0 bg-white border-b-2 border-blue-500 py-2 mt-6 text-2xl font-bold"><?php echo $year ?></h3>
                            <ul class="divide-y">
                                <?php foreach ($contests as $contest) {
                                    $url = "contest.php?cid=" . $contest["contest_id"] . "&type=official_contest";
                                    $name = htmlspecialchars(trim(strip_tags($contest["title"])));
                                    $date = $contest["start_time"] ? date("d/m/Y", strtotime($contest["start_time"])) : "";
                                ?>
                                    <li>
                                        <a href="<?php echo $url ?>" class="flex items-baseline gap-4 px-2 py-3 hover:bg-blue-50">
                                            <span class="w-24 shrink-0 text-sm text-gray-500 tabular-nums"><?php echo $date ?></span>
                                            <span class="result-blue font-medium"><?php echo $name ?></span>
                                        </a>
                                    </li>
                                <?php } ?>
                            </ul>
                        </section>
                    <?php } ?>
                </div>
            </div>
        </div>

    </main>

    <?php require_once "oj-footer.php" ?>

</body>

</html>