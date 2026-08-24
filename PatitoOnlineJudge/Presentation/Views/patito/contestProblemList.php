<!DOCTYPE html>
<html lang="es" class="h-full">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="./assets/base.css">
    <?php echo file_get_contents(__DIR__."/partials/utils-header.php"); ?>
</head>

<body class="flex flex-col h-full">
    <?php
    if (isset($cid) && intval($cid) > 0) {
        require "oj-header-contest.php";
    } else {
        require "oj-header.php";
    }
    ?>
    <?php require __DIR__ . "/../Modules/StatusTime.php"; ?>
    <?php require __DIR__ . "/../Modules/Utils.php"; ?>
    <main class="container mx-auto p-4">
        <div class="flex flex-col">
            <div class="rounded-lg border bg-card text-card-foreground shadow-sm w-full">
                <div class="flex flex-col space-y-1.5 p-6 items-center">
                    <h3 class="text-2xl font-semibold leading-none tracking-tight">
                        <?php
                        echo $contestDetail["contest_id"] . " " . closetags($contestDetail["title"]);
                        ?>
                    </h3>
                    <h5 class="oj-rich-content font-semibold leading-none tracking-tight py-2">
                        <?php
                        echo closetags($contestDetail["description"]);
                        ?>
                    </h5>
                </div>

                <div class="flex items-center justify-center py-4">
                    <div class="grid grid-cols-2 gap-1">
                        <div class="flex justify-left"><b>Hora de Inicio:</b><?php echo $contestDetail["start_time"] ?></div>
                        <div class="flex justify-left"><b>Hora de Fin:</b><?php echo $contestDetail["end_time"] ?></div>
                        <div class="flex justify-center col-span-2">
                            <?php echo getStatusTime($contestDetail["start_time"], $contestDetail["end_time"]) ?>
                        </div>
                    </div>
                </div>
                <?php
                if (isset($cid) && intval($cid) > 0 &&
                    (
                        isset($_SESSION["Administrador"]) && $_SESSION["Administrador"] == "Administrador" ||
                        isset($_SESSION["Docente"])       && $_SESSION["Docente"]       == "Docente"       ||
                        isset($_SESSION["Auxiliar"])      && $_SESSION["Auxiliar"]      == "Auxiliar")
                ) {
                ?>
                    <div class="text-center mb-5">
                        <a href="./contestrankExcel.php?cid=<?php echo $cid ?>" class="text-blue-500 hover:underline">
                            <span class="w-full">Descargar Reporte</span>
                        </a>
                    </div>
                <?php
                }
                ?>
                <div class="p-1">
                    <div class="relative w-full overflow-auto">

                        <table class="border-b transition-colors hover:bg-muted/50 w-full">
                            <thead class="bg-gray-900 text-white">
                                <tr class="shadow-lg">
                                    <th class="p-1 font-bold text-lg border-r"></th>
                                    <th class="p-1 font-bold text-lg border-r">Problema</th>
                                    <th class="p-1 font-bold text-lg border-r">Nombre</th>
                                    <th class="p-1 font-bold text-lg border-r">Setter</th>
                                    <th class="p-1 font-bold text-lg border-r">Aceptados</th>
                                    <th class="p-1 font-bold text-lg">Envios</th>
                                </tr>
                            </thead>
                            <tbody class="content-center">
                                <?php
                                if (!empty($error)) {
                                    echo "<th colspan=6 class='result-red p-1 font-bold text-lg border-r'>$error</th>";
                                }
                                $letter = 65;
                                $xtra_letter = "";
                                $numeration = 1;
                                foreach ($contestProblemList as $key => $value) {
                                    $css = "oddrow";
                                    if ($key % 2 == 0) {
                                        $css = "evenrow";
                                    }
                                    echo '<tr class="border-b transition-colors hover:bg-muted/50 data-[state=selected]:bg-muted ' . $css . '">';
                                    echo '<td class="p-1 text-center align-middle">';
                                    if (isset($user_id) && isset($resolveBy[$user_id]) && in_array($value["pnum"], $resolveBy[$user_id])) {
                                        echo "Y";
                                    }
                                    echo "</td>";
                                    echo '<td class="p-1 text-center align-middle">' . $numeration . " " . ' ' . chr($letter) . "" . $xtra_letter . '</td>';
                                    $problemUrl = sprintf("problem.php?cid=%d&pid=%d", $cid, $value["pnum"]);
                                    if (isset($cType) && $cType == "official_contest") {
                                        $problemUrl = sprintf("problem.php?cid=%d&pid=%d&type=%s", $cid, $value["pnum"], $cType);
                                    }
                                    $vibeIdeUrl = ($_SERVER['APP_PREFIX_ROUTE'] ?? '/oj') . '/vibe-ide-launch.php?' . http_build_query([
                                        'contestId' => intval($cid),
                                        'cid' => intval($cid),
                                        'num' => intval($value["pnum"]),
                                        'pid' => intval($value["pnum"]),
                                    ]);
                                    echo '<td class="p-1 text-center align-middle result-blue">
                                            <a href="' . $problemUrl . '">' . $value["title"] . '</a>
                                            <a class="ml-2 text-sky-600 hover:text-sky-800" target="_blank" rel="noopener noreferrer" href="' . htmlspecialchars($vibeIdeUrl, ENT_QUOTES, 'UTF-8') . '">IDE</a></td>';
                                    echo '<td class="p-1 text-center align-middle">' . $value["source"] . '</td>';
                                    echo '<td class="p-1 text-center align-middle">' . $value["accepted"] . '</td>';
                                    echo '<td class="p-1 text-center align-middle">' . $value["submit"] . '</td>';
                                    echo '</tr>';
                                    if ($letter == 90) {
                                        $letter = 64;
                                        $xtra_letter = intval($xtra_letter) + 1;
                                    }
                                    $letter = intval($letter) + 1;
                                    $numeration = intval($numeration) + 1;
                                }
                                ?>
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
        </div>

    </main>

    <?php require_once "oj-footer.php" ?>

</body>

</html>