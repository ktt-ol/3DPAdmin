<?php

require_once 'functions.php';
require_once 'sessions.php';
 
$_SESSION = array();
$params = session_get_cookie_params(); 
setcookie(session_name(),'', time() - 42000, $params["path"], $params["domain"],$params["secure"]);
session_destroy();
header('Location: /');
