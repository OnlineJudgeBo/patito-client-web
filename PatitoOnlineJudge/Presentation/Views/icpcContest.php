<!DOCTYPE html>
<html lang="es" class="h-full">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title ?></title>
    <link href='https://fonts.googleapis.com/css?family=Capriola' rel='stylesheet'>
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
                    <h1 class="text-4xl font-bold leading-tight text-center text-blue-600 mb-4">
                        Bienvenido a la sección de concursos oficiales pasados<br>
                        Aquí podrás encontrar concursos anteriores de ICPC, OBI y OCE.
                    </h1>
                </div>
                <div class="p-1">
                    <div class="relative w-full overflow-auto">
                        <table class="border-b transition-colors hover:bg-muted/50 data-[state=selected]:bg-muted w-full">
                            <thead class="">
                                <tr class="border-b transition-colors hover:bg-muted/50 data-[state=selected]:bg-muted">
                                    <th class="p-4 font-semibold">Nombre</th>
                                    <th class="p-4 font-semibold">Descripción</th>
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
                                    echo '<td class="p-4 result-blue"><a href="icpcContest.php?cid=' . $value["contest_id"] . '">' . $value["title"] . '</td>';
                                    echo '<td class="p-4 result-blue"><a href="icpcContest.php?cid=' . $value["contest_id"] . '">' . $value["title"] . '</td>';
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