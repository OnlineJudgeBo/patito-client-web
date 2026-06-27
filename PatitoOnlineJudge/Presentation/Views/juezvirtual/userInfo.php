<!DOCTYPE html>
<html lang="es" class="h-full">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title ?></title>
    <link href='https://fonts.googleapis.com/css?family=Capriola' rel='stylesheet'>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="./assets/base.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.0.0/css/dataTables.tailwindcss.css">
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script src="https://cdn.datatables.net/2.0.0/js/dataTables.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/searchpanes/2.3.0/css/searchPanes.dataTables.css">
    <script src="https://cdn.datatables.net/searchpanes/2.3.0/js/dataTables.searchPanes.js"></script>
    <script src="https://cdn.datatables.net/searchpanes/2.3.0/js/searchPanes.dataTables.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/select/2.0.0/css/select.dataTables.css">
    <script src="https://cdn.datatables.net/select/2.0.0/js/dataTables.select.js"></script>
    <script src="https://cdn.datatables.net/select/2.0.0/js/select.dataTables.js"></script>
</head>

<body class="flex flex-col h-full">
    <?php
    if (isset($cid) && intval($cid) > 0) {
        require_once "oj-header-contest.php";
    } else {
        require_once "oj-header.php";
    }
    ?>
    <main class="container mx-auto p-4 grid grid-cols-0">
        <div class="relative w-full overflow-auto">
            <div class="min-h-screen p-8">
                <div class="max-w-4xl mx-auto">
                    <div class="bg-white p-6 rounded-lg shadow-lg mb-8">
                        <h2 class="text-xl font-bold mb-6">Editar datos de cuenta</h2>
                        <form method="POST">
                            <div class="mb-4">
                                <label class="block text-gray-700 text-sm font-bold mb-2" for="username">Nombre</label>
                                <input
                                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                    value="<?php echo $user['nick'] ?>"
                                    id="nick"
                                    name="nick"
                                    type="text"
                                    placeholder="Tu nombre">
                            </div>
                            <div class="mb-4">
                                <label class="block text-gray-700 text-sm font-bold mb-2" for="username">Apellido</label>
                                <input
                                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                    value="<?php echo $user['lastname'] ?>"
                                    id="lastname"
                                    name="lastname"
                                    type="text"
                                    placeholder="Tu nombre">
                            </div>
                            <div class="mb-4">
                                <label class="block text-gray-700 text-sm font-bold mb-2" for="email">Correo electrónico</label>
                                <input
                                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                    value="<?php echo $user['email'] ?>"
                                    id="email"
                                    name="email"
                                    type="email" 
                                    placeholder="Tu correo electrónico">
                            </div>
                            <div class="mb-4">
                                <label class="block text-gray-700 text-sm font-bold mb-2" for="password">Contraseña</label>
                                <input 
                                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 mb-3 leading-tight focus:outline-none focus:shadow-outline"
                                    id="password" 
                                    name="password" 
                                    type="password"
                                    placeholder="********">
                            </div>
                            <div class="flex items-center justify-between">
                                <button
                                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline"
                                    type="submit">Guardar cambios</button>
                            </div>
                        </form>
                    </div>

                    <div class="bg-white p-6 rounded-lg shadow-lg">
                        <h2 class="text-xl font-bold mb-6">Problemas resueltos</h2>
                        <div class="mb-4">
                            <input class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="Buscar problema">
                        </div>
                        <div class="overflow-x-auto">
                            <table class="min-w-full bg-white">
                                <thead class="bg-gray-800 text-white">
                                    <tr>
                                        <th class="w-1/3 text-left py-3 px-4 uppercase font-semibold text-sm">Mi primer envío</th>
                                        <th class="w-1/3 text-left py-3 px-4 uppercase font-semibold text-sm">Problema</th>
                                        <th class="w-1/3 text-left py-3 px-4 uppercase font-semibold text-sm">Solución Id</th>
                                        <th class="w-1/3 text-left py-3 px-4 uppercase font-semibold text-sm">Código</th>
                                        <th class="w-1/3 text-left py-3 px-4 uppercase font-semibold text-sm">Número de envíos</th>
                                    </tr>
                                </thead>
                                <tbody class="text-gray-700">
                                    <?php foreach ($problemList as $key => $value) { ?>
                                        <tr>
                                            <td class="w-1/3 text-left py-3 px-4"><?php echo $value["in_date"] ?></td>
                                            <td class="w-1/3 text-left py-3 px-4">
                                                <a href="./problem.php?id=<?php echo $value["problem_id"] ?>" class="text-blue-500 hover:text-blue-700" target="_blank">
                                                    <?php echo $value["title"] ?>
                                                </a>
                                            </td>
                                            <td class="w-1/3 text-left py-3 px-4">
                                                <a href="./showsource.php?id=<?php echo $value["solution_id"] ?>" class="text-blue-500 hover:text-blue-700" target="_blank">
                                                    <?php echo $value["solution_id"] ?>
                                                </a>
                                            </td>
                                            <td class="w-1/3 text-left py-3 px-4">
                                                <a href="./showsource.php?id=<?php echo $value["solution_id"] ?>" class="text-blue-500 hover:text-blue-700" target="_blank">
                                                    Ver
                                                </a>
                                            </td>
                                            <td class="w-1/3 text-left py-3 px-4">
                                                <a href="./status.php?user_id=<?php echo $value["user_id"]?>&problem_id=<?php echo $value["problem_id"]?>" class="text-blue-500 hover:text-blue-700" target="_blank">
                                                    <?php echo $judge_result[$value["result"]] ?>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="bg-white p-6 rounded-lg shadow-lg">
                        <h2 class="text-xl font-bold mb-6">Problemas que no se resolvieron</h2>
                        <div class="mb-4">
                            <input class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="Buscar problema">
                        </div>
                        <div class="overflow-x-auto">
                            <table class="min-w-full bg-white">
                                <thead class="bg-gray-800 text-white">
                                    <tr>
                                        <th class="w-1/3 text-left py-3 px-4 uppercase font-semibold text-sm">Envío</th>
                                        <th class="w-1/3 text-left py-3 px-4 uppercase font-semibold text-sm">Problema</th>
                                        <th class="w-1/3 text-left py-3 px-4 uppercase font-semibold text-sm">Solución Id</th>
                                        <th class="w-1/3 text-left py-3 px-4 uppercase font-semibold text-sm">Código</th>
                                        <th class="w-1/3 text-left py-3 px-4 uppercase font-semibold text-sm">Resultado</th>
                                    </tr>
                                </thead>
                                <tbody class="text-gray-700">
                                    <?php
                                        require __DIR__ . "/../../../../Legacy/Include/const.inc.php";
                                        foreach ($problemErrorList as $key => $value) { ?>
                                        <tr>
                                            <td class="w-1/3 text-left py-3 px-4"><?php echo $value["in_date"] ?></td>
                                            <td class="w-1/3 text-left py-3 px-4">
                                                <a href="./problem.php?id=<?php echo $value["problem_id"] ?>" class="text-blue-500 hover:text-blue-700" target="_blank">
                                                    <?php echo $value["title"] ?>
                                                </a>
                                            </td>
                                            <td class="w-1/3 text-left py-3 px-4">
                                                <a href="./showsource.php?id=<?php echo $value["solution_id"] ?>" class="text-blue-500 hover:text-blue-700" target="_blank">
                                                    <?php echo $value["solution_id"] ?>
                                                </a>
                                            </td>
                                            <td class="w-1/3 text-left py-3 px-4">
                                                <a href="./showsource.php?id=<?php echo $value["solution_id"] ?>" class="text-blue-500 hover:text-blue-700" target="_blank">
                                                    Ver
                                                </a>
                                            </td>
                                            <td class="w-1/3 text-left py-3 px-4">
                                                <a href="./status.php?user_id=<?php echo $value["user_id"]?>&problem_id=<?php echo $value["problem_id"]?>" class="text-blue-500 hover:text-blue-700" target="_blank">
                                                    <?php echo $judge_result[$value["result"]] ?>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </main>
    <?php require_once "oj-footer.php" ?>
</body>

</html>