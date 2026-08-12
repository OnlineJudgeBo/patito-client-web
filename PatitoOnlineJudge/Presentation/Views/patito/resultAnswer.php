<!DOCTYPE html>
<html lang="es" class="h-full">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="./assets/highlight/highlight.min.js"></script>
    <link rel="stylesheet" href="./assets/highlight/styles/windows-95.css">
    <script src="//cdnjs.cloudflare.com/ajax/libs/highlightjs-line-numbers.js/2.8.0/highlightjs-line-numbers.min.js"></script>
    <?php echo file_get_contents(__DIR__."/partials/utils-header.php"); ?>
    <link rel="stylesheet" href="./assets/base.css">
</head>

<body class="flex flex-col h-full">

    <?php require_once "oj-header.php" ?>
    <?php require __DIR__ . "/Modules/Utils.php"; ?>
    <main class="w-full">
        <div class="max-w-md mx-auto bg-white rounded-xl shadow-md overflow-hidden md:max-w-2xl">
            <div class="md:flex">
                <div class="p-8">
                    <div class="uppercase tracking-wide text-sm text-indigo-500 font-semibold">Resultado de la ejecución</div>
                    <div class="mt-2 text-gray-900">
                        <pre class="whitespace-pre-wrap"><?php echo $result["error"]; ?></pre>
                    </div>
                </div>
            </div>
        </div>

    </main>

    <?php require_once "oj-footer.php" ?>
</body>

</html>