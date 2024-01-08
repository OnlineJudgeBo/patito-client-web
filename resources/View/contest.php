<!DOCTYPE html>
<html lang="en" class="h-full">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Juez Virtual Patito</title>
    <link href='https://fonts.googleapis.com/css?family=Capriola' rel='stylesheet'>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="./assets/base.css">

</head>

<body class="flex flex-col h-full">

    <?php require_once "oj-header.php" ?>
    <main class="container mx-auto p-4">
    <div class="flex flex-col items-center">
        <div class="rounded-lg border bg-card text-card-foreground shadow-sm w-full">
            <div class="flex flex-col space-y-1.5 p-6">
                <h3 class="text-2xl font-semibold leading-none tracking-tight">Contest</h3>
            </div>
                <div class="flex justify-center">
                    Hora del servidor: <div class="nowdate"> </div>
                </div>
                <div class="p-1">
                    <div class="relative w-full overflow-auto">
                        <?php require __DIR__ . "/Modules/ServerTime.php"; ?>
                        <?php require __DIR__ . "/Modules/StatusTime.php"; ?>
                        <table class="border-b transition-colors hover:bg-muted/50 data-[state=selected]:bg-muted w-full">
                            <thead class="">
                                <tr class="border-b transition-colors hover:bg-muted/50 data-[state=selected]:bg-muted">
                                    <th class="p-4 font-semibold">ID</th>
                                    <th class="p-4 font-semibold">Nombre</th>
                                    <th class="p-4 font-semibold">Estado</th>
                                    <th class="p-4 font-semibold">Contest Privado?</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                foreach ($contest_list as $key => $value) {
                                    $css = "evenrow";
                                    if ($key % 2 == 0) {
                                        $css = "oddrow";
                                    }
                                    echo '<tr class="border-b transition-colors hover:bg-muted/50 data-[state=selected]:bg-muted ' . $css . '">';
                                    echo '<td class="p-4">' . $value["contest_id"] . '</td>';
                                    echo '<td class="p-4 result-blue">' . $value["title"] . '</td>';
                                    echo '<td class="p-4 result-green">' .getStatusTime($value["start_time"], $value["end_time"]). '</td>';
                                    if ($value["private"] == 0) {
                                        echo '<td class="p-4 result-blue">Publico</td>';
                                    } else {
                                        echo '<td class="p-4 result-red">Privado</td>';
                                    }
                                    echo '</tr>';
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