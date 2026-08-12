<nav class="w-full border-b-4 border-green-700 bg-slate-900 shadow-sm">
  <div class="mx-auto max-w-7xl px-2 sm:px-6 lg:px-8">
    <div class="relative flex h-16 items-center justify-between">
      <div class="absolute inset-y-0 left-0 flex items-center sm:hidden">
        <button type="button" class="mobile-menu-button rounded-md p-2 text-gray-400 hover:text-yellow-400 focus:outline-none focus:ring-2 focus:ring-white" aria-controls="mobile-menu" aria-expanded="false">
          <span class="sr-only">Abrir menú del curso</span>
          <svg class="open-icon h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
          <svg class="close-icon hidden h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18 18 6M6 6l12 12"/></svg>
        </button>
      </div>
      <div class="flex flex-1 items-center justify-center sm:justify-start">
        <a href="<?php echo htmlspecialchars((string) $_SERVER['APP_DOMAIN'], ENT_QUOTES, 'UTF-8'); ?>" class="shrink-0" aria-label="Inicio de Patito">
          <img class="h-16 w-auto" src="./assets/logo.svg" alt="Juez Virtual Patito">
        </a>
        <div class="hidden sm:ml-6 sm:flex sm:items-center sm:space-x-1">
          <a href="courses.php" class="text-sm md:text-xl text-white hover:text-yellow-400 rounded-md px-3 py-2">Cursos</a>
          <a href="course.php?id=<?php echo (int) $courseId; ?>" class="text-sm md:text-xl text-white hover:text-yellow-400 rounded-md px-3 py-2">Contenido</a>
          <?php if (!empty($assignmentId)): ?>
          <a href="course-contest.php?id=<?php echo (int) $courseId; ?>&assignmentId=<?php echo (int) $assignmentId; ?>" class="text-sm md:text-xl text-white hover:text-yellow-400 rounded-md px-3 py-2">Problemas</a>
          <a href="course-submissions.php?id=<?php echo (int) $courseId; ?>&assignmentId=<?php echo (int) $assignmentId; ?>" class="text-sm md:text-xl text-white hover:text-yellow-400 rounded-md px-3 py-2">Status</a>
          <a href="course-ranking.php?id=<?php echo (int) $courseId; ?>&assignmentId=<?php echo (int) $assignmentId; ?>" class="text-sm md:text-xl text-white hover:text-yellow-400 rounded-md px-3 py-2">Ranking</a>
          <?php endif; ?>
        </div>
      </div>
      <div class="hidden sm:block">
        <div class="flex gap-2"><?php include __DIR__ . '/partials/user-session-menu.php'; ?></div>
      </div>
    </div>
  </div>
  <div class="mobile-menu hidden sm:hidden" id="mobile-menu">
    <div class="space-y-1 px-2 pb-3 pt-2">
      <a href="courses.php" class="block text-sm md:text-xl text-white hover:text-yellow-400 rounded-md px-3 py-2">Cursos</a>
      <a href="course.php?id=<?php echo (int) $courseId; ?>" class="block text-sm md:text-xl text-white hover:text-yellow-400 rounded-md px-3 py-2">Contenido</a>
      <?php if (!empty($assignmentId)): ?>
      <a href="course-contest.php?id=<?php echo (int) $courseId; ?>&assignmentId=<?php echo (int) $assignmentId; ?>" class="block text-sm md:text-xl text-white hover:text-yellow-400 rounded-md px-3 py-2">Problemas</a>
      <a href="course-submissions.php?id=<?php echo (int) $courseId; ?>&assignmentId=<?php echo (int) $assignmentId; ?>" class="block text-sm md:text-xl text-white hover:text-yellow-400 rounded-md px-3 py-2">Status</a>
      <a href="course-ranking.php?id=<?php echo (int) $courseId; ?>&assignmentId=<?php echo (int) $assignmentId; ?>" class="block text-sm md:text-xl text-white hover:text-yellow-400 rounded-md px-3 py-2">Ranking</a>
      <?php endif; ?>
      <?php include __DIR__ . '/partials/user-session-menu.php'; ?>
    </div>
  </div>
</nav>
<script>
document.addEventListener('DOMContentLoaded', function () {
  const button = document.querySelector('.mobile-menu-button');
  const menu = document.getElementById('mobile-menu');
  if (!button || !menu) return;
  button.addEventListener('click', function () {
    menu.classList.toggle('hidden');
    button.querySelector('.open-icon').classList.toggle('hidden');
    button.querySelector('.close-icon').classList.toggle('hidden');
    button.setAttribute('aria-expanded', String(!menu.classList.contains('hidden')));
  });
});
</script>
