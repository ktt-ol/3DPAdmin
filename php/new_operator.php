<?php
require 'functions.php';
require 'sessions.php';

$error_msg = "";
 
if (isset($_POST['username'])) {
    $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
    $opedby = $_SESSION['user'];
    $activ = filter_input(INPUT_POST, 'activ', FILTER_SANITIZE_NUMBER_INT);
    $prep_stmt = "SELECT UID FROM userbase WHERE username = ? LIMIT 1";
    $stmt = $mysqli->prepare($prep_stmt);
    if ($stmt) {
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $stmt->store_result(); 
        if ($stmt->num_rows >= 1) {
            $error_msg .= 'There is already a user with the name '. $username;
        }
    } else {
        $error_msg .= 'Database error';
    }
    if (empty($error_msg)) {
        $random_salt = hash('sha512', uniqid(openssl_random_pseudo_bytes(16), TRUE));
        $password_clear = rdm_string(10);
        $password = hash('sha512', $password_clear . $random_salt);
        if ($insert_stmt = $mysqli->prepare("INSERT INTO userbase (username, password, salt, activ, opedby) VALUES (?, ?, ?, ?, ?)")) {
            
            $insert_stmt->bind_param('sssis', $username, $password, $random_salt, $activ, $opedby);
            if (! $insert_stmt->execute()) {
                header('Location: /index.php?s=newoperator&err=Registration failure: INSERT');
            }
        }
        $_SESSION['post']['newoppass'] = $password_clear;
        header('Location: /index.php?s=newoperator&success=newop');
    }
    else{
        $url = "Location: /index.php?s=newoperator&err=$error_msg";
        header($url);
    }
}
  

