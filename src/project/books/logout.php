<?php
require_once 'php/lib/session.php';
startSession();

session_destroy();

header("Location: index.php");
exit;