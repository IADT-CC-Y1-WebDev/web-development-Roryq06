<?php

function truncate($text, $length) {
    if (strlen($text) > $length) {
        return substr($text, 0, $length) . "...";
    }
    return $text;
}

function formatPrice($amount) {
    return "$" . number_format($amount, 2);
}

function getCurrentYear() {
    return date("Y");
}

?>