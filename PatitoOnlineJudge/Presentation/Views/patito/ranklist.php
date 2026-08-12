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
    <style>
        .ranklist-card {
            overflow: hidden;
            border: 1px solid #dbe2ea;
            border-radius: 0.75rem;
            background: #fff;
        }

        .ranklist-toolbar {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: space-between;
            gap: 0.75rem;
            padding: 0.8rem 1rem;
            border-bottom: 1px solid #e2e8f0;
            background: #fff;
        }

        .ranklist-periods {
            display: flex;
            flex-wrap: wrap;
            gap: 0.25rem;
        }

        .ranklist-period {
            padding: 0.4rem 0.7rem;
            border-radius: 0.35rem;
            color: #64748b;
            font-size: 0.82rem;
            font-weight: 600;
            text-decoration: none;
        }

        .ranklist-period:hover {
            color: #0f172a;
            background: #f1f5f9;
        }

        .ranklist-period[aria-current="page"] {
            color: #0f172a;
            background: #e2e8f0;
        }

        .ranklist-note {
            color: #64748b;
            font-size: 0.8rem;
        }

        .ranklist-table-wrap {
            overflow-x: auto;
        }

        #ranklist thead {
            color: #334155;
            background: #f8fafc;
        }

        #ranklist thead th {
            padding: 0.8rem 1rem;
            border-bottom: 1px solid #cbd5e1;
            font-size: 0.78rem;
            font-weight: 600;
            letter-spacing: 0.01em;
            text-transform: none;
        }

        #ranklist tbody td {
            padding: 0.85rem 1rem;
            border-bottom: 1px solid #e2e8f0;
            color: #475569;
            vertical-align: middle;
        }

        #ranklist tbody tr:nth-child(even) {
            background: #f8fafc;
        }

        #ranklist tbody tr:hover {
            background: #f1f5f9;
        }

        #ranklist tbody tr.rank-top {
            box-shadow: inset 3px 0 #cbd5e1;
        }

        #ranklist tbody tr.rank-first {
            box-shadow: inset 3px 0 #d97706;
        }

        #ranklist .rank-user {
            color: #1e40af;
            font-weight: 600;
        }

        #ranklist .rank-name {
            color: #334155;
        }

        #ranklist .rank-number {
            color: #334155;
            font-weight: 600;
            font-variant-numeric: tabular-nums;
        }

        #ranklist .rank-rate {
            color: #64748b;
            font-size: 0.82rem;
            font-variant-numeric: tabular-nums;
        }

        #ranklist_wrapper {
            padding: 1rem;
        }

        #ranklist_wrapper .dataTables_filter,
        #ranklist_wrapper .dataTables_length {
            margin-bottom: 1rem;
            color: #64748b;
            font-size: 0.875rem;
        }

        #ranklist_wrapper .dataTables_filter input,
        #ranklist_wrapper .dataTables_length select {
            margin-left: 0.4rem;
            border: 1px solid #cbd5e1;
            border-radius: 0.4rem;
            background: #fff;
        }

        #ranklist_wrapper .dataTables_filter input {
            min-width: 15rem;
            padding: 0.45rem 0.65rem;
        }

        #ranklist_wrapper .dataTables_info,
        #ranklist_wrapper .dataTables_paginate {
            padding-top: 1rem;
            color: #64748b;
            font-size: 0.875rem;
        }

        #ranklist_wrapper .dataTables_paginate .paginate_button {
            border: 0 !important;
            border-radius: 0.35rem;
            background: transparent !important;
        }

        #ranklist_wrapper .dataTables_paginate .paginate_button.current {
            color: #0f172a !important;
            background: #e2e8f0 !important;
        }

        @media (max-width: 640px) {
            #ranklist_wrapper {
                padding: 0.75rem;
            }

            #ranklist_wrapper .dataTables_filter {
                float: none;
                text-align: left;
            }

            #ranklist_wrapper .dataTables_filter input {
                width: 100%;
                min-width: 0;
                margin: 0.4rem 0 0;
            }
        }
    </style>
</head>

<body class="flex flex-col h-full">
    <?php require_once "oj-header.php" ?>
    <main class="oj-page">
        <header class="mb-5">
            <h1 class="text-2xl font-semibold text-slate-900">Clasificación general</h1>
            <p class="mt-1 text-sm text-slate-500">Más problemas resueltos significa una mejor posición; en empate, se favorecen menos envíos.</p>
        </header>

        <div class="ranklist-card">
            <?php
            $currentScope = $_GET["scope"] ?? "all";
            $periods = [
                "all" => "Histórico",
                "m" => "Este mes",
                "w" => "Últimos 7 días",
                "d" => "Hoy"
            ];
            ?>
            <div class="ranklist-toolbar">
                <nav class="ranklist-periods" aria-label="Período de clasificación">
                    <?php foreach ($periods as $periodScope => $periodLabel) { ?>
                        <a
                            class="ranklist-period"
                            href="ranklist.php?scope=<?php echo $periodScope ?>"
                            <?php echo $currentScope === $periodScope ? 'aria-current="page"' : '' ?>
                        >
                            <?php echo $periodLabel ?>
                        </a>
                    <?php } ?>
                </nav>
                <span class="ranklist-note">Solo se cuentan soluciones aceptadas.</span>
            </div>
            <div class="ranklist-table-wrap">
                <table id="ranklist" class="display" style="width: 100%">
                    <thead>
                        <tr>
                            <th>Posición</th>
                            <th>Usuario</th>
                            <th>Nombre</th>
                            <th>Resueltos</th>
                            <th>Enviados</th>
                            <th>Efectividad</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </main>

    <?php require_once "oj-footer.php" ?>

    <script>
        const escapeHtml = value => $('<div>').text(value ?? '').html();

        new DataTable('#ranklist', {
            dom: 'frtip',
            ajax: 'ranklist.php?api=true&scope=<?php echo urlencode($currentScope) ?>',
            columns: [
                {
                    data: null,
                    searchable: false,
                    orderable: false,
                    className: 'dt-body-center',
                    render: function(data, type, row, meta) {
                        const position = meta.row + meta.settings._iDisplayStart + 1;
                        return `<span class="oj-rank-position" data-position="${position}">${position}</span>`;
                    }
                },
                {
                    data: 'user_id',
                    render: function(data, type) {
                        if (type !== 'display') {
                            return data;
                        }

                        const userId = escapeHtml(data);
                        return `<a class="rank-user" href="status.php?user_id=${encodeURIComponent(data)}">${userId}</a>`;
                    }
                },
                {
                    data: 'nick',
                    className: 'rank-name',
                    defaultContent: '',
                    render: function(data, type) {
                        return type === 'display' ? escapeHtml(data) : data;
                    }
                },
                {
                    data: 'solved',
                    className: 'dt-body-center rank-number'
                },
                {
                    data: 'submit',
                    className: 'dt-body-center rank-number'
                },
                {
                    data: null,
                    className: 'dt-body-center rank-rate',
                    render: function(data, type, row) {
                        const solved = Number(row.solved) || 0;
                        const submitted = Number(row.submit) || 0;
                        const rate = submitted > 0 ? (solved / submitted) * 100 : 0;

                        if (type === 'sort' || type === 'type') {
                            return rate;
                        }

                        return `${rate.toFixed(1)}%`;
                    }
                }
            ],
            language: {
                url: 'https://cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json',
                emptyTable: 'Todavía no hay participantes en la clasificación.'
            },
            order: [[3, 'desc'], [4, 'asc']],
            pageLength: 100,
            pagingType: 'simple_numbers',
            createdRow: function(row, data, dataIndex) {
                const position = dataIndex + 1;

                if (position <= 3) {
                    row.classList.add('rank-top');
                }

                if (position === 1) {
                    row.classList.add('rank-first');
                }
            }
        });
    </script>
</body>

</html>
