<?php
$asciiArt = <<<ASCII
    ________________________________________________
   /                                                \
  |    _________________________________________     |
  |   |                                         |    |
  |   |  C:\> _                                 |    |
  |   |                                         |    |
  |   |                                         |    |
  |   |                                         |    |
  |   |                                         |    |
  |   |                                         |    |
  |   |                                         |    |
  |   |_________________________________________|    |
  |                                                  |
   \_________________________________________________/
          \___________________________________/
       ___________________________________________
    _-'    .-.-.-.-.-.-.-.-.-.-.-.-.-.-.-.-.  --- `-_
 _-'.-.-. .---.-.-.-.-.-.-.-.-.-.-.-.-.-.-.--.  .-.-.`-_
:-------------------------------------------------------------------------:
`---._.-------------------------------------------------------------._.---'
ASCII;
header("HTTP/1.0 404 Not Found");
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Página no encontrada</title>
    <style>
        body {
            background-color: black;
            color: green;
        }
        pre {
            font-size: 12px;
            font-family: monospace;
            color: green;
        }
        .center {
            text-align: center;
            margin-top: 50px;
        }
        a {
            color: green;
            text-decoration: none;
        }
        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="center">
        <pre><?php echo $asciiArt; ?></pre>
        <p>Lo sentimos, la página que buscas no se encuentra.</p>
        <p><a href="https://juezvirtual.com/oj/">Vuelve a jv.umsa.bo</a></p>
    </div>
</body>
</html>
