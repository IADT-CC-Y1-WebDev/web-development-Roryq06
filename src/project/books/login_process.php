<?php
require_once 'php/lib/session.php';
startSession();

// Example hardcoded user (simple version)
$username = $_POST['username'];
$password = $_POST['password'];

if ($username === 'admin' && $password === 'password') {
    $_SESSION['user_id'] = 1;

    header("Location: index.php");
    exit;
} else {
    echo "Invalid login";
}