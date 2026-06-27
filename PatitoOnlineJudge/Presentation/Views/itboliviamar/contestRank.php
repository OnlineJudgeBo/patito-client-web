<!DOCTYPE html>
<html lang="es" class="h-full">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title ?></title>
    <link href='https://fonts.googleapis.com/css?family=Capriola' rel='stylesheet'>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <link rel="stylesheet" href="./assets/base.css">
</head>

<body class="flex flex-col h-full">

    <?php
    if (isset($cid) && intval($cid) > 0) {
        require_once "oj-header-contest.php";
    } else {
        require_once "oj-header.php";
    }
    ?>
    <?php require __DIR__ . "/../Modules/Utils.php"; ?>
    <main class="oj-page">
        <div class="flex flex-col items-center">
            <div class="oj-card w-full">
                <div class="flex flex-col space-y-1.5 p-6 items-center">
                    <p class="oj-eyebrow">Resultados en tiempo real</p>
                    <h1 class="oj-page-title text-center">
                        <?php
                        echo $contest["contest_id"] . " - " . closetags($contest["title"]);
                        ?>
                    </h1>

                </div>
                <div class="flex justify-center">
                    Hora del servidor: <div class="nowdate"> </div>
                </div>
                <div class="p-1">
                    <div class="relative w-full overflow-auto">
                        <?php require __DIR__ . "/../Modules/ServerTime.php"; ?>
                        <?php require __DIR__ . "/../Modules/StatusTime.php"; ?>

                        <?php
                        if (isset($cid) && intval($cid) > 0 && 
                        isset($_SESSION["Administrador"]) && $_SESSION["Administrador"] == "Administrador" ||
                        isset($_SESSION["Docente"])       && $_SESSION["Docente"]       == "Docente"       ||
                        isset($_SESSION["Auxiliar"])      && $_SESSION["Auxiliar"]      == "Auxiliar") {
                        ?>
                            <div class="text-center mb-5">
                                <a href="./contestrankExcel.php?cid=<?php echo $cid ?>" class="text-blue-500 hover:underline">
                                    <span class="w-full">Descargar Reporte</span>
                                </a>
                            </div>
                        <?php
                        }
                        ?>
                        <div class="overflow-x-auto relative shadow-lg rounded-lg">
                            <table class="oj-table text-sm">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">NOMBRE</th>
                                        <th scope="col">USUARIO</th>
                                        <th scope="col">RESUELTOS</th>
                                        <!-- <th scope="col">PENALIDAD</th> -->
                                        <?php
                                        foreach ($problems as $key => $value) {
                                            echo "<th scope='col' class='py-3 px-2'>".chr(65 + $key % 26) . "" . (intval($key / 26) > 0 ? intval($key / 26) : "")."</th>";
                                        }
                                        ?>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if (empty($contestRank)) {
                                        echo '<tr><td colspan="' . (count($problems) + 4) . '" class="oj-empty-state"><strong>Aún no hay participantes clasificados</strong>La tabla se actualizará cuando existan envíos en el concurso.</td></tr>';
                                    }
                                    foreach ($contestRank as $index => $row) {
                                        $css = "oddrow";
                                        if ($index % 2 == 0) {
                                            $css = "evenrow";
                                        }
                                    ?>
                                        <tr class="border-b hover:bg-gray-100 <?php echo $css ?>">
                                            <td><span class="oj-rank-position" data-position="<?php echo ($index + 1) ?>"><?php echo ($index + 1) ?></span></td>
                                            <td class="px-2"><?php echo htmlspecialchars($row->nick) ?></td>
                                            <td class="px-2"><?php echo htmlspecialchars($row->user_id) ?></td>
                                            <td class="px-2"><span class="oj-stat"><?php echo $row->solved ?></span></td>
                                            <!--
                                            <td class="px-2"><?php echo $sec2str($row->time) ?></td>
                                            -->

                                        <?php
                                        for ($j = 0; $j < count($problems); $j++) {
                                            $backgroundColor = "";
                                            if (
                                                isset($row->p_ac_sec[$j]) 
                                                //FIX ME&&
                                                //$row->p_ac_sec[$j] > 0
                                            ) {
                                                $colorIntensity = 0x33 + $row->p_wa_num[$j] * 32;
                                                $colorIntensity = $colorIntensity > 0xaa ? 0xaa : $colorIntensity;
                                                $hexColorIntensity = dechex($colorIntensity);
                                                $backgroundColor = "$hexColorIntensity" . "ff" . "$hexColorIntensity";
                                                if ($row->user_id == $first_blood[$j]) {
                                                    $backgroundColor = "aaaaff";
                                                }
                                            } elseif (
                                                isset($row->p_wa_num[$j]) &&
                                                $row->p_wa_num[$j] > 0
                                            ) {
                                                $colorDecrease = 0xaa - $row->p_wa_num[$j] * 10;
                                                $colorDecrease = $colorDecrease > 16 ? $colorDecrease : 16;
                                                $hexColorDecrease = dechex($colorDecrease);
                                                $backgroundColor = "ff$hexColorDecrease$hexColorDecrease";
                                            }

                                            echo "<td class=well style='padding:1px;background-color:#$backgroundColor'>";
                                            if (isset($row)) {
                                                if (
                                                    isset($row->p_ac_sec[$j]) 
                                                    //&&
                                                    //$row->p_ac_sec[$j] > 0
                                                ) {
                                                    echo $sec2str($row->p_ac_sec[$j]);
                                                }
                                                if ($obi == 1) {
                                                    if ($row->pass_rate[$j] > 0) {
                                                        echo " (" . intval($row->pass_rate[$j]) . "%)";
                                                    } else {
                                                        if (
                                                            isset($row->p_wa_num[$j]) &&
                                                            $row->p_wa_num[$j] > 0
                                                        ) {
                                                            echo "(-" . $row->p_wa_num[$j] . ")";
                                                        }
                                                    }
                                                } else {
                                                    if (
                                                        isset($row->p_wa_num[$j]) &&
                                                        $row->p_wa_num[$j] > 0
                                                    ) {
                                                        echo "(-" . $row->p_wa_num[$j] . ")";
                                                    }
                                                }
                                            }
                                        }
                                        echo "</tr>\n";
                                    }
                                        ?>
                                        </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </main>

    <?php require_once "oj-footer.php" ?>

</body>

</html>
