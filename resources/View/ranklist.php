<!DOCTYPE html>
<html lang="en" class="h-full">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Juez Virtual Patito</title>
    <link href='https://fonts.googleapis.com/css?family=Capriola' rel='stylesheet'>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="./assets/base.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
</head>

<body class="flex flex-col h-full">
    <?php require_once "oj-header.php" ?>
    <main class="container mx-auto p-4 grid grid-cols-0">
        <div class="col-span-2">

            <table id="ranklist" class="display" style="width:100%">
                <thead>
                    <tr>
                        <th>N</th>
                        <th>User</th>
                        <th>Nombre</th>
                        <th>AC</th>
                        <th>Enviados</th>
                    </tr>
                </thead>
            </table>
            <style>
                #ranklist thead th,
                #ranklist tbody td {
                    padding: 3px;
                }
            </style>
            <script>
new DataTable('#ranklist', {
    dom: '<frtp><Brtp>',
    ajax: 'ranklist.php?api=true',
    columns: [
        {
            data: null, // No hay datos de servidor para esta columna
            searchable: false,
            orderable: false,
            className: 'dt-body-center',
            render: function (data, type, row, meta) {
                return meta.row + meta.settings._iDisplayStart + 1; // Genera el índice
            }
        },
        {
            data: 'user_id'
        },
        {
            data: 'nick',
        },
        {
            data: 'solved'
        },
        {
            data: 'submit'
        }
    ],
    language: {
        url: 'https://cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json',
    },
    responsive: true,
    pageLength: 100,
    pagingType: "full_numbers"
});


            </script>
        </div>
        </div>

    </main>

    <?php require_once "oj-footer.php" ?>

</body>

</html>