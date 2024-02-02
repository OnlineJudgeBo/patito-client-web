<!DOCTYPE html>
<html lang="en" class="h-full">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Juez Virtual Patito</title>
    <link href='https://fonts.googleapis.com/css?family=Capriola' rel='stylesheet'>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="./assets/base.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
</head>

<body class="flex flex-col h-full">
    <?php require_once "oj-header.php" ?>
    <main class="container mx-auto p-4 grid grid-cols-0">
        <div class="relative w-full overflow-auto">

            <table class="border-b transition-colors hover:bg-muted/50 w-full">
                <thead>
                    <tr class="border-b transition-colors hover:bg-muted/50">
                        <th class="p-4 font-semibold">RunID</th>
                        <th class="p-4 font-semibold">Usuario</th>
                        <th class="p-4 font-semibold">Problema</th>
                        <th class="p-4 font-semibold">Lenguaje</th>
                        <th class="p-4 font-semibold">Resultado</th>
                        <th class="p-4 font-semibold">Memoria</th>
                        <th class="p-4 font-semibold">Tiempo</th>
                        <th class="p-4 font-semibold">Hora de Envio</th>
                    </tr>
                </thead>

                <tbody>
                    <?php
                    require __DIR__ . "/../../../Legacy/Include/const.inc.php";
                    foreach ($statusViewList as $key => $value) {
                        $css = "evenrow";
                        if ($key % 2 == 0) {
                            $css = "oddrow";
                        }

                        if (!empty($value["contest_id"])) {
                            $url = "problem.php?cid=" . $value['contest_id'] . "pid=" . $value['problem_id'];
                            $user_url = "contestrank.php?cid=" . $value['contest_id'] . "&user_id=" . $value["user_id"] . "#" . $value["user_id"];
                        } else {
                            $url = "problem.php?id=" . $value['problem_id'];
                            $user_url = "userinfo.php?user=" . $value["user_id"];
                        }
                    ?>
                        <tr class="border-b transition-colors hover:bg-muted/50 <?php echo $css ?> ">
                            <td class="p-4">
                                <?php echo $value["solution_id"] ?>
                            </td>
                            <td class="p-4">
                                <a class="text-blue-500 hover:text-blue-700 transition duration-300 ease-in-out" href="<?php echo $user_url ?>">
                                    <?php echo $value["user_id"] ?>
                                </a>
                            </td>
                            <td class="p-4">
                                <a class="text-blue-500 hover:text-blue-700 transition duration-300 ease-in-out" href="<?php echo $url ?>">
                                    <?php 
                                    if (isset($value["contest_id"])) {
                                        echo $PID2[$value["num"]];
                                    } else {
                                        echo $value["problem_id"];
                                    }
                                    ?>
                                </a>
                            </td>
                            <td class="p-4">
                                <?php echo $language_name[$value["language"]] ?>
                            </td>
                            <td class="p-4">
                                <div class="font-bold decoration-solid decoration-sky-500 result-<?php echo $judge_color[$value["result"]] ?>">
                                    <?php echo $judge_result[$value["result"]] ?>
                                </div>
                            </td>
                            <td class="p-4">
                                <div class="font-bold decoration-solid decoration-sky-500 result-<?php echo $judge_color[$value["result"]] ?>">
                                    <?php echo $value["memory"] ?>
                                </div>
                            </td>
                            <td class="p-4">
                                <div class="font-bold decoration-solid decoration-sky-500 result-<?php echo $judge_color[$value["result"]] ?>">
                                    <?php echo $value["time"] ?>
                                </div>
                            </td>
                            <td class="p-4">
                                <?php echo $value["in_date"] ?>
                            </td>
                        <?php
                    }
                        ?>
                        </tr>
                </tbody>
            </table>
        </div>

        </div>

    </main>

    <?php require_once "oj-footer.php" ?>

</body>

</html>