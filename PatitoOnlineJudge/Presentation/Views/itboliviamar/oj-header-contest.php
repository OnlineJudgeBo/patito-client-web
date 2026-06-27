<nav class="bg-white p-4 shadow-md border-b border-gray-200">
  <div class="container mx-auto flex items-center justify-between">
    <div class="text-gray-800 text-lg font-bold">
      <a href="/" class="mr-6 text-gray-800 hover:text-blue-200">Juez Virtual</a>
    </div>
    <div class="flex items-center">

      <div class="hidden sm:ml-6 sm:block">
        <div class="flex space-x-4 pt-2">
          <a href="/" class="mr-6 text-gray-800 hover:text-blue-200">Inicio</a>
          <a href="contest.php?cid=<?php echo $cid ?>" class="mr-6 text-gray-800 hover:text-blue-200">Problemas</a>
          <a href="contestrank.php?cid=<?php echo $cid ?>" class="mr-6 text-gray-800 hover:text-blue-200">Ranking</a>
          <a href="status.php?cid=<?php echo $cid ?>" class="mr-6 text-gray-800 hover:text-blue-200">Envios</a>
          <?php include __DIR__ . '/partials/user-session-menu.php'; ?>
        </div>
      </div>
    </div>
  </div>
</nav>