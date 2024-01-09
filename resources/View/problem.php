<!DOCTYPE html>
<html lang="en" class="h-full">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Juez Virtual Patito</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="flex flex-col h-full">

    <nav class="w-full bg-slate-900 border-b-4 border-green-700 bg-gradient-to-r from-bg-slate-600 to-bg-slate-700">
        <div class="mx-auto max-w-7xl px-2 sm:px-6 lg:px-8">
            <div class="relative flex h-16 items-center justify-between ">
                <div class="absolute inset-y-0 left-0 flex items-center sm:hidden">
                    <!-- Mobile menu button-->
                    <button type="button"
                        class="relative inline-flex items-center justify-center rounded-md p-2 text-gray-400 hover:text-yellow-400 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white"
                        aria-controls="mobile-menu" aria-expanded="false">
                        <span class="absolute -inset-0.5"></span>
                        <span class="sr-only">Open main menu</span>

                        <svg class="block h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                        </svg>

                        <svg class="hidden h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <div
                    class="flex flex-1 items-center justify-center sm:items-stretch sm:justify-start text-custom-blue ">
                    <div class="flex flex-shrink-0 items-center">
                        <img class="h-16 w-auto" src="/logo.svg" alt="Juez Virtual Patito">
                    </div>

                    <div class="hidden sm:ml-6 sm:block">
                        <div class="flex space-x-4 pt-2">
                            <a href="#"
                                class="text-xl text-white hover:text-yellow-400 rounded-md px-3 py-2 text-sm font-medium">Inicio</a>
                            <a href="#"
                                class="text-xl text-white hover:text-yellow-400 rounded-md px-3 py-2 text-sm font-medium">Concursos</a>
                            <a href="#"
                                class="text-xl text-white hover:text-yellow-400 rounded-md px-3 py-2 text-sm font-medium">Problemas</a>
                            <a href="#"
                                class="text-xl text-white hover:text-yellow-400 rounded-md px-3 py-2 text-sm font-medium">Ranking</a>
                            <a href="#"
                                class="text-xl text-white hover:text-yellow-400 rounded-md px-3 py-2 text-sm font-medium">Ayuda</a>
                        </div>
                    </div>
                </div>

                <div class="absolute inset-y-0 right-0 flex items-center pr-2 sm:static sm:inset-auto sm:ml-6 sm:pr-0">
                    <div class="flex space-x-4">
                        <a href="#"
                            class="text-xl text-white hover:text-yellow-400 rounded-md px-3 py-2 text-sm font-medium">Inicia
                            sesión</a>
                        <a href="#"
                            class="text-xl text-white hover:text-yellow-400 rounded-md px-3 py-2 text-sm font-medium">Registrarse</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="sm:hidden" id="mobile-menu">
            <div class="space-y-1 px-2 pb-3 pt-2">
                <a href="#" class="bg-gray-900 text-white block rounded-md px-3 py-2 text-base font-medium">Inicio</a>
                <a href="#"
                    class="text-white hover:text-yellow-400 block rounded-md px-3 py-2 text-base font-medium">Concursos</a>
                <a href="#"
                    class="text-white hover:text-yellow-400 block rounded-md px-3 py-2 text-base font-medium">Problemas</a>
                <a href="#"
                    class="text-white hover:text-yellow-400 block rounded-md px-3 py-2 text-base font-medium">Ranking</a>
                <a href="#"
                    class="text-white hover:text-yellow-400 block rounded-md px-3 py-2 text-base font-medium">Ayuda</a>
            </div>
        </div>
    </nav>

    <main class="container mx-auto p-4 grid grid-cols-0">
        <div class="col-span-2">

            <div class="rounded-lg border bg-card text-card-foreground shadow-sm">
                <div class="flex flex-col items-center p-6">
                    <div class="text-center">
                        <h2 class="text-xl font-bold tracking-tight text-black mb-1">1000: A + B</h2>

                        <div class="grid grid-cols-2 mb-2">
                            <div class="flex justify-center items-center">
                                <dt class="text-green-500 mr-1">Time Limit:</dt>
                                <dd>1 Sec</dd>
                            </div>
                            <div class="flex">
                                <dt class="text-green-500 mr-1">Memory Limit:</dt>
                                <dd>128 MB</dd>
                            </div>
                            <div class="flex justify-center items-center -mt-2">
                                <dt class="text-green-500 mr-1">Enviados:</dt>
                                <dd>15534</dd>
                            </div>
                            <div class="flex">
                                <dt class="text-green-500 mr-1 -mt-2">Resuelto:</dt>
                                <dd>6311</dd>
                            </div>
                        </div>

                        <div class="flex justify-center gap-4">
                            <a href="#"
                                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                                Enviar
                            </a>
                            <a href="#"
                                class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                                Estado
                            </a>
                            <a href="#"
                                class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                                Prueba nuestro editor online
                            </a>
                        </div>
                    </div>
                </div>

                <div class="ml-2 mx-auto rounded">
                    <div class="mb-4">
                        <h2 class="text-xl font-bold tracking-tight text-black mb-1">Descripción</h2>
                        <p class="text-gray-600 bg-gray-100 text-gray-800 p-4">El problema trata de sumar dos números.
                        </p>
                    </div>

                    <div class="mb-4">
                        <h3 class="text-xl font-bold tracking-tight text-black mb-1">Entrada</h3>
                        <p class="text-gray-600 bg-gray-100 text-gray-800 p-4">Se le darán dos números A y B.</p>
                    </div>

                    <div class="mb-4">
                        <h3 class="text-xl font-bold tracking-tight text-black mb-1">Salida</h3>
                        <p class="text-gray-600 bg-gray-100 text-gray-800 p-4">Imprimir A + B.</p>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div class="">
                            <h3 class="text-xl font-bold tracking-tight text-black mb-1">Ejemplo Entrada</h3>
                            <div class="bg-gray-100 p-2 rounded">
                                <p class="text-gray-600 bg-gray-100 text-gray-800 p-4">1 2</p>
                            </div>
                        </div>

                        <div class="">
                            <h3 class="text-xl font-bold tracking-tight text-black mb-1">Ejemplo Salida</h3>
                            <div class="bg-gray-100 p-2 rounded">
                                <p class="text-gray-600 bg-gray-100 text-gray-800 p-4">3</p>
                            </div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <h3 class="text-xl font-bold tracking-tight text-black mb-1">Ayuda</h3>
                        <p class="text-gray-600 bg-gray-100 text-gray-800 p-4">Codigo</p>
                    </div>
                </div>

            </div>
        </div>

    </main>

    <footer class="mt-auto bg-slate-800	text-white p-2">
        <div class="text-center mt-1">
            <p> Algun problema, Envie un correo:
                <a href="mailto:samuel.loza26@gmail.com"
                    class="text-blue-300 hover:text-blue-500">samuel.loza26@gmail.com</a>
            </p>
            <p>O a través de Telegram:
                <a href="https://t.me/zsams" class="text-green-300 hover:text-green-500">@zsams</a>
            </p>
        </div>
    </footer>

</body>

</html>