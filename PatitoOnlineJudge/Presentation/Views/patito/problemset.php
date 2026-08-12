<!DOCTYPE html>
<html lang="es" class="h-full">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="./assets/base.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
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
    <?php require_once "oj-header.php" ?>
    <main class="container mx-auto p-4 mt-5">
        <div class="col-span-2">

            <table id="problemList" class="display" style="width:100%">
                <thead>
                    <tr>
                        <th></th>
                        <th>Id</th>
                        <th>Nombre</th>
                        <th>Setter</th>
                        <th>Resueltos</th>
                        <th>Enviados</th>
                    </tr>
                </thead>
            </table>
            <style>
                .wide-column {
                    width: 80% !important;
                }

                #problemList thead th,
                #problemList tbody td {
                    padding: 6px;
                }
            </style>
            <script>
                new DataTable('#problemList', {
                    "order": [],
                    dom: '<frtp><Brtp>',
                    ajax: "problemset.php?api=true&user_id=<?php echo $user_id ?>",
                    columns: [
                        {
                            render: function(data, type, row) {
                                let svg = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" style="vertical-align: middle;"><path fill="currentColor" d="M9 16.17l-3.5-3.5a1 1 0 0 1 1.41-1.41L9 13.34l6.09-6.09a1 1 0 0 1 1.41 1.41L9.71 16.17a1 1 0 0 1-1.41 0z"/></svg>';
                                let svgError = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" style="vertical-align: middle;"><path fill="currentColor" d="M18 6.34l-1.41-1.41L12 10.59 7.41 6 6 7.41 10.59 12 6 16.59 7.41 18 12 13.41l4.59 4.59L18 18.59 13.41 14 18 9.41z"/></svg>';
                                if (parseInt(row["ac"]) > 0) {
                                    return '<div class="result-green">' + svg + '</div>';
                                } else if (parseInt(row["wa"]) > 0) {
                                    return '<div class="result-red">' + svgError + '</div>';
                                }
                                return '';
                            }
                        },
                        {
                            data: 'problem_id',
                            render: function(data, type, row) {
                                return row.problem_id;
                            }
                        },
                        {
                            data: 'title',
                            className: 'wide-column',
                            render: function(data, type, row) {
                                return '<a href="problem.php?id=' + row.problem_id + '" class="text-blue-600 hover:text-blue-800">' + data + '</a>';
                            }
                        },
                        {
                            data: 'source'
                        },
                        {
                            data: 'accepted',
                            render: function(data, type, row) {
                                return '<a href="status.php?problem_id=' + row.problem_id + '&jresult=4" class="text-blue-600 hover:text-blue-800">' + data + '</a>';
                            }
                        },
                        {
                            data: 'submit',
                            render: function(data, type, row) {
                                return '<a href="status.php?problem_id=' + row.problem_id + '" class="text-blue-600 hover:text-blue-800">' + data + '</a>';
                            }
                        }
                    ],
                    language: {
                        url: 'https://cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json',
                    },
                    responsive: true,
                    "pageLength": 100,
                    "pagingType": "full_numbers",
                });
            </script>
        </div>
        </div>

    </main>

    <?php require_once "oj-footer.php" ?>

</body>

</html>