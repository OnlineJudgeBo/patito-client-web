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
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body class="flex flex-col h-full">
    <?php require_once "oj-header.php" ?>
    <main class="container mx-auto p-4 grid grid-cols-0">

        <div class="isolate bg-white px-6 sm:py-20 lg:px-8">
            <div class="mx-auto max-w-2xl text-center">
                <h2 class="text-3xl font-bold tracking-tight text-gray-900 sm:text-4xl">Recuperar Contraseña</h2>
                <p class="mt-4 text-lg leading-6 text-gray-500">Ingresa tu correo electrónico y te enviaremos instrucciones para restablecer tu contraseña.</p>
            </div>
            <form action="lostpassword.php" method="POST" class="mx-auto mt-16 max-w-xl sm:mt-20" id="lostpassword">
                <div class="sm:col-span-2">
                    <label for="email" class="block text-sm font-semibold leading-6 text-gray-900">Correo Electrónico</label>
                    <div class="mt-2.5">
                        <input type="email" name="email" id="email" autocomplete="email" required class="block w-full rounded-md border-0 px-3.5 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                    </div>
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
    document.getElementById('lostpassword').addEventListener('submit', function(event) {
        event.preventDefault();
        var form = this;
        Swal.fire({
            icon: 'success',
            title: 'Registro Exitoso',
            text: 'Si el correo electrónico proporcionado es correcto, se ha enviado un código de confirmación a su cuenta de correo.',
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });

    });
</script>

</html>