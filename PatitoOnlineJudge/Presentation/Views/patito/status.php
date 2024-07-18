<!DOCTYPE html>
<html lang="es" class="h-full">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title ?></title>
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
    <?php echo file_get_contents(__DIR__ . "/partials/utils-header.php"); ?>
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
            box-shadow: inset 0 0 0 9999px rgba(13, 110, 253, 0.3) !important;
            box-shadow: inset 0 0 0 9999px rgba(var(--dt-row-selected), 0.3) !important;
            color: #2c3e50 !important;
            color: rgb(var(--dt-row-selected-text)) !important;
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
            <div class="flex items-center p-3 bg-white border border-gray-300 rounded-lg shadow-sm hidden" id="history-panel">
                <img src="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.0/icons/search.svg" alt="Search" class="w-5 h-5 mr-3 text-gray-500">
                <input type="search" id="searchInput" placeholder="Escriba el nombre de usuario para buscar (histórico completo)" class="flex-1 outline-none" />
                <button onclick="performSearch()" class="ml-2 text-white bg-blue-500 hover:bg-blue-600 font-medium rounded-lg text-sm px-4 py-2">Buscar</button>
            </div>

            <table class="border-b transition-colors hover:bg-muted/50 w-full" id="status-table">
                <thead>
                    <tr class="border-b transition-colors hover:bg-muted/50">
                        <th class="p-2 font-semibold">RunID</th>
                        <th class="p-2 font-semibold">Usuario</th>
                        <th class="p-2 font-semibold">Problema</th>
                        <th class="p-2 font-semibold">Lenguaje</th>
                        <th class="p-2 font-semibold">Resultado</th>
                        <th class="p-2 font-semibold"></th>
                        <th class="p-2 font-semibold">Memoria</th>
                        <th class="p-2 font-semibold">Tiempo</th>
                        <th class="p-2 font-semibold">Hora de Envio</th>
                    </tr>
                </thead>

                <tbody>
                    <?php
                    require __DIR__ . "/../../../../Legacy/Include/const.inc.php";
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
                            $user_url = "status.php?user_id=" . $value["user_id"];
                        }
                    ?>
                        <tr class="border-b transition-colors hover:bg-muted/50 <?php echo $css ?> ">
                            <td class="p-4">
                                <?php
                                if (
                                    isset($_SESSION["user_id"])       && $_SESSION["user_id"] == $value["user_id"] ||
                                    isset($_SESSION["Administrador"]) && $_SESSION["Administrador"] == "Administrador" ||
                                    isset($_SESSION["Docente"])       && $_SESSION["Docente"]       == "Docente"       ||
                                    isset($_SESSION["Auxiliar"])      && $_SESSION["Auxiliar"]      == "Auxiliar"
                                ) {
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
                                        echo chr(65 + $value['num'] % 26) . "" . (intval($value['num'] / 26) > 0 ? intval($value['num'] / 26) : "");
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
                            <td class="p-4 flex items-center space-x-4">
                                <?php
                                if (
                                    isset($_SESSION["Administrador"]) && $_SESSION["Administrador"] == "Administrador" ||
                                    isset($_SESSION["Docente"])       && $_SESSION["Docente"]       == "Docente"       ||
                                    isset($_SESSION["Auxiliar"])      && $_SESSION["Auxiliar"]      == "Auxiliar"
                                ) {
                                    echo sprintf(
                                        '<button onclick="rejudgeSolution(%d)" class="border-b transition-colors hover:bg-muted/50">Rejudge</button>',
                                        $value["solution_id"]
                                    );
                                }
                                ?>
                                <div class="font-bold decoration-solid decoration-sky-500 result-<?php echo $judge_color[$value["result"]] ?>">
                                    <?php
                                    if ($value["result"] <= 3) {
                                        echo  "<div class='pending'>" . $judge_result[$value["result"]] . "</div>";
                                    } elseif ($value["result"] == 4) {
                                        echo  $judge_result[$value["result"]];
                                    } else {
                                        if (
                                            isset($_SESSION["user_id"])       && $_SESSION["user_id"] == $value["user_id"] ||
                                            isset($_SESSION["Administrador"]) && $_SESSION["Administrador"] == "Administrador" ||
                                            isset($_SESSION["Docente"])       && $_SESSION["Docente"]       == "Docente"       ||
                                            isset($_SESSION["Auxiliar"])      && $_SESSION["Auxiliar"]      == "Auxiliar"
                                        ) {
                                            echo sprintf("<a href='./showError.php?sid=%d' target='_blank' >%s</a>", $value["solution_id"], $judge_result[$value["result"]]);
                                        } else {
                                            echo $judge_result[$value["result"]];
                                        }
                                    }
                                    ?>
                                </div>
                            </td>
                            <td class="p-4">
                                <div class="font-bold decoration-solid decoration-sky-500">
                                    <?php
                                    if ($value["percentage"] > 51) {
                                        if (
                                            isset($_SESSION["user_id"])       && $_SESSION["user_id"] == $value["user_id"] ||
                                            isset($_SESSION["Administrador"]) && $_SESSION["Administrador"] == "Administrador" ||
                                            isset($_SESSION["Docente"])       && $_SESSION["Docente"]       == "Docente"       ||
                                            isset($_SESSION["Auxiliar"])      && $_SESSION["Auxiliar"]      == "Auxiliar"
                                        ) {
                                            echo "<a href=\"diff_code.php?solution_id=" . $value["solution_id"] . "&solution_id2=" . $value["similar_s_id"] . "\"><span class=\"text-xs align-super text-black\">[" . $value['similar_s_id'] . "] " . $value['percentage'] . "%</span></a>";
                                        } else {
                                            echo "<span class=\"text-xs align-super text-black\">[" . $value['similar_s_id'] . "] " . $value['percentage'] . "%</span>";
                                        }
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
        "order": [],
        pageLength: 100,
        dom: 'Prtip',
        searchPanes: {
            cascadePanes: true,
            viewTotal: true,
        },
        select: true,
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
                title: "",
                searchPanes: {
                    show: false
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
                title: "Hora de Envió",
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
        },
        initComplete: function(settings, json) {
            $('.dtsp-paneButton').on('click', function() {
                let userId = document.querySelector('.dtsp-paneInputButton.dtsp-search').value;
                if (userId.length > 2) {
                    window.location = "status.php?user_id=" + userId
                }
            });
        }
    });

    table.on('init.dt', function() {

        var newButton = document.createElement("button");
        newButton.type = "button";
        newButton.style = "border: 1px solid transparent;background-color: transparent;"
        newButton.className = "text-black py-2 px-4 mr-2 mb-2 transition ease-in-out duration-150 shadow-md bg-blue-200 hover:bg-blue-300 focus:bg-blue-300 border-blue-300 hover:shadow-lg focus:shadow-lg";
        newButton.textContent = "Buscar todos mis envíos";
        newButton.onclick = toggleSearch;

        var container = document.querySelector(".dtsp-titleRow");
        container.appendChild(newButton);

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

    function performSearch() {
        var userId = document.getElementById('searchInput').value;
        window.location = "status.php?user_id=" + userId
    }

    function toggleSearch() {
        var historyPanel = document.getElementById('history-panel');
        var statusTable = document.getElementById('status-table');
        var searchPanel = document.getElementsByClassName('dtsp-searchPanes');
        var dtInfo = document.getElementsByClassName('dt-info');
        var dtPaging = document.getElementsByClassName('paging_full_numbers');

        if (historyPanel.classList.contains('hidden')) {
            historyPanel.classList.remove('hidden');
            statusTable.classList.add('hidden');
            searchPanel[0].style.display = 'none'
            dtInfo[0].style.display = 'none'
            dtPaging[0].style.display = 'none'
        } else {
            historyPanel.classList.add('hidden');
            statusTable.classList.remove('hidden');
            searchPanel[0].style.display = ''
            dtInfo[0].style.display = ''
            dtPaging[0].style.display = ''
        }
    }
</script>
<script>
    function getCookieValue(name) {
        const value = document.cookie.match(`(^|;)\\s*${name}\\s*=\\s*([^;]+)`);
        return value ? value.pop() : null;
    }

    function rejudgeSolution(solutionId) {
        const token = getCookieValue('accessToken');

        if (!token) {
            console.error('No access token found in cookies');
            return;
        }

        fetch(`<?php echo $_SERVER["APP_DOMAIN_API"] ?>/Judge/rejudge/solution/${solutionId}`, {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                    'Authorization': `Bearer ${token}`
                }
            })
            .then(response => {
                if (response.ok) {
                    window.location.reload();
                } else {
                    throw new Error(response);
                }
            })
            .then(response => {
                console.log('Success:', response);
            })
            .catch(error => {
                console.error('Error:', error);
            });
    }
</script>

</html>