<!DOCTYPE html>
<html lang="es" class="h-full">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title ?></title>
    <link href='https://fonts.googleapis.com/css?family=Capriola' rel='stylesheet'>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="./assets/base.css">
    <?php echo file_get_contents(__DIR__."/partials/utils-header.php"); ?>
    <script type="text/javascript" src="https://cdn.mathjax.org/mathjax/latest/MathJax.js?config=TeX-AMS-MML_HTMLorMML">
        MathJax.Hub.Config({
            tex2jax: {
                inlineMath: [
                    ['$', '$'],
                    ['\\(', '\\)']
                ],
                processEscapes: true
            }
        });
    </script>
</head>

<body class="flex flex-col h-full">
    <?php
    if (isset($cid) && intval($cid) > 0) {
        require "oj-header-contest.php";
    } else {
        require "oj-header.php";
    }
    ?>
    <?php require __DIR__ . "/Modules/StatusTime.php"; ?>
    <main class="container mx-auto p-4 grid grid-cols-0">
        <div class="col-span-2">

            <div class="rounded-lg border bg-card text-card-foreground shadow-sm">
                <div class="flex flex-col items-center p-6">
                    <div class="text-center">
                        <h2 class="text-xl font-bold tracking-tight text-black mb-1"><?php echo $problem["title"] ?></h2>

                        <div class="grid grid-cols-2 mb-2">
                            <div class="flex justify-center items-center">
                                <dt class="text-green-500 mr-1">Time Limit:</dt>
                                <dd><?php echo $problem["time_limit"] . " Sec" ?></dd>
                            </div>
                            <div class="flex">
                                <dt class="text-green-500 mr-1">Memory Limit:</dt>
                                <dd><?php echo $problem["memory_limit"] . "Mb" ?></dd>
                            </div>
                            <div class="flex justify-center items-center -mt-2">
                                <dt class="text-green-500 mr-1">Enviados:</dt>
                                <dd><?php echo $problem["submit"] ?></dd>
                            </div>
                            <div class="flex">
                                <dt class="text-green-500 mr-1 -mt-2">Resuelto:</dt>
                                <dd><?php echo $problem["accepted"] ?></dd>
                            </div>
                        </div>

                        <div class="flex justify-center gap-4">
                            <?php
                            if ($isContestActive) {
                            ?>
                                <a href="submitpage.php?<?php
                                                        if (isset($cid)) {
                                                            echo "cid=" . $cid . "&pid=" . $num;
                                                        } else {
                                                            echo "id=" . $problem["problem_id"];
                                                        }
                                                        ?>" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                                    Enviar
                                </a>
                            <?php
                            }
                            ?>
                            <a href="problemstatus.php?id=<?php echo $problem["problem_id"] ?>" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                                Estado
                            </a>
                            <?php
                            if (
                                isset($_SESSION["Administrador"]) && $_SESSION["Administrador"] == "Administrador" ||
                                isset($_SESSION["Docente"])       && $_SESSION["Docente"]       == "Docente"       ||
                                isset($_SESSION["Auxiliar"])      && $_SESSION["Auxiliar"]      == "Auxiliar"
                            ) {
                            ?>
                                <a href="/admin/problems/edit/<?php echo $problem["problem_id"] ?>" target="_blank" class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                                    Editar
                                </a>
                                <a href="/admin/fileManager/<?php echo $problem["problem_id"] ?>" target="_blank" class="bg-orange-500 hover:bg-orange-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                                    TestData
                                </a>
                            <?php
                            }
                            ?>
                        </div>
                    </div>
                </div>

                <div class="ml-2 mx-auto rounded">
                    <div class="mb-4">
                        <h2 class="text-xl font-bold tracking-tight text-black mb-1">Descripción</h2>
                        <div class="text-gray-600 bg-gray-100 text-gray-800 p-4">
                            <?php echo $problem["description"] ?>
                        </div>
                    </div>

                    <div class="mb-4">
                        <h3 class="text-xl font-bold tracking-tight text-black mb-1">Entrada</h3>
                        <div class="text-gray-600 bg-gray-100 text-gray-800 p-4">
                            <?php echo $problem["input"] ?>
                        </div>
                    </div>

                    <div class="mb-4">
                        <h3 class="text-xl font-bold tracking-tight text-black mb-1">Salida</h3>
                        <div class="text-gray-600 bg-gray-100 text-gray-800 p-4">
                            <?php echo $problem["output"] ?>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-4">
                        <div>
                            <h3 class="text-xl font-bold tracking-tight text-black mb-1 inline-block">Ejemplo Entrada</h3>
                            <a href="javascript:CopyToClipboard('samplein')" class="inline-block ml-2">
                                <img src="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/icons/clipboard.svg" alt="Copy icon" class="w-5 h-5">
                            </a>
                            <div class="bg-gray-100 p-2 rounded mt-2">
                                <pre class="text-gray-600 bg-gray-100 text-gray-800 p-4" id="samplein"><?php echo $problem["sample_input"]; ?></pre>
                            </div>
                        </div>

                        <div>
                            <h3 class="text-xl font-bold tracking-tight text-black mb-1 inline-block">Ejemplo Salida</h3>
                            <a href="javascript:CopyToClipboard('#sampleout')" alt="Click para copiar" class="inline-block ml-2">
                                <img src="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/icons/clipboard.svg" alt="Copy icon" class="w-5 h-5">
                            </a>
                            <div class="bg-gray-100 p-2 rounded">
                                <pre class="text-gray-600 bg-gray-100 text-gray-800 p-4" id="sampleout"><?php echo $problem["sample_output"] ?></pre>
                            </div>
                        </div>
                    </div>

                    <div class=" mb-4">
                        <h3 class="text-xl font-bold tracking-tight text-black mb-1">Ayuda</h3>
                        <div class="text-gray-600 bg-gray-100 text-gray-800 p-4"><?php echo $problem["hint"] ?></div>
                    </div>
                </div>

            </div>
        </div>

    </main>

    <?php require_once "oj-footer.php" ?>
    <script>
        function CopyToClipboard(id) {
            const textToCopy = document.getElementById(id).innerText;

            navigator.clipboard.writeText(textToCopy).then(() => {
                console.log('Text successfully copied to clipboard');
            })
            .catch(err => {
                console.error('Failed to copy text: ', err);
            });
        }

    </script>

</body>

</html>