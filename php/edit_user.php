<?php
include 'config.php';
include 'functions.php';
include 'sessions.php';
if (login_check($mysqli) != true){   $url = "Location: /index.php?s=e403";header($url);} 
if (chk_rights($mysqli,OP_OP) != true){$url = "Location: /index.php?s=e403";header($url);}

function newpw($mysqli,$UID){
    $random_salt = hash('sha512', uniqid(openssl_random_pseudo_bytes(16), TRUE));
    $password_clear = rdm_string(10);
    $password = hash('sha512', $password_clear . $random_salt);
    if ($insert_stmt = $mysqli->prepare("UPDATE `userbase` SET `password`= ?,`salt`= ? WHERE `UID` = ?")) {
        $insert_stmt->bind_param('ssi', $password, $random_salt, $UID);
        if (! $insert_stmt->execute()){return false;}
    }
    $_SESSION["np"] = $password_clear;
    return true;
}

$ID = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
$UID = filter_input(INPUT_POST, 'UID-'.$ID, FILTER_SANITIZE_NUMBER_INT);
$username = filter_input(INPUT_POST, 'username-'.$UID, FILTER_SANITIZE_STRING);
$rank = filter_input(INPUT_POST, 'rank-'.$UID, FILTER_SANITIZE_NUMBER_INT);
$activ = filter_input(INPUT_POST, 'activ-'.$UID, FILTER_SANITIZE_STRING);
$confirm = filter_input(INPUT_POST, 'confirm-'.$UID, FILTER_SANITIZE_STRING);
$submit = filter_input(INPUT_POST, 'submit-'.$UID, FILTER_SANITIZE_STRING);

if($activ == 'yes'){
    $activ = 1;
} else{
    $activ = 0;
}

$error_msg = '';
if (isset($UID,$username,$rank,$activ,$submit)&& $submit == 'delete' && $confirm == 'delete') {
    $prep_stmt = "SELECT username FROM userbase WHERE UID  = ? LIMIT 1";
    $stmt = $mysqli->prepare($prep_stmt);
    if ($stmt) {
        $stmt->bind_param('i', $UID);
        $stmt->execute();
        $stmt->store_result(); 
    } else {
        $error_msg .= 'Database error';
    }    
    if (empty($error_msg)) {
        if ($insert_stmt = $mysqli->prepare(
                "DELETE FROM `userbase` WHERE `userbase`.`UID` = ? AND `userbase`.`username` = ? AND `userbase`.`activ` = ?")) {
             
            $insert_stmt->bind_param('isi', $UID, $username, $activ);
            if (!$insert_stmt->execute()) {
                header('Location: /index.php?s=manageuser&err=Registration failure: DELETE');
            }
        }
        header('Location: /index.php?s=manageuser&success=deluser');
    }
    else{
        $url = "Location: /index.php?s=manageuser&err=$error_msg";
        header($url);
    }
} 
else if(isset($UID,$username,$rank,$activ)&& $submit == 'edit'){
    $error_msg = '';
    $prep_stmt = "SELECT `UID` FROM `userbase` WHERE `username` = ? LIMIT 1";
    $stmt = $mysqli->prepare($prep_stmt);
    if ($stmt) {
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $stmt->store_result(); 
    } else {
        $error_msg .= 'Database error';
    }    
    if (empty($error_msg)) {
        if ($insert_stmt = $mysqli->prepare(
                "UPDATE `userbase` SET "
                . "`username`= ?,"
                . "`rank`= ?,"
                . "`activ`= ?"
                . " WHERE `userbase`.`UID` = ?")) {             
            $insert_stmt->bind_param('siii', $username, $rank, $activ, $UID);
            if (!$insert_stmt->execute()) {
                header('Location: /index.php?s=manageuser&err=Registration failure: UPDATE');
            }
        }
        header('Location: /index.php?s=manageuser&success=edited');
    }
    else
    {
        $url = "Location: /index.php?s=manageuser&err=$error_msg";
        header($url);
    }
}
else if(isset($UID) && $submit == 'newpw'){
    if(newpw($mysqli, $UID)){
        $url = "Location: /index.php?s=manageuser&newpw=1";
        header($url);
    }
    else{
        $url = "Location: /index.php?s=manageuser&err=FEHLER BEIM RESET";
        header($url);
    }
}
else
{
     $url = "Location: /index.php?s=manageuser&err=nointention";
     header($url);
}
