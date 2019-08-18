<?php
include 'config.php';
include 'functions.php';
include 'sessions.php';
if (login_check($mysqli) != true){   $url = "Location: /index.php?s=e403";header($url);} 
if (chk_rights($mysqli,OP) != true){$url = "Location: /index.php?s=e403";header($url);}

$ID = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
$PID = filter_input(INPUT_POST, 'PrID-'.$ID, FILTER_SANITIZE_NUMBER_INT);
$printername = (filter_input(INPUT_POST, 'printername-'.$ID, FILTER_SANITIZE_STRING));
$owner = filter_input(INPUT_POST, 'owner-'.$PID, FILTER_SANITIZE_STRING);
$xdim = filter_input(INPUT_POST, 'xdim-'.$PID, FILTER_SANITIZE_NUMBER_INT);
$ydim = filter_input(INPUT_POST, 'ydim-'.$PID, FILTER_SANITIZE_NUMBER_INT);
$description = filter_input(INPUT_POST, 'description-'.$PID, FILTER_SANITIZE_STRING);
$statusbtn = filter_input(INPUT_POST, 'status-'.$PID, FILTER_SANITIZE_STRING);
$nozzle = filter_input(INPUT_POST, 'nozzle-'.$PID, FILTER_SANITIZE_NUMBER_FLOAT,FILTER_FLAG_ALLOW_FRACTION);
$filament = filter_input(INPUT_POST, 'filament-'.$PID, FILTER_SANITIZE_NUMBER_FLOAT,FILTER_FLAG_ALLOW_FRACTION);
$defects = filter_input(INPUT_POST, 'defects-'.$PID, FILTER_SANITIZE_STRING);
$submit = filter_input(INPUT_POST, 'submit-'.$PID, FILTER_SANITIZE_STRING);
@$confirm = filter_input(INPUT_POST, 'confirm-'.$PID, FILTER_SANITIZE_STRING);
if($activ == 'yes'){
    $activ = 1;
} else{
    $activ = 0;
}

$error_msg = '';

if (isset($ID)&& $submit == 'delete' && $confirm == 'delete') {
    $prep_stmt = "SELECT PrID FROM printer WHERE PrID  = ? LIMIT 1";
    $stmt = $mysqli->prepare($prep_stmt);
    if ($stmt) {
        $stmt->bind_param('i', $PID);
        $stmt->execute();
        $stmt->store_result(); 
    } else {
        $error_msg .= 'Database error';
    }    
    if (empty($error_msg)) {
        if ($insert_stmt = $mysqli->prepare(
                "DELETE FROM `printer` WHERE `printer`.`PrID` = ? AND `printer`.`printername` = ? ")) {
             
            $insert_stmt->bind_param('is', $PID, $printername);
            if (!$insert_stmt->execute()) {
                header('Location: /index.php?s=printer&err=delete failure: DELETE');
            }
        }
        header('Location: /index.php?s=printer&success=delprinter');
    }
    else{
        $url = "Location: /index.php?s=printer&err=$error_msg";
        header($url);
    }
} 
else if(isset($ID)&& $submit == 'edit'){
    $error_msg = '';
    $prep_stmt = "SELECT `PrID` FROM `printer` WHERE `printername` = ? LIMIT 1";
    $stmt = $mysqli->prepare($prep_stmt);
    if ($stmt) {
        $stmt->bind_param('s', $printername);
        $stmt->execute();
        $stmt->store_result(); 
    } else {
        $error_msg .= 'Database error';
    }    
    if (empty($error_msg)) {
        $insert_stmt = $mysqli->prepare("UPDATE `printer` SET "
                . "`printername`= ?,"
                . "`owner`= ?,"
                . "`ydim`= ?,"
                . "`xdim`= ?,"
                . "`description`= ?,"
                . "`status`= ?,"
                . "`nozzle`= ?,"
                . "`filament`= ?,"
                . "`defects`= ? "
                . " WHERE `printer`.`PrID` = ?");
        if ($insert_stmt) {             
            $insert_stmt->bind_param('ssiisiddsi', $printername,$owner,$ydim,$xdim,$description,$statusbtn,$nozzle,$filament,$defects,$ID);

            if (!$insert_stmt->execute())
                header('Location: /index.php?s=printer&err=Registration failure: UPDATE');
            $url = "Location: /index.php?s=printer&success=edited";
            header($url);
        }
    }
    else
    {
        $url = "Location: /index.php?s=printer&err=$error_msg";
        header($url);
    }
}
else
{
     $url = "Location: /index.php?s=printer&err=nointention";
     header($url);
}
