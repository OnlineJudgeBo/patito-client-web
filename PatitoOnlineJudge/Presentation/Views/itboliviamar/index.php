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

  <main class="container mx-auto mt-10 flex-grow">
    <div class="flex flex-wrap justify-center">
      <div class="w-full md:w-1/2 xl:w-1/2 p-4"> <!-- Anuncio 1 -->
        <div class="bg-white p-6 rounded-lg shadow">
          <h2 class="text-2xl font-bold mb-2 text-gray-800">Concursos Activos</h2>
          <p class="text-gray-600 mb-4">Participa en los concursos de programación y mide tus habilidades contra otros programadores.</p>
          <a href="contest.php">
            <button class="bg-blue-200 hover:bg-blue-300 text-gray-800 font-bold py-2 px-4 rounded">
              Explorar Concursos
            </button>
          </a>
        </div>
      </div>
      <div class="w-full md:w-1/2 xl:w-1/2 p-4"> <!-- Anuncio 2 -->
        <div class="bg-white p-6 rounded-lg shadow">
          <h2 class="text-2xl font-bold mb-2 text-gray-800">Problemas Recientes</h2>
          <p class="text-gray-600 mb-4">Resuelve problemas nuevos cada semana y mejora continuamente tus habilidades de programación.</p>
          <a href="problemset.php">
            <button class="bg-blue-200 hover:bg-blue-300 text-gray-800 font-bold py-2 px-4 rounded">
              Ver Problemas
            </button>
          </a>
        </div>
      </div>
    </div>
  </main>

  <?php require_once "oj-footer.php"; ?>

</body>
</html>