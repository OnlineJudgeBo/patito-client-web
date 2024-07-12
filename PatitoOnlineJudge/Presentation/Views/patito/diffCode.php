<!DOCTYPE html>
<html lang="es" class="h-full">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title ?></title>
    <link href="https://fonts.googleapis.com/css?family=Capriola" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="./assets/base.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.11.0/codemirror.min.js"></script>
    <script type="text/javascript" src="https://cdn.rawgit.com/wickedest/Mergely/3.4.1/lib/mergely.js"></script>
    <link type="text/css" rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.11.0/codemirror.min.css" />
    <link type="text/css" rel="stylesheet" href="https://cdn.rawgit.com/wickedest/Mergely/3.4.0/lib/mergely.css" />
    <style>
        .mergely.ch.d.lhs {
            background-color: #ddeeff;
            text-decoration: none;
            color: black !important;
        }

        .diffs {
            margin-left: 0;
        }
    </style>
    <script>
        function getWinHeight() {
            return window.innerHeight || document.body.clientHeight;
        }

        function getWinWidth() {
            return window.innerWidth || document.body.clientWidth;
        }

        $(document).ready(function() {
            var comp = $('#compare');
            comp.mergely({
                cmsettings: {
                    readOnly: false,
                    lineWrapping: true
                },
                wrap_lines: true,
                lcs: true,
                viewport: false,
                lhs: function(setValue) {
                    setValue(`<?php echo $sourceDetail["source"] ?>
                    <?php
                    echo "\n\n\n\n";
                    include(__DIR__ . "/../../../../Legacy/Include/const.inc.php");
                    $comment = str_contains($language_name[$sourceDetail["language"]], "Python") ? "#" : "/";
                    echo $comment . "**************************************************************$comment\n";
                    echo "Solución: " . $sourceDetail["solution_id"] . "\n";
                    echo "Problema: " . $sourceDetail["problem_id"] . "\nUsuario: " . $sourceDetail["user_id"] . "\n";
                    echo "Lenguaje: " . $language_name[$sourceDetail["language"]] . "\nResult: " . $judge_result[$sourceDetail["result"]] . "\n";
                    if ($sourceDetail["result"] == 4) {
                        echo "Time:" . $sourceDetail["time"] . " ms\n";
                        echo "Memory:" . $sourceDetail["memory"] . " kb\n";
                    }
                    echo $comment . "**************************************************************$comment\n";
                    ?>
                    `)
                },
                rhs: function(setValue) {
                    setValue(`<?php echo $sourceDetail2["source"] ?>
                    <?php
                    echo "\n\n\n\n";
                    include(__DIR__ . "/../../../../Legacy/Include/const.inc.php");
                    $comment = str_contains($language_name[$sourceDetail2["language"]], "Python") ? "#" : "/";
                    echo $comment . "**************************************************************$comment\n";
                    echo "Solución: " . $sourceDetail2["solution_id"] . "\n";
                    echo "Problema: " . $sourceDetail2["problem_id"] . "\nUsuario: " . $sourceDetail2["user_id"] . "\n";
                    echo "Lenguaje: " . $language_name[$sourceDetail2["language"]] . "\nResult: " . $judge_result[$sourceDetail2["result"]] . "\n";
                    if ($sourceDetail2["result"] == 4) {
                        echo "Time:" . $sourceDetail2["time"] . " ms\n";
                        echo "Memory:" . $sourceDetail2["memory"] . " kb\n";
                    }
                    echo $comment . "**************************************************************$comment\n";
                    ?>
                    `)
                }
            });

            function resizeMergely() {
                $('#compare').mergely('options', {
                    height: getWinHeight() - 190,
                    width: "100%"
                });
                $('#compare').mergely('update');
            }

            $(window).resize(resizeMergely);
            resizeMergely();
        });
    </script>

    <?php echo file_get_contents(__DIR__ . "/partials/utils-header.php"); ?>

</head>

<body class="flex flex-col h-full">
    <?php require_once "oj-header.php"; ?>
    <main class="container mx-auto p-4 grid grid-cols-1">
        <div class="diffs">
            <div class="compare-wrapper ml-0">
                <div id="compare" class="ml-0"></div>
            </div>
        </div>
    </main>
    <?php require_once "oj-footer.php"; ?>
</body>

</html>