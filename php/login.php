<?php
require_once 'functions.php';
require_once 'sessions.php';

if (isset($_POST['username'], $_POST['password'])) {
    $user = strtolower($_POST['username']);
    $password = $_POST['password'];
    if (login($user, $password, $mysqli) == true) {
        header('Location: /');
    }else{
        header('Location: /?error=l');
    }
} else {
    echo 'Invalid Request';
}
