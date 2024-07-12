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
</head>

<body class="flex flex-col h-full bg-gray-100">
    <?php require_once "oj-header.php" ?>
    <main class="container mx-auto p-4 flex-grow flex flex-col justify-center items-center">
        <section id="respuestas-del-juez" class="max-w-2xl mx-auto p-6 bg-white rounded-lg shadow-md">
            <h1 class="text-3xl font-bold text-center mb-5">Error</h1>
            <p class="text-lg text-gray-700 text-center mb-10">
                <?php echo $error ?>
            </p>
        </section>
    </main>
    <?php require_once "oj-footer.php" ?>
</body>

</html>