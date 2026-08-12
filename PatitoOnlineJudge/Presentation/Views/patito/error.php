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
    <?php require "oj-header.php"; ?>
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
                    <div class="flex items-center justify-center py-4">
                        <div class="grid grid-cols-2 gap-1">
                            <div class="flex justify-left"><b>Hora de Inicio:</b><?php echo $contestDetail["start_time"] ?></div>
                            <div class="flex justify-left"><b>Hora de Fin:</b><?php echo $contestDetail["end_time"] ?></div>
                            <div class="flex justify-center col-span-2 font-semibold">
                                <?php echo closetags($contestDetail["description"]); ?>
                            </div>
                        </div>
                    </div>

                    <div class="p-1">
                        <div class="relative w-full overflow-auto">
                            <?php
                            echo "<th colspan=6 class='result-red p-1 font-bold text-lg border-r items-center	'>$error</th>";
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </main>

    <?php require_once "oj-footer.php" ?>

</body>

</html>