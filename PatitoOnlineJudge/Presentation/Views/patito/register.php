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
    <?php echo file_get_contents(__DIR__ . "/partials/utils-header.php"); ?>
</head>

<body class="flex flex-col h-full">
    <?php require_once "oj-header.php" ?>
    <main class="container mx-auto p-4 grid grid-cols-0">

        <div class="isolate bg-white px-6 lg:px-8">
            <div class="mx-auto max-w-2xl text-center">
                <h2 class="text-3xl font-bold tracking-tight text-gray-900 sm:text-4xl py-0">Registro de Usuario</h2>
            </div>

            <div class="max-w-lg mx-auto mt-10 bg-white p-8 border border-gray-200 rounded-lg shadow-lg">
                <form id="registrationForm" method="POST" action="registerpage.php">
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="name">
                            Nombre
                        </label>
                        <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500" id="name" name="name" type="text" placeholder="Tu nombre" required>
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="lastname">
                            Apellidos
                        </label>
                        <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500" id="lastname" name="lastname" type="text" placeholder="Apellidos" required>
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="nickname">
                            Nombre de usuario (nickname)
                        </label>
                        <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500" id="nickname" name="nickname" type="text" placeholder="Tu nombre de usuario (nickname)" required>
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="email">
                            Correo Electrónico
                        </label>
                        <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500" name="email" id="email" type="email" placeholder="tucorreo@ejemplo.com" required>
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="email">
                            Confirma tu Correo Electrónico
                        </label>
                        <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500" name="email2" id="email2" type="email" placeholder="tucorreo@ejemplo.com" required>
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="password" autocomplete="new-password">
                            Contraseña
                        </label>
                        <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500" id="password" name="password" type="password" required>
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="password">
                            Confirma tu Contraseña
                        </label>
                        <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500" id="password2" name="password2" type="password" required>
                    </div>

                    <div class="flex items-center justify-between">
                        <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline transition duration-150 ease-in-out" type="submit">
                            Registrarse
                        </button>
                    </div>
                </form>
                <div class="mt-10 flex flex-col items-center">
                    <a href="lostpassword.php" class="mt-4 text-sm text-indigo-600 hover:underline" rel="noopener noreferrer">Recuperar clave</a>
                </div>
            </div>

        </div>
    </main>
    <?php require_once "oj-footer.php" ?>
    <script>
        document.getElementById('registrationForm').addEventListener('submit', function(event) {
            event.preventDefault();

            Swal.fire({
                title: 'Cargando...',
                text: 'Por favor, espere mientras se crea su usuario.',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            let email = document.getElementById('email').value;
            let confirmEmail = document.getElementById('email2').value;
            let password = document.getElementById('password').value;
            let confirmPassword = document.getElementById('password2').value;

            if (email === '' || confirmEmail === '' || password === '' || confirmPassword === '') {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Por favor, completa todos los campos del formulario.',
                });
                return false;
            }

            if (email !== confirmEmail) {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Los correos electrónicos no coinciden. Por favor, verifica e intenta nuevamente.',
                });
                return false;
            }

            if (password !== confirmPassword) {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Las contraseñas no coinciden. Por favor, verifica e intenta nuevamente.',
                });
                return false;
            }

            let formData = new FormData(this);
            fetch('registerpage.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => {
                    if (response.ok) {
                        Swal.fire({
                            icon: 'success',
                            timer: 3000,
                            timerProgressBar: true,
                            didOpen: () => {
                                Swal.showLoading();
                            },
                            onClose: () => {
                                window.location.href = "login.php";
                            },
                            title: 'Registro Exitoso',
                            text: 'Usuario registrado correctamente. Por favor, inicie sesión.',
                            timer: 3000,
                            timerProgressBar: true,
                            didClose: () => {
                                window.location.href = "login.php";
                            }
                        });
                    } else {
                        return response.text().then(text => {
                            throw new Error(text);
                        })
                    }
                })
                .catch((error) => {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: error,
                    });
                });
        });
    </script>
</body>

</html>
