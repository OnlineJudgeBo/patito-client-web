<?php $e = fn($text) => htmlspecialchars((string)$text, ENT_QUOTES, 'UTF-8'); ?>
<!DOCTYPE html>
<html lang="es" class="h-full">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $e($title) ?></title>
    <meta name="robots" content="noindex">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="./assets/base.css">
    <?php echo file_get_contents(__DIR__ . "/partials/utils-header.php"); ?>
</head>

<body class="flex flex-col h-full">
    <?php require_once "oj-header.php" ?>
    <main class="container mx-auto p-4 grid grid-cols-0">
        <div class="isolate bg-white px-6 lg:px-8">
            <div class="mx-auto max-w-2xl text-center">
                <h2 class="text-3xl font-bold tracking-tight text-gray-900 sm:text-4xl py-0">Crea tu cuenta</h2>
                <p class="mt-2 text-gray-600">Entraste con Google como <b><?php echo $e($profile["email"]) ?></b>. Solo falta elegir tu nombre de usuario.</p>
            </div>

            <div class="max-w-lg mx-auto mt-10 bg-white p-8 border border-gray-200 rounded-lg shadow-lg">
                <form method="POST" action="google-register.php">
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="username">Nombre de usuario (nickname)</label>
                        <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500" id="username" name="username" type="text" value="<?php echo $e($username) ?>" pattern="[A-Za-z0-9]{3,20}" maxlength="20" required>
                        <p class="mt-1 text-xs text-gray-500">De 3 a 20 letras o números, sin espacios. Es el que se verá en el ranking.</p>
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="name">Nombre</label>
                        <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500" id="name" name="name" type="text" value="<?php echo $e($profile["name"]) ?>" required>
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="lastname">Apellidos</label>
                        <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500" id="lastname" name="lastname" type="text" value="<?php echo $e($profile["lastname"]) ?>">
                    </div>
                    <?php if (!empty($error)) { ?>
                        <p class="mb-4 text-red-500 italic"><?php echo $e($error) ?></p>
                    <?php } ?>
                    <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline transition duration-150 ease-in-out" type="submit">
                        Crear cuenta
                    </button>
                </form>
            </div>
        </div>
    </main>
    <?php require_once "oj-footer.php" ?>
</body>

</html>
