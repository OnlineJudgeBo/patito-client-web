<!DOCTYPE html>
<html lang="es" class="h-full">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title ?></title>
    <link href='https://fonts.googleapis.com/css?family=Capriola' rel='stylesheet'>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="./assets/highlight/highlight.min.js"></script>
    <link rel="stylesheet" href="./assets/highlight/styles/nnfx-light.min.css">
    <script src="//cdnjs.cloudflare.com/ajax/libs/highlightjs-line-numbers.js/2.8.0/highlightjs-line-numbers.min.js"></script>
    <?php echo file_get_contents(__DIR__."/partials/utils-header.php"); ?>

    <link rel="stylesheet" href="./assets/base.css">
    <style>
        textarea,
        pre {
            width: 100%;
            height: 100%;
            border: 1px solid #ddd;
            margin: 10px;
            padding: 10px;
        }

        pre {
            background-color: #f4f4f4;
            overflow: auto;
        }

        .hljs-ln-numbers {
            -webkit-touch-callout: none;
            -webkit-user-select: none;
            -khtml-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;

            text-align: center;
            color: #ccc;
            border-right: 1px solid #CCC;
            vertical-align: top;
            padding-right: 5px;
        }

        .hljs-ln td {
            padding: 11;
        }
    </style>

</head>

<body class="flex flex-col h-full">

    <?php require_once "oj-header.php" ?>
    <?php require __DIR__ . "/Modules/Utils.php"; ?>
    <?php
    include(__DIR__ . "/../../../../Legacy/Include/const.inc.php");
    $language = "";
    if (str_contains($language_name[$sourceDetail["language"]], "Python") === true) {
        $language = "python";
    } elseif (str_contains($language_name[$sourceDetail["language"]], "C") === true) {
        $language = "cpp";
    } elseif (str_contains($language_name[$sourceDetail["language"]], "Java") === true) {
        $language = "java";
    }
    ?>
    <main class="w-full">
        <div class="flex flex-col items-center">
            <div class="rounded-lg border bg-card text-card-foreground shadow-sm w-full">
                <div class="p-1">
                    <div class="relative w-full h-full">
                        <pre><code class="language-<?php echo $language ?>"><?php echo htmlspecialchars($sourceDetail["source"]); ?>



<?php
include(__DIR__ . "/../../../../Legacy/Include/const.inc.php");
$comment = "/";
if (str_contains($language_name[$sourceDetail["language"]], "Python") === true) {
    $comment = "#";
}
echo $comment . "**************************************************************$comment\n";
echo "Problema: " . $sourceDetail["problem_id"] . "\nUsuario: " . $sourceDetail["user_id"] . "\n";
echo "Lenguaje: " . $language_name[$sourceDetail["language"]] . "\nResult: " . $judge_result[$sourceDetail["result"]] . "\n";
if ($sourceDetail["result"] == 4) {
    echo "Time:" . $sourceDetail["time"] . " ms\n";
    echo "Memory:" . $sourceDetail["memory"] . " kb\n";
}
echo $comment . "**************************************************************$comment\n";
?>
                    </code></pre>
                    </div>
                </div>
            </div>
        </div>

    </main>

    <?php require_once "oj-footer.php" ?>
    <script type="text/javascript">
        hljs.initLineNumbersOnLoad();
        hljs.highlightAll();
        document.querySelectorAll('code').forEach(el => {
            hljs.highlightElement(el);
        });
    </script>
</body>

</html>