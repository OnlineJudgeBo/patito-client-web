<?php

namespace PatitoOnlineJudge\Presentation\Utils;

class Utils
{
    public static function get_current_theme() {
        return __DIR__."/../Views/".$_SERVER["THEME_TEMPLATE"];
    }
}
