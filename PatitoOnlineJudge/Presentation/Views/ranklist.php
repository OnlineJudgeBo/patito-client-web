<!DOCTYPE html>
<html lang="es" class="h-full">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title ?></title>
    <link href='https://fonts.googleapis.com/css?family=Capriola' rel='stylesheet'>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="./assets/base.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <?php echo file_get_contents(__DIR__."/partials/utils-header.php"); ?>
    <style>
    .medal-container {
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100%;
    }

    .medal {
        width: 30px;
        height: 30px;
    }
    </style>
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
                data: null,
                searchable: false,
                orderable: false,
                className: 'dt-body-center',
                render: function(data, type, row, meta) {
                    var rank = meta.row + meta.settings._iDisplayStart + 1;
                    if (rank === 1) {
                        return `<div class="medal-container"><svg class="medal" width="50" height="50" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <circle cx="12" cy="12" r="10" stroke="gold" stroke-width="2" fill="gold"/>
                        <text x="12" y="16" text-anchor="middle" fill="white" font-size="12" font-family="Arial" font-weight="bold">1</text>
                        </svg></div>`;
                    } else if (rank === 2) {
                        return `<div class="medal-container"><svg class="medal" width="50" height="50" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <circle cx="12" cy="12" r="10" stroke="silver" stroke-width="2" fill="silver"/>
                        <text x="12" y="16" text-anchor="middle" fill="black" font-size="12" font-family="Arial" font-weight="bold">2</text>
                        </svg></div>`;
                    } else if (rank === 3) {
                        return `<div class="medal-container"><svg class="medal" width="50" height="50" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <circle cx="12" cy="12" r="10" stroke="#cd7f32" stroke-width="2" fill="#cd7f32"/>
                        <text x="12" y="16" text-anchor="middle" fill="white" font-size="12" font-family="Arial" font-weight="bold">3</text>
                        </svg></div>`;
                    } else {
                        return '<div class="medal-container">' + rank + '</div>';
                    }
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