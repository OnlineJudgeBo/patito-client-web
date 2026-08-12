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
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <?php echo file_get_contents(__DIR__."/partials/utils-header.php"); ?>
</head>

<body class="flex flex-col h-full">
    <?php require_once "oj-header.php" ?>
    <main class="container mx-auto p-4 grid grid-cols-0">

        <div class="isolate bg-white px-6 sm:py-20 lg:px-8">
            <div class="mx-auto max-w-2xl text-center">
                <h2 class="text-3xl font-bold tracking-tight text-gray-900 sm:text-4xl">Cambio de Contraseña</h2>
            </div>
            <form action="updatepassword.php" method="POST" class="mx-auto mt-16 max-w-xl sm:mt-20" id="updatePasswordForm">
                <input name="token" type="hidden" value="<?php echo $token ?>">
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="password" autocomplete="new-password">
                        Contraseña
                    </label>
                    <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500" id="password" name="password" type="password">
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="password">
                        Confirma tu Contraseña
                    </label>
                    <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500" id="password2" name="password2" type="password">
                </div>
                <div class="mt-10">
                    <button type="submit" class="block w-full rounded-md bg-indigo-600 px-3.5 py-2.5 text-center text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Enviar</button>
                </div>
            </form>
        </div>

    </main>

    <?php require_once "oj-footer.php" ?>

</body>
<script>
    document.getElementById('updatePasswordForm').addEventListener('submit', function(event) {
        
        let password = document.getElementById('password').value;
        let confirmPassword = document.getElementById('password2').value;
        
        if (password !== confirmPassword) {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Las contraseñas no coinciden. Por favor, verifica e intenta nuevamente.',
            });
            event.preventDefault();
        }
    });
</script>

</html>