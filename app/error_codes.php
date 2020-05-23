<?php
switch ($code) {
    case "4" :
        $text = "К сожалению при обработке запроса произошла ошибка";
        $end = 1;
        $log = 1;
        break;
    default:
        $log = 1;
        break;
}