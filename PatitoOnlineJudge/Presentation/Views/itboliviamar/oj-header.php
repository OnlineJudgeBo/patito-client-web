<nav class="bg-white p-4 shadow-md border-b border-gray-200">
    <div class="container mx-auto flex items-center justify-between">
        <div class="text-gray-800 text-lg font-bold">
            Juez Virtual
        </div>
        <div class="flex items-center space-x-4 hidden md:flex">
            <a href="/" class="text-gray-800 hover:text-blue-200">Inicio</a>
            <a href="contest.php" class="text-gray-800 hover:text-blue-200">Concursos</a>
            <a href="problemset.php" class="text-gray-800 hover:text-blue-200">Problemas</a>
            <a href="ranklist.php" class="text-gray-800 hover:text-blue-200">Ranking</a>
            <a href="status.php" class="text-gray-800 hover:text-blue-200">Envios</a>
            <a href="faqs.php" class="text-gray-800 hover:text-blue-200">Ayuda</a>
            <?php include __DIR__ . '/partials/user-session-menu.php'; ?>
        </div>
        <div class="md:hidden">
            <button id="menu-toggle" class="text-gray-800 focus:outline-none">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7" />
                </svg>
            </button>
        </div>
    </div>
    <div id="mobile-menu" class="md:hidden hidden">
        <a href="/" class="block px-4 py-2 text-gray-800 hover:bg-gray-100">Inicio</a>
        <a href="contest.php" class="block px-4 py-2 text-gray-800 hover:bg-gray-100">Concursos</a>
        <a href="problemset.php" class="block px-4 py-2 text-gray-800 hover:bg-gray-100">Problemas</a>
        <a href="ranklist.php" class="block px-4 py-2 text-gray-800 hover:bg-gray-100">Ranking</a>
        <a href="status.php" class="block px-4 py-2 text-gray-800 hover:bg-gray-100">Envios</a>
        <a href="faqs.php" class="block px-4 py-2 text-gray-800 hover:bg-gray-100">Ayuda</a>
        <?php include __DIR__ . '/partials/user-session-menu.php'; ?>
    </div>
</nav>

<script>
    document.getElementById('menu-toggle').addEventListener('click', function() {
        var menu = document.getElementById('mobile-menu');
        menu.classList.toggle('hidden');
    });
</script>