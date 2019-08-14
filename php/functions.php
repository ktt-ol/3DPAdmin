<?php
include_once $_SERVER['DOCUMENT_ROOT'].'/php/config.php';
function errhandle($get){
    
    if(isset($get['success'])){
        $msg = filter_var($get['success'], FILTER_SANITIZE_SPECIAL_CHARS);
        echo  " <div class=\"alert alert-success\" role=\"alert\">
                <strong>It worked!</strong> $msg 
                <button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-label=\"Close\">
                <span aria-hidden=\"true\">&times;</span>
                </button>
                </div>";    
    }
    
    if(isset($get['error'])||isset($get['err'])){
        $msg = filter_var($get['error'], FILTER_SANITIZE_SPECIAL_CHARS);
        if(empty($msg)){
            $msg = filter_var($get['err'], FILTER_SANITIZE_SPECIAL_CHARS);
        }
        echo "<div class=\"alert alert-danger\" role=\"alert\">
            <strong>Oops!</strong> $msg 
            <button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-label=\"Close\">
            <span aria-hidden=\"true\">&times;</span>
            </button>
            </div>";
    }   
}
function mysqlquery($query){
  
        $connect = mysqli_connect(
                MYSQLI_HOST, 
                MYSQLI_USER, 
                MYSQLI_PASS, 
                MYSQLI_BASE);
        
        if(!$connect){
            echo mysqli_error($connect);
            echo 'NO CONNECTION TO DATABASE';
        }
        elseif ($connect) { 
            //echo 'DB OK';   
            return mysqli_fetch_array(mysqli_query($connect, $query));
        }
        else{
            echo 'UNDEFINED ERROR';
        }
    }

function userid($user){
        $query ="SELECT `UID` FROM `userbase` WHERE `username` = '$user'";
        if ($stmt = mysqlquery($query))
        {   
            $uid = $stmt['UID'];
        }   
        return $uid ;
    }
function usergrp($user){
        $query ="SELECT `rank` FROM `userbase` WHERE `username` = '$user'";
        if ($stmt = mysqlquery($query))
        {   
            $rid = $stmt['rank'];
            $query2 ="SELECT `rank` FROM `ranks` WHERE `RID` = '$rid'";
            if ($stmt2 = mysqlquery($query2))
            {
                $power = $stmt2['rank'];
            }
            if($power > OP){
                $query3 ="SELECT `RID` FROM `ranks` WHERE `rank` = '".OP."' ORDER BY RID DESC LIMIT 1 ";
                $stmt3 = mysqlquery($query3);
                $rid = $stmt3['RID'];
            }
        }   
        return $rid ;
    }
function username($userid){
        $query ="SELECT `username` FROM `userbase` WHERE `UID` = '$user'";
        if ($stmt = mysqlquery($query))
        {   
            $username = $stmt['username'];
        }   
        return $username ;
    }

function rdm_string($length = 10) {
    $pass="";
    $n = 0 ;
    for ($i = 0; $i <= $length; $i++) {
        $alphabet = "abcdefghijklmnopqrstuwxyzABCDEFGHIJKLMNOPQRSTUWXYZ0123456789!-_";
        $n = rand(0, strlen($alphabet)-1);
        $pass = $pass.$alphabet[$n];
    }
    return $pass;
}

function get_current_credit($sess,$mysqli){
    $uid = $sess['uid'];
    if ($stmt = $mysqli->prepare("SELECT `value` FROM `credit` WHERE `userid` = '$uid' LIMIT 1")) {
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($free_print_value);
        $stmt->fetch();
    }
    return $free_print_value;
}
function chksite($name){
    $site_folder = 'sites/';
    $name = filter_var($name, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    if(file_exists($site_folder. $name . '.php')){
        $include_site = $site_folder.$name.'.php';
    } else {
        $include_site = $site_folder.'e404.php';
    }
    return $include_site;
}

function login($user, $password, $mysqli) {
    if ($stmt = $mysqli->prepare("SELECT UID, username, password, salt FROM userbase WHERE username = ? LIMIT 1")) {
        $stmt->bind_param('s', $user);  
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($uid, $user, $db_password, $salt);
        $stmt->fetch();
        $password = hash('sha512', $password . $salt);
        
        if ($stmt->num_rows == 1) {
            if (bruteforce($uid, $mysqli) == true) {
                return false;
            } else {
                if ($db_password == $password) {
                    $browser = $_SERVER['HTTP_USER_AGENT'];
                    $uid = preg_replace("/[^0-9]+/", "", $uid);
                    $_SESSION['uid'] = $uid;
                    $user = preg_replace("/[^a-zA-Z0-9_\-]+/","",$user);
                    $_SESSION['user'] = $user;
                    $_SESSION['login_string'] = hash('sha512',$password.$browser);                    
                    return true;
                } else {
                    $now = time();
                    $mysqli->query("INSERT INTO login_attempts(UID, time) VALUES ('$uid', '$now')");
                    return false;
                }
            }
        } else {
            return false;
        }
    }
}

function bruteforce($uid, $mysqli) {
    $now = time(); 
    $valid_attempts = $now - (60 * 60);
 
    if ($stmt = $mysqli->prepare("SELECT time FROM login_attempts WHERE UID = ? AND time > '$valid_attempts'")) {
        $stmt->bind_param('i', $uid);
        $stmt->execute();
        $stmt->store_result(); 
        if ($stmt->num_rows > LOGIN_ATTEMPTS) {
            return true;
        } else {
            return false;
        }
    }
}
function get_printer_status(){
    $SQL = "SELECT * FROM `printer` ORDER BY  PrID";
    $connect = mysqli_connect(MYSQLI_HOST,MYSQLI_USER,MYSQLI_PASS,MYSQLI_BASE);    
    if ($connect) { 
        $result = mysqli_query($connect, $SQL);
        for($i=0; $i<mysqli_num_rows($result); $i++) {
            $allprinters[] = mysqli_fetch_assoc($result);
        }
    }
    foreach($allprinters as $single_printer){
        switch ($single_printer['status']) {
            case 0:
                echo "<div class=\"boxed\">"
                    . "<h3>".$single_printer['printername']."</h3>"
                    . "<h5><i>".$single_printer['owner']."</i></h5>"
                    . "<div class=\"alert alert-dark\" role=\"alert\">Undefiniert"
                    . "</div></div>";
                break;
            case 1:
                echo "<div class=\"boxed\">"
                    . "<h3>".$single_printer['printername']."</h3>"
                    . "<h5><i>".$single_printer['owner']."</i></h5>"
                    . "<div class=\"alert alert-success\" role=\"alert\">OK!"
                    . "</div></div>";
                break;
            case 2:
                echo "<div class=\"boxed\">"
                    . "<h3>".$single_printer['printername']."</h3>"
                    . "<h5><i>".$single_printer['owner']."</i></h5>"
                    . "<div class=\"alert alert-warning\" role=\"alert\">Kleinere Defekte: ".$single_printer['defects']
                    . "</div></div>";
                break;
            case 3:
                echo "<div class=\"boxed\">"
                    . "<h3>".$single_printer['printername']."</h3>"
                    . "<h5><i>".$single_printer['owner']."</i></h5>"
                    . "<div class=\"alert alert-danger\" role=\"alert\">Größere Defekte: ".$single_printer['defects']
                    . "</div></div>";
                break;
            case 4:
                echo "<div class=\"boxed\">"
                    . "<h3>".$single_printer['printername']."</h3>"
                    . "<h5><i>".$single_printer['owner']."</i></h5>"
                    . "<div class=\"alert alert-dark\" role=\"alert\">OUT OF ORDER : ".$single_printer['defects']
                    . "</div></div>";
                break;
            default:
                echo "<div class=\"boxed\">"
                . "<h2>".$single_printer['printername']."</h2>"
                    . "<h5><i>".$single_printer['owner']."</i></h5>"
                    . "<div class=\"alert alert-light\" role=\"alert\"> NaN"
                    . "</div></div>";
                break;
        }
                
    }
                       
}
function get_filament_status(){
    $SQL = "SELECT * FROM `filament` ORDER BY weight";
    $connect = mysqli_connect(MYSQLI_HOST,MYSQLI_USER,MYSQLI_PASS,MYSQLI_BASE);    
    if ($connect) { 
        $result = mysqli_query($connect, $SQL);
        for($i=0; $i<mysqli_num_rows($result); $i++) {
            $allfilaments[] = mysqli_fetch_assoc($result);
        }
    }
    foreach ($allfilaments as $onefilaments) {
        if($onefilaments['weight']>0)
        echo "<tr>
                  <td>".$onefilaments['FID']."</td>
                  <td>".$onefilaments['name']."</td>
                  <td>".$onefilaments['color']."</td>
                  <td>".$onefilaments['weight']."</td>
                  <td>".$onefilaments['thickness']."</td>
                  <td>".$onefilaments['owner']."</td>
                </tr>";
                        
    }
}
function get_lastprint_status(){
    $SQL = "SELECT * FROM `history` ORDER BY PID DESC LIMIT 15";
    $connect = mysqli_connect(MYSQLI_HOST,MYSQLI_USER,MYSQLI_PASS,MYSQLI_BASE);    
    if ($connect) { 
        $result = mysqli_query($connect, $SQL);
        for($i=0; $i<mysqli_num_rows($result); $i++) {
            $lasthistory[] = mysqli_fetch_assoc($result);
        }
    }
    foreach ($lasthistory as $oneprint) {

        if(true)
        {   
            $SQL = "SELECT `printername` FROM `printer` WHERE `PrID` =".$oneprint['printer'];
            $result = mysqli_query($connect, $SQL);
            $printername= mysqli_fetch_assoc($result)['printername'];
            echo "<tr>
                  <td>".$oneprint['operator']."</td>
                  <td>".$printername."</td>
                  <td>".$oneprint['weight']." g</td>
                  <td>".$oneprint['printdate']."</td>
                </tr>";
            
        }
                        
    }
}


function login_check($mysqli) {
    if (isset($_SESSION['uid'],$_SESSION['user'],$_SESSION['login_string']))
        {
            $uid = $_SESSION['uid'];
            $login_string = $_SESSION['login_string'];
            $username = $_SESSION['user'];
            $browser = $_SERVER['HTTP_USER_AGENT'];
 
            if ($stmt = $mysqli->prepare("SELECT password FROM userbase WHERE UID = ? LIMIT 1"))
                {
                    $stmt->bind_param('i', $uid);
                    $stmt->execute();
                    $stmt->store_result();
                    if ($stmt->num_rows == 1)
                        {
                            $stmt->bind_result($password);
                            $stmt->fetch();
                            $login_check = hash('sha512', $password . $browser); 
                            if ($login_check == $login_string)
                                {
                                    return true;
                                } else {
                            return false;
                            }
                        } else {
                        return false;
                        }
                    } else {
                    return false;
                    }
        } else {
        return false;
        }
}

function esc_url($url) {
    if('' == $url){return $url;}
 
    $url = preg_replace('|[^a-z0-9-~+_.?#=!&;,/:%@$\|*\'()\\x80-\\xff]|i', '', $url);
    $strip = array('%0d', '%0a', '%0D', '%0A');
    $url = (string) $url; 
    $count = 1;
    while ($count) {
        $url = str_replace($strip, '', $url, $count);
    }
 
    $url = str_replace(';//', '://', $url); 
    $url = htmlentities($url); 
    $url = str_replace('&amp;', '&#038;', $url);
    $url = str_replace("'", '&#039;', $url); 
    if ($url[0] !== '/') {
        return '';
    } else {
        return $url;
    }
}

function chk_rights($mysqli , $rank = 0, $uid_ex = NULL){
    
    if (isset($_SESSION['uid'],$_SESSION['user']))
        {
            $uid = $_SESSION['uid'];
            $username = $_SESSION['user'];            
            
            if ($stmt = $mysqli->prepare("SELECT `activ`, `rank` FROM `userbase` WHERE `UID` = ? LIMIT 1"))
                {   
                    if($uid_ex != NULL){
                        $stmt->bind_param('i', $uid_ex);
                    }else{
                        $stmt->bind_param('i', $uid);
                    }
                    $stmt->execute();
                    $stmt->store_result();
                    if ($stmt->num_rows == 1)
                        {
                            $stmt->bind_result($activ, $u_rank);
                            $stmt->fetch();
                            
                            $stmt = $mysqli->prepare("SELECT `rank` FROM `ranks` WHERE `RID` = ? LIMIT 1");
                            $stmt->bind_param('i', $u_rank);
                            $stmt->execute();
                            $stmt->store_result();
                            
                            if ($stmt->num_rows == 1)
                            {   
                                $stmt->bind_result($_dbrank);
                                $stmt->fetch();
                                if (($_dbrank >= $rank) && $activ == true && $_dbrank != NULL)
                                    {
                                        return true;
                                } else {
                                return false;
                                }
                            }
                        } else {
                        return false;
                        }
                    } else {
                    return false;
                    }
        } else {
        return false;
        }   
    
    return TRUE;
}



// Printing functions
function get_category($mysqli, $cat, $block = 0 ){
    $query = "SELECT `RID`, `name`, `pricepergramm` FROM `ranks` WHERE 1 ORDER BY `ranks`.`rank` DESC";
    if ($stmt = $mysqli->query($query))
    {   
        $n =0;
        while ($categories[$n] = $stmt->fetch_assoc()) {
            $n++;
        }
        $stmt->free();
    }
    echo "<div class=\"radio\">";
    $n = 0;
    foreach ($categories as $group) {
        $checked ='';
        if($cat == $group['RID']){
            $checked = "checked=\"checked\"";
            
        }
        if($categories[$n]!= NULL && $group['name'] != "NOT_DEFINED" && $group['name'] != "Operator-Admin" && $group['name'] != "Administrator"){
            if($block){
                if($checked=="checked=\"checked\""){
                     echo "<label for=\"radios-$n\">"
                . "<input type=\"hidden\" name=\"pricecat\" id=\"radios-$n\" value=\"".$group['RID']."\" />"
                . " ".badgegroup($group['name'],0)." (".$group['pricepergramm']." ct/g)"
                . "</label></br>";
                }
            }else{
                echo "<label for=\"radios-$n\">"
                . "<input type=\"radio\" name=\"pricecat\" id=\"radios-$n\" value=\"".$group['RID']."\" $checked />"
                . " ".badgegroup($group['name'],0)." (".$group['pricepergramm']." ct/g)"
                . "</label></br>";
            }
        }
        $n++;           
    }      
    echo "</div>";
}
function get_ops($mysqli, $op){
    $query = "SELECT `UID`, `username`, `activ` FROM `userbase` WHERE `activ` = 1";
    if ($stmt = $mysqli->query($query))
    {   
        $n =0;
        while ($categories[$n] = $stmt->fetch_assoc()) {
            $categories[$n]['canprint'] = chk_rights($mysqli, OP, $categories[$n]['UID']);
            $n++;
        }
        $stmt->free();
    }
    else{
        echo 'error';
    }
    $n = 0;
    foreach ($categories as $group) {
        $checked ='';
        if($op == $group['UID']){
            $checked = 'selected="selected"';
        }
        if($categories[$n]!= NULL && $categories[$n]['canprint']== true){
            echo "<option value=\"".$categories[$n]['UID']."\" $checked>".$categories[$n]['UID']." | ".$categories[$n]['username']."</option>";
        }
        $n++;           
    }            
}
function get_printer($mysqli,$printer){
    $query = "SELECT * FROM `printer` WHERE `status` <=2";
    if ($stmt = $mysqli->query($query))
    {   
        $n =0;
        while ($categories[$n] = $stmt->fetch_assoc()) {
            $n++;
        }
        $stmt->free();
    }
    else{
        echo 'error';
    }
    $n = 0;
    foreach ($categories as $group) {
        $checked ='';
        if($printer == $n)
            $checked == "selected";
        if($categories[$n]!= NULL){
            echo "<option value=\"".$categories[$n]['PrID']."\" $checked>".$categories[$n]['printername']." | &#8709 ".$categories[$n]['nozzle']." | ".$categories[$n]['owner']."</option>";
        }
        $n++;           
    }            
}

function badgegroup($name,$heading = true) {
    switch ($name) {
        case "Member":
            $return = '<span class="badge badge-warning">'.$name.'</span>';
            break;
        case "Mainframe":
            $return = '<span class="badge badge-dark">'.$name.'</span>';
            break;
        case "Operator":
            $return = '<span class="badge badge-success">'.$name.'</span>';
            break;
        case "External":
            $return = '<span class="badge badge-danger">'.$name.'</span>';
            break;
        default:
            $return = $name;
            break;
        
    }
    if($heading){
        $return = '<h6>'.$return.'</h6>';
    }
    return $return;   
}
function get_filament($mysqli, $filament){
    $query = "SELECT * FROM `filament` WHERE `weight` != 0";
    if ($stmt = $mysqli->query($query))
    {   
        $n =0;
        while ($categories[$n] = $stmt->fetch_assoc()) {
            $n++;
        }
        $stmt->free();
    }
    else{
        echo 'error';
    }
    $n = 0;
    foreach ($categories as $group) {
        $checked ='';
        if($filament == $n)
            $checked == "selected";
        if($categories[$n]!= NULL&& $categories[$n]['weight'] >0) {
            echo "<option value=\"".$categories[$n]['FID']."\" $checked>".$categories[$n]['FID']." | ".$categories[$n]['weight']."g left  | ".$categories[$n]['color']." | &#8709 ".$categories[$n]['thickness']." | ".$categories[$n]['owner']." | ".$categories[$n]['multiplier']."x</option>";
        }
        $n++;           
    }            
}
?>
