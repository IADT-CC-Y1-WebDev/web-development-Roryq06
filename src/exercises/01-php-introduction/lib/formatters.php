<?php

function formatPhoneNumber($number) {
    if (strlen($number) == 10) {
        return substr($number, 0, 3) . "-" .
               substr($number, 3, 3) . "-" .
               substr($number, 6, 4);
    }
    return $number;
}

?>