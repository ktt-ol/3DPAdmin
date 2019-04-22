<?php
require 'functions.php';
require 'sessions.php';
/* 
 * The MIT License
 *
 * Copyright 2018 arneh.
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

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
            $insert_stmt = ("UPDATE `userbase` SET `password` = $password_new_to_db AND SET `salt` = $random_salt WHERE `userbase`.`username` = $editedby");            
            if (mysqli_query($mysqli, $insert_stmt)) {
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
  

