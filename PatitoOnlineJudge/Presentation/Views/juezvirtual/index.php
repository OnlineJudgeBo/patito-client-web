<!DOCTYPE html>
<html lang="es" class="h-full">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo $title ?></title>
  <link href='https://fonts.googleapis.com/css?family=Capriola' rel='stylesheet'>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="./assets/base.css">
  <script>
    ['accessToken', 'refreshToken'].forEach(t => {
      let v = document.cookie.split('; ').find(c => c.startsWith(t + '='))?.split('=')[1];
      if (v) localStorage.setItem(t, v);
    });
  </script>
</head>

<body class="flex flex-col min-h-screen">

  <?php require_once "oj-header.php"; ?>

  <!-- Main Content -->
  <main class="flex-1 p-5 mx-auto max-w-7xl">

  <section class="bg-muted py-8">
      <div class="container mx-auto px-4 md:px-6">
        <div class="max-w-3xl mx-auto text-center space-y-6">
          <h1 class="text-4xl font-bold text-gray-800">Bienvenido al Juez Virtual</h1>
          <p class="text-lg text-gray-600">Participa en concursos, resuelve problemas y sube al leaderboard.</p>
        </div>
      </div>
    </section>

    <section class="py-16 bg-white">
      <div class="container mx-auto px-4 md:px-6 grid grid-cols-1 lg:grid-cols-2 gap-8">
        <div>
          <h2 class="text-2xl font-bold mb-8 text-gray-800">Últimas Noticias</h2>
          <div class="bg-gray-100 p-6 rounded-lg shadow-lg">
            <h3 class="text-xl font-semibold mb-2 text-gray-800">Capacitación en Programación para Estudiantes de Secundaria</h3>
            <p class="text-gray-700 mb-4">Si tienes alguna duda o inconveniente durante la capacitación, puedes contactarnos a través de los siguientes medios:</p>
            <ul class="list-disc list-inside mb-4 text-gray-700">
              <li><strong>Correo:</strong> <a href="mailto:reynaldozeballos@gmail.com" class="text-blue-500 hover:underline">reynaldozeballos@gmail.com</a></li>
              <li><strong>Telegram:</strong> <a href="https://t.me/reynaldozeballos" class="text-blue-500 hover:underline">@reynaldozeballos</a></li>
            </ul>
          </div>
        </div>

        <div class="space-y-8 ml-auto">
          <div class="bg-white p-6 rounded-lg shadow-lg">
            <h3 class="text-2xl font-semibold mb-4 text-gray-800">Concursos Activos</h3>
            <p class="text-gray-700 mb-4">Participa en los concursos de programación y mide tus habilidades contra otros programadores.</p>

            <div class="text-center">
              <a href="contest.php" class="bg-blue-200 hover:bg-blue-300 text-gray-800 font-bold py-2 px-4 rounded">Ver Problemas</a>
            </div>
          </div>


          <div class="bg-white p-6 rounded-lg shadow-lg">
            <h3 class="text-2xl font-semibold mb-4 text-gray-800">Problemas Recientes</h3>
            <p class="text-gray-700 mb-4">Resuelve problemas nuevos cada semana y mejora continuamente tus habilidades de programación.</p>
            <div class="text-center">
              <a href="#" class="bg-blue-200 hover:bg-blue-300 text-gray-800 font-bold py-2 px-4 rounded">Ver Problemas</a>
            </div>
          </div>
        </div>
      </div>
    </section>
  </main>

  <?php require_once "oj-footer.php"; ?>

</body>

</html>