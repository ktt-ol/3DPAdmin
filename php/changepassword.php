<?php
require 'functions.php';
require 'sessions.php';

$error_msg = "";

$password_old = filter_input(INPUT_POST, 'password_old', FILTER_SANITIZE_STRING);
$password_new = filter_input(INPUT_POST, 'password_new', FILTER_SANITIZE_STRING);
$password_new_confirm = filter_input(INPUT_POST, 'password_new_confirm', FILTER_SANITIZE_STRING);
 
if (
        isset($password_old)&&
        isset($password_new)&&
        isset($password_new_confirm)) 
    {
    //if(login($_SESSION['user'], $password_old, $mysqli)){        // CHECK IF OLD PASSWORD IS CORRECT
        $editedby = $_SESSION['user'];               
        
        // Check if both new passwords are the same to avoid typos 
        if ($password_new == $password_new_confirm) 
        {   
            // Generate new Password with salted hash
            $random_salt = hash('sha512', uniqid(openssl_random_pseudo_bytes(16), TRUE));
            $password_new_to_db = hash('sha512', $password_new . $random_salt);
            
            // Place session information for LogIn-Tracking
            $browser = $_SERVER['HTTP_USER_AGENT'];

            // Put in the new password        
            $update = "UPDATE `userbase` SET "
                    . "`password` = '$password_new_to_db', "
                    . "`salt` = '$random_salt' "
                    . "WHERE `userbase`.`username` = '$editedby';";
            
            if (mysqli_query($mysqli, $update)) {
                $_SESSION['login_string'] = hash('sha512',$password_new_to_db.$browser);
                header('Location: /index.php?s=changepassword&success=passwordchanged');

            } else {
                $error_msg ="Couln't change password: Database error";
                header("Location: /index.php?s=changepassword&err=$error_msg");
            }                
        } else {
            $error_msg ="Passwords do not match!";
            $url = "Location: /index.php?s=changepassword&err=$error_msg";
            header($url);
        }
    //}             ///////////
}else{
    $error_msg = "Please fill out the whole form!";
    $url = "Location: /index.php?s=changepassword&err=$error_msg";
    header($url);
}
  

