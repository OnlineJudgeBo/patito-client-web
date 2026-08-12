<?php
include(__DIR__ . "/../../../../Legacy/Include/const.inc.php");

function sourceLabel($sourceDetail, $language_name, $judge_result): string
{
    if (!$sourceDetail) {
        return '';
    }

    $language = $language_name[$sourceDetail["language"]] ?? $sourceDetail["language"] ?? 'N/D';
    $result = $judge_result[$sourceDetail["result"]] ?? $sourceDetail["result"] ?? 'N/D';

    return sprintf(
        "Solución: %s\nProblema: %s\nUsuario: %s\nLenguaje: %s\nResultado: %s",
        $sourceDetail["solution_id"] ?? 'N/D',
        $sourceDetail["problem_id"] ?? 'N/D',
        $sourceDetail["user_id"] ?? 'N/D',
        $language,
        $result
    );
}

$leftSource = $sourceDetail ? (string)($sourceDetail["source"] ?? '') . "\n\n/*\n" . sourceLabel($sourceDetail, $language_name, $judge_result) . "\n*/\n" : '';
$rightSource = $sourceDetail2 ? (string)($sourceDetail2["source"] ?? '') . "\n\n/*\n" . sourceLabel($sourceDetail2, $language_name, $judge_result) . "\n*/\n" : '';
?>
<!DOCTYPE html>
<html lang="es" class="h-full">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="./assets/base.css">
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
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
    <?php echo file_get_contents(__DIR__ . "/partials/utils-header.php"); ?>
</head>

<body class="flex flex-col h-full">
    <?php require_once "oj-header.php"; ?>
    <main class="container mx-auto p-4 grid grid-cols-1">
        <?php if (!empty($errorMessage)): ?>
            <div class="mb-4 text-red-700">
                <?php echo htmlspecialchars((string)$errorMessage, ENT_QUOTES, 'UTF-8'); ?>
            </div>
        <?php elseif ($comparison): ?>
            <div class="mb-3 text-xl font-bold">
                Similitud: <?php echo htmlspecialchars(number_format($comparison["similarity_percentage"], 2), ENT_QUOTES, 'UTF-8'); ?>%
            </div>
        <?php endif; ?>

        <?php if (empty($errorMessage)): ?>
            <div class="diffs">
                <div class="compare-wrapper ml-0">
                    <div id="compare" class="ml-0"></div>
                </div>
            </div>
        <?php endif; ?>
    </main>
    <?php require_once "oj-footer.php"; ?>

    <?php if (empty($errorMessage)): ?>
        <script>
            const leftSource = <?php echo json_encode($leftSource, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT); ?>;
            const rightSource = <?php echo json_encode($rightSource, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT); ?>;

            function getWinHeight() {
                return window.innerHeight || document.body.clientHeight;
            }

            $(document).ready(function() {
                const comp = $('#compare');
                comp.mergely({
                    cmsettings: {
                        readOnly: true,
                        lineWrapping: true
                    },
                    wrap_lines: true,
                    lcs: true,
                    viewport: false,
                    lhs: function(setValue) {
                        setValue(leftSource);
                    },
                    rhs: function(setValue) {
                        setValue(rightSource);
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
    <?php endif; ?>
</body>

</html>
