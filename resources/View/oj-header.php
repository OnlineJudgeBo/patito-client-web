<nav class="w-full bg-slate-900 border-b-4 border-green-700 bg-gradient-to-r from-bg-slate-600 to-bg-slate-700">
  <div class="mx-auto max-w-7xl px-2 sm:px-6 lg:px-8">
    <div class="relative flex h-16 items-center justify-between ">
      <div class="absolute inset-y-0 left-0 flex items-center sm:hidden">
        <!-- Mobile menu button-->
        <button type="button" class="mobile-menu-button relative inline-flex items-center justify-center rounded-md p-2 text-gray-400 hover:text-yellow-400 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white" aria-controls="mobile-menu" aria-expanded="false">
          <span class="absolute -inset-0.5"></span>
          <span class="sr-only">Open main menu</span>

          <svg class="open-icon block h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
          </svg>
          <svg class="close-icon hidden h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
          </svg>
        </button>
      </div>

      <div class="flex flex-1 items-center justify-center sm:items-stretch sm:justify-start text-custom-blue ">
        <div class="flex flex-shrink-0 items-center">
          <img class="h-16 w-auto" src="./assets/logo.svg" alt="Juez Virtual Patito">
        </div>

        <div class="hidden sm:ml-6 sm:block">
          <div class="flex space-x-4 pt-2">
            <a href="/" class="text-xl text-white hover:text-yellow-400 rounded-md px-3 py-2 text-sm font-medium">Inicio</a>
            <a href="contest.php" class="text-xl text-white hover:text-yellow-400 rounded-md px-3 py-2 text-sm font-medium">Concursos</a>
            <a href="problemset.php" class="text-xl text-white hover:text-yellow-400 rounded-md px-3 py-2 text-sm font-medium">Problemas</a>
            <a href="ranklist.php" class="text-xl text-white hover:text-yellow-400 rounded-md px-3 py-2 text-sm font-medium">Ranking</a>
            <a href="status.php" class="text-xl text-white hover:text-yellow-400 rounded-md px-3 py-2 text-sm font-medium">Envios</a>
            <a href="faqs.php" class="text-xl text-white hover:text-yellow-400 rounded-md px-3 py-2 text-sm font-medium">Ayuda</a>
          </div>
        </div>
      </div>

      <div class="absolute inset-y-0 right-0 flex items-center pr-2 sm:static sm:inset-auto sm:ml-6 sm:pr-0 hidden sm:ml-6 sm:block">
        <div class="flex space-x-4">
          <a href="login.php" class="text-xl text-white hover:text-yellow-400 rounded-md px-3 py-2 text-sm font-medium">Inicia
            sesión</a>
          <a href="registerpage.php" class="text-xl text-white hover:text-yellow-400 rounded-md px-3 py-2 text-sm font-medium">Registrarse</a>
        </div>
      </div>
    </div>
  </div>

  <div class="sm:hidden hidden" id="mobile-menu">
    <div class="space-y-1 px-2 pb-3 pt-2">
      <a href="/" class="bg-gray-900 text-white block rounded-md px-3 py-2 text-base font-medium">Inicio</a>
      <a href="contest.php" class="text-white hover:text-yellow-400 block rounded-md px-3 py-2 text-base font-medium">Concursos</a>
      <a href="problemset.php" class="text-white hover:text-yellow-400 block rounded-md px-3 py-2 text-base font-medium">Problemas</a>
      <a href="ranklist.php" class="text-white hover:text-yellow-400 block rounded-md px-3 py-2 text-base font-medium">Ranking</a>
      <a href="status.php" class="text-white hover:text-yellow-400 block rounded-md px-3 py-2 text-base font-medium">Envios</a>
      <a href="faqs.php" class="text-white hover:text-yellow-400 block rounded-md px-3 py-2 text-base font-medium">Ayuda</a>
    </div>
  </div>
</nav>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    let menuButton = document.querySelector('.mobile-menu-button');
    let mobileMenu = document.getElementById('mobile-menu');

    menuButton.addEventListener('click', function() {
      mobileMenu.classList.toggle('hidden');
      menuButton.querySelector('.open-icon').classList.toggle('hidden');
      menuButton.querySelector('.close-icon').classList.toggle('hidden');
    });
  });
</script>