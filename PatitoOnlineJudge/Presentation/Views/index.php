<!DOCTYPE html>
<html lang="en" class="h-full">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Juez Virtual Patito</title>
  <link href='https://fonts.googleapis.com/css?family=Capriola' rel='stylesheet'>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="./assets/base.css">

</head>

<body class="flex flex-col h-full">

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
                    $user_url = "userinfo.php?user=" . $value["user_id"];
                  }
                ?>
                  <tr class="border-b transition-colors hover:bg-muted/50 <?php echo $css ?> ">
                    <td class="p-4">
                      <?php
                      if (isset($_SESSION["user_id"]) && $_SESSION["user_id"] == $value["user_id"] || isset($_SESSION["administrator"]) && $_SESSION["administrator"] == 1) {
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
                          echo sprintf("<a href='./showError.php?sid=%d' target='_blank' >%s</a>", $value["solution_id"], $judge_result[$value["result"]]);
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

    <div id="privacyPolicyModal" class="fixed z-10 inset-0 flex items-center justify-center" style="display:none;">
      <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
      <div class="relative bg-white rounded-lg overflow-hidden shadow-xl max-w-screen-md w-full m-4">
        <div class="px-6 py-4">
          <h3 class="text-lg leading-6 font-medium text-gray-900">Tips del día</h3>
        </div>
        <div class="prose max-w-screen-md p-6 overflow-y-auto" style="max-height: 70vh; background-color: #fff; border: 1px solid #e2e8f0; border-radius: 0.375rem; box-shadow: 0 2px 4px 0 rgba(0, 0, 0, 0.1);">
          <h2 class="text-2xl font-bold mb-4">Tu respuesta es <strong>wrong answer</strong>?</h2>
          <div class="text-center mx-auto mb-4">
            <p>¿Te gustaría tener una pista para ver en donde esta el error?</p>
            <p>Haz clic en wrong answer, Runtime error o Compilation error</p>
            <div class="flex justify-center">
              <iframe src="https://giphy.com/embed/rCCiQ5R65O3vrIoFVN" width="280" height="205" frameBorder="0" allowFullScreen></iframe>
            </div>
          </div>
        </div>
        <div class="bg-gray-50 px-4 py-3 sm:px-6 flex justify-end p-4 gap-4 flex-row">
          <button onclick="showPrivacyPolicy(false)" type="button" class="inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-black text-base font-medium text-white">Cerrar</button>
        </div>
      </div>
    </div>

  </main>

  <?php require_once "oj-footer.php" ?>

</body>
<script>
  showPrivacyPolicy(true);
  function showPrivacyPolicy(show) {
    var modal = document.getElementById('privacyPolicyModal');
    modal.style.display = show ? 'flex' : 'none';
  }
</script>
</html>