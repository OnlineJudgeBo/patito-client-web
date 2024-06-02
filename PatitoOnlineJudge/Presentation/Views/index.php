<!DOCTYPE html>
<html lang="es" class="">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="google-site-verification" content="7lqeypTs6VScmVRg6rQJA8_C-orO482PL_4vVbYjLc0" />
  <title><?php echo $title ?></title>
  <link href='https://fonts.googleapis.com/css?family=Capriola' rel='stylesheet'>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="./assets/base.css">
  <script>
  ['accessToken', 'refreshToken', 'user_id'].forEach(t=>{let v=document.cookie.split('; ').find(c=>c.startsWith(t+'='))?.split('=')[1];if(v)localStorage.setItem(t,v);});
</script>

</head>

<body class="w-full fixed top-0 left-0 z-50">

  <?php
  require_once "oj-header.php" ?>

  <main class="container mx-auto p-4 sm:grid sm:grid-cols-4 sm:gap-4">
    <div class="col-span-3">
      <div class="bg-gray-100 p-0 mb-2">
        <div class="container mx-auto bg-white shadow-lg rounded-lg border p-6">

          <div class="my-1">
            <h2 class="text-lg font-semibold">¿Nuevo aquí? ¡Bienvenido!</h2>
            <p>Se encuentran disponibles una <a href="https://aquicasual.me/es/online-judge/jv-umsa-bo/guia-de-inicio" target="_blank"><span class="text-blue-600 underline">guía rápida</span> </a>, una
              <span class="text-blue-600 underline"><s>guía en video</s></span>.
            </p>
          </div>
        </div>
      </div>

      <div class="rounded-lg border bg-card text-card-foreground shadow-sm w-full lg:block hidden">
        <div class="flex flex-col space-y-1.5 p-6">
          <h3 class="text-2xl font-semibold leading-none tracking-tight">Últimos envíos</h3>
        </div>
        <div class="flex flex-1">
          <div class="w-full overflow-auto">
            <table class="w-full border-collapse border-slate-500">
              <thead>
                <tr class="border-b transition-colors hover:bg-muted/50">
                  <th class="p-4 font-semibold">RunID</th>
                  <th class="p-4 font-semibold">Usuario</th>
                  <th class="p-4 font-semibold">Problema</th>
                  <th class="p-4 font-semibold">Lenguaje</th>
                  <th class="p-4 font-semibold">Resultado</th>
                  <th class="p-4 font-semibold">Memoria</th>
                  <th class="p-4 font-semibold">Tiempo</th>
                  <th class="p-4 font-semibold">Hora de Envio</th>
                </tr>
              </thead>

              <tbody>
                <?php
                require __DIR__ . "/../../../Legacy/Include/const.inc.php";
                $showSource = "";
                foreach ($view_last_runs as $key => $value) {
                  $css = "evenrow";
                  if ($key % 2 == 0) {
                    $css = "oddrow";
                  }
                  if (!empty($value["contest_id"])) {
                    $url = "problem.php?cid=" . $value['contest_id'] . "&pid=" . $value['num'];
                    $user_url = "contestrank.php?cid=" . $value['contest_id'] . "&user_id=" . $value["user_id"] . "#" . $value["user_id"];
                  } else {
                    $url = "problem.php?id=" . $value['problem_id'];
                    $user_url = "status.php?user_id=" . $value["user_id"];
                  }
                ?>
                  <tr class="border-b transition-colors hover:bg-muted/50 <?php echo $css ?> ">
                    <td class="p-4">
                      <?php
                      if (isset($_SESSION["user_id"]) && $_SESSION["user_id"] == $value["user_id"] || 
                        isset($_SESSION["Administrador"]) && $_SESSION["Administrador"] == "Administrador" ||
                        isset($_SESSION["Docente"])       && $_SESSION["Docente"]       == "Docente"       ||
                        isset($_SESSION["Auxiliar"])      && $_SESSION["Auxiliar"]      == "Auxiliar"
                      ) {
                        $showSource = "showsource.php?id=" . $value["solution_id"];
                      }
                      echo $value["solution_id"];
                      ?>
                    </td>
                    <td class="p-4">
                      <a class="text-blue-500 hover:text-blue-700 transition duration-300 ease-in-out" href="<?php echo $user_url ?>">
                        <?php echo $value["user_id"] ?>
                      </a>
                    </td>
                    <td class="p-4">
                      <a class="text-blue-500 hover:text-blue-700 transition duration-300 ease-in-out" href="<?php echo $url ?>">
                        <?php
                        if (isset($value["contest_id"])) {
                          echo $PID2[$value["num"]];
                        } else {
                          echo $value["problem_id"];
                        }
                        ?>
                      </a>
                    </td>
                    <td class="p-4">
                      <a class="text-blue-500 hover:text-blue-700 transition duration-300 ease-in-out" href="<?php echo $showSource ?>">
                        <?php echo $language_name[$value["language"]]; ?>
                      </a>
                    </td>
                    <td class="p-4">
                      <div class="font-bold decoration-solid decoration-sky-500 result-<?php echo $judge_color[$value["result"]] ?>">
                        <?php
                        if ($value["result"] <= 3) {
                          echo  "<div class='pending'>" . $judge_result[$value["result"]] . "</div>";
                        } elseif ($value["result"] == 4) {
                          echo  $judge_result[$value["result"]];
                        } else {
                          if (isset($_SESSION["user_id"]) && $_SESSION["user_id"] == $value["user_id"] || 
                          isset($_SESSION["Administrador"]) && $_SESSION["Administrador"] == "Administrador" ||
                          isset($_SESSION["Docente"])       && $_SESSION["Docente"]       == "Docente"       ||
                          isset($_SESSION["Auxiliar"])      && $_SESSION["Auxiliar"]      == "Auxiliar" ) {
                            echo sprintf("<a href='./showError.php?sid=%d' target='_blank' >%s</a>", $value["solution_id"], $judge_result[$value["result"]]);
                          } else {
                            echo $judge_result[$value["result"]];
                          }
                        }
                        ?>
                      </div>
                    </td>
                    <td class="p-4">
                      <div class="font-bold decoration-solid decoration-sky-500 result-<?php echo $judge_color[$value["result"]] ?>">
                        <?php echo $value["memory"] ?>
                      </div>
                    </td>
                    <td class="p-4">
                      <div class="font-bold decoration-solid decoration-sky-500 result-<?php echo $judge_color[$value["result"]] ?>">
                        <?php echo $value["time"] ?>
                      </div>
                    </td>
                    <td class="p-4">
                      <?php echo $value["in_date"] ?>
                    </td>
                  <?php
                }
                  ?>
                  </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>

    <div class="col-span-1">
      <div class="rounded-lg border bg-card text-card-foreground shadow-sm w-full mb-2">
        <div class="px-4 py-3" role="alert">
          <?php

          use PatitoOnlineJudgeModule\ContestList\ContestList;

          $contestListModule = new ContestList();
          $contestListModule->render();
          ?>
        </div>
      </div>


      <div class="rounded-lg border bg-card text-card-foreground">
        <div class="px-4 py-3" role="alert">
          <h3 class="text-2xl font-bold text-center mb-3">Últimas Noticias</h3>
          <?php
          foreach ($view_news as $value) {
            echo '<div class="flex mb-4 p-3">';
            echo '<div>';
            echo '<p class="font-bold">' . $value["title"] . '</p>';
            echo '<p class="text-sm">' . $value["content"] . '</p>';
            echo '</div>';
            echo '</div>';
          }
          ?>
        </div>
      </div>
    </div>

  </main>

  <?php require_once "oj-footer.php" ?>

</body>

</html>