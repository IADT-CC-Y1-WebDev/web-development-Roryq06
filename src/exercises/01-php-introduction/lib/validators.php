<?php

function isValidEmail($email) {
    return strpos($email, "@") !== false;
}

?>