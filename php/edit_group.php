<?php
require 'config.php';
require 'functions.php';
//if (login_check($mysqli) != true){   $url = "Location: /index.php?s=e403";header($url);} 
//if (chk_rights($mysqli,OP_OP) != true){$url = "Location: /index.php?s=e403";header($url);}

$ID = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
$RID = filter_input(INPUT_POST, 'RID-'.$ID, FILTER_SANITIZE_NUMBER_INT);
$groupname = filter_input(INPUT_POST, 'name-'.$RID, FILTER_SANITIZE_STRING);
$rank = filter_input(INPUT_POST, 'rank-'.$RID, FILTER_SANITIZE_NUMBER_INT);
$ppg = filter_input(INPUT_POST, 'ppg-'.$RID, FILTER_SANITIZE_NUMBER_INT);
@$description = filter_input(INPUT_POST, 'description-'.$RID, FILTER_SANITIZE_STRING);
$confirm = filter_input(INPUT_POST, 'confirm-'.$RID, FILTER_SANITIZE_STRING);
$submit = filter_input(INPUT_POST, 'submit-'.$RID, FILTER_SANITIZE_STRING);

$error_msg = '';
if (isset($RID,$groupname,$rank,$ppg,$submit,$confirm)&& $submit == 'delete' && $confirm == 'delete') {
    $prep_stmt = "SELECT RID, name, rank, pricepergramm FROM ranks WHERE RID  = ? LIMIT 1";
    $stmt = $mysqli->prepare($prep_stmt);
    if ($stmt) {
        $stmt->bind_param('i', $RID);
        $stmt->execute();
        $stmt->store_result(); 
    } else {
        $error_msg .= 'Database error';
    }    
    if (empty($error_msg)) {
        if ($insert_stmt = $mysqli->prepare(
                "DELETE FROM `ranks` WHERE `ranks`.`RID` = ? AND `ranks`.`name` = ? AND `ranks`.`pricepergramm` = ?")) {
             
            $insert_stmt->bind_param('isi', $RID, $groupname, $ppg);
            if (!$insert_stmt->execute()) {
                header('Location: /index.php?s=manageop&err=Registration failure: DELETE');
            }
        }
        echo 'ok';
        header('Location: /index.php?s=manageop&success=delgroup');
    }
    else{
        $url = "Location: /index.php?s=manageop&err=$error_msg";
        header($url);
    }
} 
else if(isset($RID,$groupname,$rank,$ppg,$submit)&& $submit == 'edit'){
    $prep_stmt = "SELECT RID FROM ranks WHERE name  = ? LIMIT 1";
    $stmt = $mysqli->prepare($prep_stmt);
    if ($stmt) {
        $stmt->bind_param('s', $groupname);
        $stmt->execute();
        $stmt->store_result(); 
    } else {
        $error_msg .= 'Database error';
    }    
    if (empty($error_msg)) {
        if ($insert_stmt = $mysqli->prepare(
                "UPDATE `ranks` SET "
                . "`name`= ?,"
                . "`rank`= ?,"
                . "`pricepergramm`= ?,"
                . "`description`= ?"
                . " WHERE `ranks`.`RID` = ?")) {
             
            $insert_stmt->bind_param('siisi', $groupname, $rank, $ppg, $description, $RID);
            if (!$insert_stmt->execute()) {
                header('Location: /index.php?s=manageop&err=Registration failure: UPDATE');
            }
        }
        echo 'ok';
        header('Location: /index.php?s=manageop&success=newgroup');
    }
    else
    {
        $url = "Location: /index.php?s=manageop&err=$error_msg";
        header($url);
    }
}
else
{
     $url = "Location: /index.php?s=manageop&err=$error_msg";
     header($url);
}
