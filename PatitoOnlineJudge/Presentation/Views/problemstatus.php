<!DOCTYPE html>
<html lang="en" class="h-full">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Juez Virtual Patito</title>
    <link href='https://fonts.googleapis.com/css?family=Capriola' rel='stylesheet'>
    <script src="https://cdn.tailwindcss.com"></script>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <link rel="stylesheet" href="./assets/base.css">
    <style>
        #piechart {
            margin-top: -128px !important;
            background-color: transparent !important;
        }
    </style>    
</head>

<body class="flex flex-col h-full">
    <?php require_once "oj-header.php"?>
    <?php require __DIR__ . "/Modules/StatusTime.php";?>
    <main class="container mx-auto p-4 grid grid-cols-0">
        <div class="col-span-2">

            <div class="rounded-lg border bg-card text-card-foreground shadow-sm">
                <div class="flex flex-col items-center p-6 w-full h-full">
                    <div class="text-center">
                        <h2 class="text-xl font-bold tracking-tight text-black mb-1">
                            Problema: <?php echo $problem["title"]?>
                        </h2>

                        <div class="flex">
                            <div class="w-auto">

                                <table class="border-collapse  border-slate-500 hover:table-fixed md:table-fixed hover:table-fixed mr-8">
                                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                        <tr class="hover:bg-gray-100 dark:hover:bg-gray-150">
                                            <td class="p-4 font-semibold">Envios</td>
                                            <td class="p-4"><?php echo $userStatics["total_submits"]?></td>
                                        </tr>

                                        <tr class="hover:bg-gray-100 dark:hover:bg-gray-150">
                                            <td class="p-4 font-semibold">AC</td>
                                            <td class="p-4"><?php echo $userStatics["total_ac"]?></td>
                                        </tr>

                                        <tr class="hover:bg-gray-100 dark:hover:bg-gray-700">
                                            <td class="p-4 font-semibold">PE</td>
                                            <td class="p-4"><?php echo $userStatics["total_pe"]?></td>
                                        </tr>

                                        <tr class="hover:bg-gray-100 dark:hover:bg-gray-700">
                                            <td class="p-4 font-semibold">WA</td>
                                            <td class="p-4"><?php echo $userStatics["total_wa"]?></td>
                                        </tr>

                                        <tr class="hover:bg-gray-100 dark:hover:bg-gray-700">
                                            <td class="p-4 font-semibold">TLE</td>
                                            <td class="p-4"><?php echo $userStatics["total_tle"]?></td>
                                        </tr>

                                        <tr class="hover:bg-gray-100 dark:hover:bg-gray-700">
                                            <td class="p-4 font-semibold">OLE</td>
                                            <td class="p-4"><?php echo $userStatics["total_ole"]?></td>
                                        </tr>

                                        <tr class="hover:bg-gray-100 dark:hover:bg-gray-700">
                                            <td class="p-4 font-semibold">RE</td>
                                            <td class="p-4"><?php echo $userStatics["total_re"]?></td>
                                        </tr>

                                        <tr class="hover:bg-gray-100 dark:hover:bg-gray-700">
                                            <td class="p-4 font-semibold">CE</td>
                                            <td class="p-4"><?php echo $userStatics["total_ce"]?></td>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                                <br>
                                <div id="piechart" style="width: 400px; height: 500px;"></div>
                            </div>

                            <div class="flex-1">
                                <div class="-m-1.5 overflow-x-auto">
                                    <div class="p-1.5 inline-block align-middle">
                                        <div class="overflow-hidden">
                                            <table class="border-collapse border-slate-500 hover:table-fixed md:table-fixed hover:table-fixed ml-5">
                                                <thead>
                                                    <tr class="transition-colors hover:bg-muted/50">
                                                        <th scope="col" class="p-4 font-semibold">No</th>
                                                        <th scope="col" class="p-4 font-semibold">RunId</th>
                                                        <th scope="col" class="p-4 font-semibold">Usuario</th>
                                                        <th scope="col" class="p-4 font-semibold">Memoria</th>
                                                        <th scope="col" class="p-4 font-semibold">Tiempo</th>
                                                        <th scope="col" class="p-4 font-semibold">Lenguaje</th>
                                                        <th scope="col" class="p-4 font-semibold">Tamaño</th>
                                                        <th scope="col" class="p-4 font-semibold">Fecha</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="divide-y divide-x divide-z divide-gray-200 dark:divide-gray-700">
                                                    <?php
                                                    foreach ($topUsersByProblem as $index => $value) {
                                                        $css = "evenrow";
                                                        if ($index % 2 == 0) {
                                                            $css = "oddrow";
                                                        }
                                                   ?>
                                                        <tr class="transition-colors hover:bg-muted/50  <?php echo $css?>">
                                                            <td class="p-4"><?php echo ($index + 1)?></td>
                                                            <td class="p-4"><?php echo $value["solution_id"]?></td>
                                                            <td class="p-4"><?php echo $value["user_id"]?></td>
                                                            <td class="p-4"><?php echo intval($value["s_memory"])?> KB</td>
                                                            <td class="p-4"><?php echo intval($value["s_time"])?> MS</td>
                                                            <td class="p-4"><?php echo $value["language"]?> B</td>
                                                            <td class="p-4"><?php echo $value["s_cl"]?></td>
                                                            <td class="p-4"><?php echo $value["in_date"]?></td>
                                                        </tr>
                                                    <?php
                                                    }
                                                   ?>

                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </main>

    <?php require_once "oj-footer.php"?>
</body>

<script type="text/javascript">
    google.charts.load('current', {
        'packages': ['corechart']
    });
    google.charts.setOnLoadCallback(drawChart);

    function drawChart() {
        var total_ac = <?php echo $userStatics["total_ac"]?>;
        var total_pe = <?php echo $userStatics["total_pe"]?>;
        var total_wa = <?php echo $userStatics["total_wa"]?>;
        var total_tle = <?php echo $userStatics["total_tle"]?>;
        var total_ole = <?php echo $userStatics["total_ole"]?>;
        var total_re = <?php echo $userStatics["total_re"]?>;
        var total_ce = <?php echo $userStatics["total_ce"]?>;
        
        var total = total_ac + total_pe + total_wa + total_tle + total_ole + total_re + total_ce;
        
        var data = google.visualization.arrayToDataTable([
            ['', ''],
            ['AC (' + ((total_ac / total) * 100).toFixed(2) + '%)', total_ac],
            ['PE (' + ((total_pe / total) * 100).toFixed(2) + '%)', total_pe],
            ['WA (' + ((total_wa / total) * 100).toFixed(2) + '%)', total_wa],
            ['TLE (' + ((total_tle / total) * 100).toFixed(2) + '%)', total_tle],
            ['OLE (' + ((total_ole / total) * 100).toFixed(2) + '%)', total_ole],
            ['RE (' + ((total_re / total) * 100).toFixed(2) + '%)', total_re],
            ['CE (' + ((total_ce / total) * 100).toFixed(2) + '%)', total_ce],
        ]);

        var options = {
            backgroundColor: 'transparent',
            colors: ['#8BC34A', '#FFEB3B', '#FF9800', '#9C27B0', '#3F51B5', '#9E9E9E', '#FF0000'],
            chartArea: {left: 0, top: 0, width: '100%', height: '100%'},
            tooltip: {text: 'percentage'},
            pieSliceText: 'percentage',
            legend: { position: 'right', alignment: 'center' }
        };

        var chart = new google.visualization.PieChart(document.getElementById('piechart'));
        chart.draw(data, options);
    }
</script>



</html>