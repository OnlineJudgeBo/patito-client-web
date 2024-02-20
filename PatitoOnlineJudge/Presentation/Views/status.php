<!DOCTYPE html>
<html lang="en" class="h-full">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Juez Virtual Patito</title>
    <link href='https://fonts.googleapis.com/css?family=Capriola' rel='stylesheet'>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="./assets/base.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.0.0/css/dataTables.tailwindcss.css">
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script src="https://cdn.datatables.net/2.0.0/js/dataTables.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/searchpanes/2.3.0/css/searchPanes.dataTables.css">
    <script src="https://cdn.datatables.net/searchpanes/2.3.0/js/dataTables.searchPanes.js"></script>
    <script src="https://cdn.datatables.net/searchpanes/2.3.0/js/searchPanes.dataTables.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/select/2.0.0/css/select.dataTables.css">
    <script src="https://cdn.datatables.net/select/2.0.0/js/dataTables.select.js"></script>
    <script src="https://cdn.datatables.net/select/2.0.0/js/select.dataTables.js"></script>
    <style>
        .dt-paging.paging_full_numbers {
            display: flex;
            justify-content: flex-end;
        }

        div.dt-container .dt-paging .dt-paging-button {
            box-sizing: border-box;
            display: inline-block;
            min-width: 1.5em;
            padding: 0.5em 1em;
            margin-left: 2px;
            text-align: center;
            text-decoration: none !important;
            cursor: pointer;
            color: inherit !important;
            border: 1px solid transparent;
            border-radius: 2px;
            background: transparent;
        }

        div.dt-container .dt-paging .dt-paging-button.current,
        div.dt-container .dt-paging .dt-paging-button.current:hover {
            color: inherit !important;
            border: 1px solid rgba(0, 0, 0, 0.3);
            background-color: rgba(0, 0, 0, 0.05);
        }

        div.dt-container .dt-paging .dt-paging-button.disabled,
        div.dt-container .dt-paging .dt-paging-button.disabled:hover,
        div.dt-container .dt-paging .dt-paging-button.disabled:active {
            cursor: default;
            color: rgba(0, 0, 0, 0.5) !important;
            border: 1px solid transparent;
            background: transparent;
            box-shadow: none;
        }

        div.dt-container .dt-paging .dt-paging-button:hover {
            color: white !important;
            border: 1px solid #111;
            background-color: #111;
        }

        div.dt-container .dt-paging .dt-paging-button:active {
            outline: none;
            background-color: #0c0c0c;
            box-shadow: inset 0 0 3px #111;
        }

        div.dt-container .dt-paging .ellipsis {
            padding: 0 1em;
        }

        table.dataTable>tbody>tr.selected>* {
            box-shadow: inset 0 0 0 9999px rgba(13, 110, 253, 0.9);
            box-shadow: inset 0 0 0 9999px rgba(var(--dt-row-selected), 0.9);
            color: rgb(255, 255, 255);
            color: rgb(var(--dt-row-selected-text));
        }
        .dtsp-searchPane a {
        pointer-events: none;
        color: inherit;
    }
    </style>
</head>

<body class="flex flex-col h-full">
    <?php
    if (isset($cid) && intval($cid) > 0) {
        require_once "oj-header-contest.php";
    } else {
        require_once "oj-header.php";
    }
    ?>
    <main class="container mx-auto p-4 grid grid-cols-0">
        <div class="relative w-full overflow-auto">

            <table class="border-b transition-colors hover:bg-muted/50 w-full" id="status-table">
                <thead>
                    <tr class="border-b transition-colors hover:bg-muted/50">
                        <th class="p-2 font-semibold">RunID</th>
                        <th class="p-2 font-semibold">Usuario</th>
                        <th class="p-2 font-semibold">Problema</th>
                        <th class="p-2 font-semibold">Lenguaje</th>
                        <th class="p-2 font-semibold">Resultado</th>
                        <th class="p-2 font-semibold">Memoria</th>
                        <th class="p-2 font-semibold">Tiempo</th>
                        <th class="p-2 font-semibold">Hora de Envio</th>
                    </tr>
                </thead>

                <tbody>
                    <?php
                    require __DIR__ . "/../../../Legacy/Include/const.inc.php";
                    $showSource = "";
                    foreach ($statusViewList as $key => $value) {
                        $css = "evenrow";
                        if ($key % 2 == 0) {
                            $css = "oddrow";
                        }
                        if (!empty($value["contest_id"])) {
                            $url = "problem.php?cid=" . $value['contest_id'] . "&pid=" . $value['num'];
                            $user_url = "contestrank.php?cid=" . $value['contest_id'] . "&user_id=" . $value["user_id"] . "#" . $value["user_id"];
                        } else {
                            $url = "problem.php?id=" . $value['problem_id'];
                            $user_url = "userinfo.php?user=" . $value["user_id"];
                        }
                    ?>
                        <tr class="border-b transition-colors hover:bg-muted/50 <?php echo $css ?> ">
                            <td class="p-4">
                                <?php
                                if (isset($_SESSION["user_id"]) && $_SESSION["user_id"] == $value["user_id"] || isset($_SESSION["administrator"]) && $_SESSION["administrator"] == 1) {
                                    $showSource = "showsource.php?id=" . $value["solution_id"];
                                }
                                echo $value["solution_id"];
                                ?>
                            </td>
                            <td class="p-4">
                                <a class="text-blue-500 hover:text-blue-700 transition duration-300 ease-in-out" target='_blank' href="<?php echo $user_url ?>">
                                    <?php echo $value["user_id"] ?>
                                </a>
                            </td>
                            <td class="p-4">
                                <a class="text-blue-500 hover:text-blue-700 transition duration-300 ease-in-out" target='_blank' href="<?php echo $url ?>">
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
                                <a class="text-blue-500 hover:text-blue-700 transition duration-300 ease-in-out" target='_blank' href="<?php echo $showSource ?>">
                                    <?php echo $language_name[$value["language"]]; ?>
                                </a>
                            </td>
                            <td class="p-4">
                                <div class="font-bold decoration-solid decoration-sky-500 result-<?php echo $judge_color[$value["result"]] ?>">
                                    <?php
                                    if ($value["result"] <= 3) {
                                        echo  "<div class='pending'>" . $judge_result[$value["result"]] . "</div>";
                                    } elseif ($value["result"] == 4) {
                                        echo  $judge_result[$value["result"]];
                                    } else {
                                        echo sprintf("<a href='./showError.php?sid=%d' target='_blank' >%s</a>", $value["solution_id"], $judge_result[$value["result"]]);
                                    }
                                    ?>
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
<script>
    function reloadPage() {
        var celdasPending = document.querySelectorAll('#status-table .pending');
        if (celdasPending.length >= 1) {
            setTimeout(function() {
                window.location.reload();
            }, 3000);
        } else {
            setTimeout(reloadPage, 2000);
        }
    }
    reloadPage();

    let table = new DataTable('#status-table', {
        pageLength: 100,
        dom: 'Prtip',
        searchPanes: {
            cascadePanes: true,
            viewTotal: true,
        },
        select: false,
        columns: [{
                title: "RunID",
                searchPanes: {
                    show: false
                }
            },
            {
                title: "Usuario",
                searchPanes: {
                    show: true
                }
            },
            {
                title: "Problema",
                searchPanes: {
                    show: true
                }
            },
            {
                title: "Lenguaje",
                searchPanes: {
                    show: true
                }
            },
            {
                title: "Resultado",
                searchPanes: {
                    show: true
                }
            },
            {
                title: "Memoria",
                searchPanes: {
                    show: false
                }
            },
            {
                title: "Tiempo",
                searchPanes: {
                    show: false
                }
            },
            {
                title: "Hora de Envio",
                searchPanes: {
                    show: false
                }
            }
        ],
        language: {
            url: "https://cdn.datatables.net/plug-ins/1.10.19/i18n/Spanish.json",
            searchPanes: {
                count: "{total}",
                countFiltered: "{shown} ({total})",
                emptyPanes: "No hay paneles de búsqueda",
                clearMessage: "Limpiar todo",
                collapse: {
                    0: "Paneles de búsqueda",
                    _: "Paneles de búsqueda (%d)"
                },
                title: {
                    _: "Filtros Activos - %d",
                    0: "",
                    1: ""
                }
            }
        }
    });

    table.on('init.dt', function() {
        $('.dtsp-collapseAll').click();
        $('.dtsp-paneButton.dtsp-nameButton.dtsp-disabledButton').removeClass('dtsp-paneButton dtsp-nameButton dtsp-disabledButton');
        $('.dtsp-paneButton.dtsp-countButton').removeClass('dtsp-paneButton dtsp-countButton');
        $('.dtsp-collapseAll').text('Ocultar Filtros');
        $('.dtsp-showAll').text('Mostrar Filtros');
        $('.dtsp-titleRow').addClass('flex flex-row items-center');
        $('.dtsp-titleRow > button').addClass('text-black py-2 px-4 mr-2 mb-2 transition ease-in-out duration-150 shadow-md').each(function() {
            if ($(this).is('.dtsp-disabledButton, :disabled')) {
                $(this).addClass('bg-gray-300 border-gray-400 text-gray-500 cursor-not-allowed');
            } else {
                $(this).addClass('bg-blue-200 hover:bg-blue-300 focus:bg-blue-300 border-blue-300 hover:shadow-lg focus:shadow-lg');

                $(this).on('focus', function() {
                    $(this).addClass('ring ring-blue-300 ring-offset-2 ring-opacity-50');
                }).on('blur', function() {
                    $(this).removeClass('ring ring-blue-300 ring-offset-2 ring-opacity-50');
                });
            }
        });

    })

    $('.dtsp-searchPane').on('click', 'a', function(e) {
        e.preventDefault();
    });
</script>
</html>