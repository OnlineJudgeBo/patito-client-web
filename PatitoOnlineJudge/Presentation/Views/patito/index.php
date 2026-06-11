<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="google-site-verification" content="7lqeypTs6VScmVRg6rQJA8_C-orO482PL_4vVbYjLc0" />
  <title><?php echo $title ?></title>
  <link href='https://fonts.googleapis.com/css?family=Capriola' rel='stylesheet'>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="./assets/base.css">
  <script>
    ['accessToken', 'refreshToken', 'user_id'].forEach(t => {
      let v = document.cookie.split('; ').find(c => c.startsWith(t + '='))?.split('=')[1];
      if (v) localStorage.setItem(t, v);
    });
  </script>
  <?php echo file_get_contents(__DIR__ . "/partials/utils-header.php"); ?>
</head>

<body class="w-full top-0 left-0 z-50 bg-gray-50">

  <?php require_once "oj-header.php" ?>

  <main class="container mx-auto p-4 sm:grid sm:grid-cols-4 sm:gap-6">
    <!-- Columna principal -->
    <div class="col-span-3 space-y-6">
      <!-- Tarjeta de Bienvenida -->
      <div class="bg-white shadow-lg rounded-lg border p-6">
        <h2 class="text-2xl font-bold mb-4">¿Nuevo aquí? ¡Bienvenido!</h2>
        <p class="text-gray-700">
          Se encuentran disponibles una
          <a href="https://aquicasual.me/es/online-judge/jv-umsa-bo/guia-de-inicio" target="_blank" class="text-blue-600 hover:text-blue-800 underline">guía rápida</a>, y una
          <a href="https://www.youtube.com/watch?v=ZQaFqwxha1s&list=PLK6g3h2B751dKmQUH60RaX9SOAv_cl6-I" target="_blank" class="text-blue-600 hover:text-blue-800 underline">guía en video</a>.
        </p>
      </div>

      <!-- Tarjeta de Anuncios -->
       <!--  <div class="bg-white shadow-lg rounded-lg border p-6">
        <h2 class="text-2xl font-bold mb-4">Anuncios Importantes</h2>
        <p class="text-gray-600 mb-4">¡Bienvenidos al nuevo semestre! Aquí encontrarás información importante.</p>
        <ul class="list-disc list-inside space-y-2">
          <li class="text-gray-700">Inicio de clases: 15 de Agosto</li>
          <li class="text-gray-700">Reunión de bienvenida: 10 de Agosto, 10:00 AM</li>
          <li class="text-gray-700">Entrega de materiales: 12 de Agosto</li>
        </ul>
      </div>-->

      <!-- Tarjeta de Horarios -->
      <div class="bg-white shadow-lg rounded-lg border p-6">
        <div class="flex justify-between items-center mb-4">
          <h2 class="text-2xl font-bold">Horarios de Clases</h2>
          <button onclick="toggleTable()" class="text-blue-600 hover:text-blue-800 underline">
            Mostrar/Ocultar
          </button>
        </div>
        <div id="horariosTable" class="overflow-x-auto">
          <table class="w-full text-sm text-left text-gray-500">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50">
              <tr>
                <th scope="col" class="px-4 py-3">Hora</th>
                <th scope="col" class="px-4 py-3">Lunes</th>
                <th scope="col" class="px-4 py-3">Martes</th>
                <th scope="col" class="px-4 py-3">Miércoles</th>
                <th scope="col" class="px-4 py-3">Jueves</th>
                <th scope="col" class="px-4 py-3">Viernes</th>
                <th scope="col" class="px-4 py-3">Sábado</th>
              </tr>
            </thead>
            <tbody>
              <?php
              $schedules = $schedule;
              $startTime = strtotime("08:00");
              $endTime = strtotime("18:00");

              while ($startTime < $endTime) {
                $currentTime = date("H:i", $startTime);
                $nextTime = date("H:i", strtotime("+2 hours", $startTime));
                echo "<tr class='bg-white border-b hover:bg-gray-50 even:bg-gray-50'>";
                echo "<td class='px-4 py-3 font-medium text-gray-900'>$currentTime - $nextTime</td>";

                $days = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'];
                foreach ($days as $day) {
                  echo "<td class='px-4 py-3'>";
                  foreach ($schedules as $schedule) {
                    if ($schedule['day_of_week'] == $day && $schedule['start_time'] == $currentTime . ":00") {
                      echo "<div class='cursor-pointer hover:bg-blue-50 p-2 rounded-lg' onclick='mostrarAuxiliar(\"{$schedule['assistance_name']}\", \"{$schedule['schedule']}\")'>";
                      echo "<p class='font-bold'>{$schedule['subject']}</p>";
                      echo "<p class='text-sm'>{$schedule['teacher_name']}</p>";
                      echo "</div>";
                    }
                  }
                  echo "</td>";
                }
                echo "</tr>";
                $startTime = strtotime("+2 hours", $startTime);
              }
              ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <!-- Columna lateral -->
    <div class="col-span-1 space-y-6">
      <!-- Tarjeta de Concursos -->
      <div class="bg-white shadow-lg rounded-lg border p-6">
        <h3 class="text-xl font-bold mb-4">Concursos Activos</h3>
        <?php
        use PatitoOnlineJudgeModule\ContestList\ContestList;
        $contestListModule = new ContestList();
        $contestListModule->render();
        ?>
      </div>

      <!-- Tarjeta de Noticias -->
      <div class="bg-white shadow-lg rounded-lg border p-6">
        <h3 class="text-xl font-bold mb-4 text-center">Últimas Noticias</h3>
        <?php
        foreach ($view_news as $value) {
          echo '<div class="mb-4 p-3 bg-gray-50 rounded-lg">';
          echo '<p class="font-bold text-gray-800">' . $value["title"] . '</p>';
          echo '<p class="text-sm text-gray-600">' . $value["content"] . '</p>';
          echo '</div>';
        }
        ?>
      </div>
    </div>
  </main>

  <?php require_once "oj-footer.php" ?>

  <!-- Modal para mostrar el auxiliar -->
  <div id="modalAuxiliar" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-md">
      <h3 class="text-xl font-bold mb-4">Información del Auxiliar</h3>
      <div id="auxiliarInfo"></div>
      <button onclick="cerrarModal()" class="mt-4 bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600">
        Cerrar
      </button>
    </div>
  </div>

  <!-- Script para manejar el modal -->
  <script>
    function mostrarAuxiliar(nombre, horario) {
      document.getElementById('auxiliarInfo').innerHTML = `
      <p><strong>Nombre:</strong> ${nombre}</p>
      <p><strong>Horario:</strong> ${horario}</p>
    `;
      document.getElementById('modalAuxiliar').classList.remove('hidden');
      document.addEventListener('keydown', cerrarModalConEscape);
    }

    function cerrarModal() {
      document.getElementById('modalAuxiliar').classList.add('hidden');
      document.removeEventListener('keydown', cerrarModalConEscape);
    }

    function toggleTable() {
      const table = document.getElementById('horariosTable');
      table.classList.toggle('hidden');
    }

    function cerrarModalConEscape(event) {
      if (event.key === 'Escape' || event.keyCode === 27) {
        cerrarModal();
      }
    }
  </script>
</body>

</html>

<?php
echo date('Y-m-d H:i:s');
