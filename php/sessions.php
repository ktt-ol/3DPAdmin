<?php
function sec_session_start() {
    session_start();            
    session_regenerate_id();    
    $session_name = '3dadmin';   // vergib einen Sessionnamen
    $secure = true;
    $cookieParams = session_get_cookie_params();
    if(!session_id()){
        session_name($session_name);
        session_set_cookie_params($cookieParams["lifetime"],$cookieParams["path"],$cookieParams["domain"],$secure);
    }
}
    sec_session_start();
?>
