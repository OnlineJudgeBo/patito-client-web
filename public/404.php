<?php
header("HTTP/1.0 404 Not Found");
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Página no encontrada</title>
    <style>
        body {
            background-color: #000000;
            color: #33ff33;
            font-family: 'Courier New', monospace;
            font-size: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .center {
            text-align: center;
            margin-top: 50px;
        }

        a {
            color: #33ff33;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }

        #typewriter {
            border-right: 2px solid #33ff33;
            padding-right: 5px;
            white-space: nowrap;
            overflow: hidden;
        }
    </style>
</head>

<body>
    <div class="center">
        <div id="Htext"></div>
        <img src='./space.gif'>
    </div>
</body>
<script>
    const text = `
    [SYSTEM FAILURE 404]
    <p>Error: La página solicitada no pudo ser localizada en este servidor. El enlace puede estar roto o la página puede haber sido eliminada.</p>
    <p>Consultas <br>Samuel Loza - samuel.loza26@gmail.com </p>
    <p>https://github.com/starsaminf</p>
    <hr>
    [END OF LINE]
    `;
    let index = 0;
    const speed = 100;

    function typeWriter() {
        if (index < text.length) {
            document.getElementById("Htext").innerHTML = text.substring(0, index + 1) + '|';
            index++;
            setTimeout(typeWriter, speed);
        } else {
            document.getElementById("Htext").innerHTML = text;
        }
    }

    window.onload = function() {
        typeWriter();
    };
</script>
</html>
