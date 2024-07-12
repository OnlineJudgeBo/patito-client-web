<?php
function getStatusTime($event_start_time, $event_end_time) {
    date_default_timezone_set("America/La_Paz");
    $start_time = strtotime(str_replace("-", "/", $event_start_time));
    $end_time   = strtotime(str_replace("-", "/", $event_end_time));
    $now        = strtotime(date("Y/m/d H:i:s"));

    if ($now > $end_time) {
        return "<span class=result-green>Termino el ".$event_end_time."</span>";
    } elseif ($now < $start_time) {
        return "<span class=result-blue>Iniciara el ".$event_start_time."</span>
                <span class='result-green'>
                <br>" . formatTimeLength($now, $start_time, $end_time) . "</span>";
    } else {
        return "<span class='result-red'> Corriendo </span>
        <br>
        <span class=result-green> Termina el: " .$event_end_time ."<span>
        ". formatTimeLength($now, $end_time, $start_time) . " </span>";
    }
}

function formatTimeLength($current_time, $start_time, $end_time) {
    $diff_seconds = $start_time - $current_time;

    $days = floor($diff_seconds / (24 * 3600));
    $diff_seconds %= 24 * 3600;
    $hours = floor($diff_seconds / 3600);
    $diff_seconds %= 3600;
    $minutes = floor($diff_seconds / 60);
    $seconds = $diff_seconds % 60;

    $result = $days . " dias, " . $hours . " horas, " . $minutes . " minutos, " . $seconds . " segundos";
    return "<div class='nowdate' data-start_time='".$start_time."' data-end_time='".$end_time."'>" . $result . "</div>";
}

