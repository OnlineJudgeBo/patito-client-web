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

<body class="flex flex-col h-full">
    <?php require_once "oj-header.php" ?>
    <main class="container mx-auto p-4 grid grid-cols-0">

        <div class="isolate bg-white px-6 py-24 sm:py-32 lg:px-8">
            <div class="mx-auto max-w-2xl text-center">
                <h2 class="text-3xl font-bold tracking-tight text-gray-900 sm:text-4xl">Inicio de Sesión</h2>
            </div>
            <form action="login.php" method="POST" class="mx-auto mt-16 max-w-xl sm:mt-20">
                <div class="grid grid-cols-1 gap-x-8 gap-y-6 sm:grid-cols-2">

                    <div class="sm:col-span-2">
                        <label for="company" class="block text-sm font-semibold leading-6 text-gray-900">Usuario</label>
                        <div class="mt-2.5">
                            <input type="text" name="username" id="username" autocomplete="username" class="block w-full rounded-md border-0 px-3.5 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                        </div>
                    </div>
                    <div class="sm:col-span-2">
                        <label for="email" class="block text-sm font-semibold leading-6 text-gray-900">Contraseña</label>
                        <div class="mt-2.5">
                            <input type="password" name="password" id="password" autocomplete="current-password" class="block w-full rounded-md border-0 px-3.5 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                        </div>
                    </div>

                </div>
                <?php
                if (!empty($error)) {
                    echo '<p class="text-red-500 text-xl italic">Error en el nombre de usuario o la contraseña</p>';
                }
                ?>
                <div class="mt-10">
                    <button type="submit" class="block w-full rounded-md bg-indigo-600 px-3.5 py-2.5 text-center text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Iniciar</button>
                </div>
            </form>
            <div class="mt-10 flex flex-col items-center">
                <a href="lostpassword.php" class="mt-4 text-sm text-indigo-600 hover:underline" rel="noopener noreferrer">Recuperar clave</a>
            </div>
        </div>

    </main>

    <?php require_once "oj-footer.php" ?>

</body>

</html>