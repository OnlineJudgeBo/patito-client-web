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
                    require __DIR__ . "/../../Legacy/Include/const.inc.php";
                    foreach ($statusViewList as $key => $value) {
                        $css = "evenrow";
                        if ($key % 2 == 0) {
                            $css = "oddrow";
                        }
                        echo '<tr class="border-b transition-colors hover:bg-muted/50 ' . $css . '">';
                        echo '<td class="p-4"><a class="text-blue-500 hover:text-blue-700 transition duration-300 ease-in-out" href="problem.php?cid=2790&amp;pid=25">' . $value["solution_id"] . '</a> </td>';
                        echo '<td class="p-4"><a class="text-blue-500 hover:text-blue-700 transition duration-300 ease-in-out" href="problem.php?cid=2790&amp;pid=25">' . $value["user_id"] . '</a> </td>';
                        echo '<td class="p-4">' . $value["problem_id"] . '</td>';
                        echo '<td class="p-4">' . $language_name[$value["language"]] . '</td>';
                        echo '<td class="p-4">' . '<div class="font-bold decoration-solid decoration-sky-500 result-' . $judge_color[$value["result"]] . '">' . $judge_result[$value["result"]] . '</div>' . '</td>';
                        echo '<td class="p-4">' . '<div class="font-bold decoration-solid decoration-sky-500 result-' . $judge_color[$value["result"]] . '">' . $value["memory"] . '</div>' . '</td>';
                        echo '<td class="p-4">' . '<div class="font-bold decoration-solid decoration-sky-500 result-' . $judge_color[$value["result"]] . '">' . $value["time"] . '</div>' . '</td>';
                        echo '<td class="p-4">' . $value["in_date"] . '</td>';
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