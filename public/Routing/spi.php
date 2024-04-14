<?php

echo generatePasswordHash($_GET["spi"]);

function generatePasswordHash($password, $isMd5 = false)
{
    if (!$isMd5) {
        $password = md5($password);
    }
    $salt = sha1(rand());
    $salt = substr($salt, 0, 4);
    $hash = base64_encode(sha1($password . $salt, true) . $salt);
    return $hash;
}
