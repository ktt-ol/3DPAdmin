<?php
include 'config.php';
include 'functions.php';
include 'sessions.php';
if (login_check($mysqli) != true){   $url = "Location: /index.php?s=e403";header($url);} 
if (chk_rights($mysqli,OP_OP) != true){$url = "Location: /index.php?s=e403";header($url);}

$ID = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
$FID = filter_input(INPUT_POST, 'FID-'.$ID, FILTER_SANITIZE_NUMBER_INT);
$name = (filter_input(INPUT_POST, 'name-'.$FID, FILTER_SANITIZE_STRING));
$multiplier = (filter_input(INPUT_POST, 'multiplier-'.$FID, FILTER_SANITIZE_NUMBER_FLOAT,FILTER_FLAG_ALLOW_FRACTION));
$roll_weight = filter_input(INPUT_POST, 'roll_weight-'.$FID, FILTER_SANITIZE_NUMBER_FLOAT,FILTER_FLAG_ALLOW_FRACTION);
$weight = filter_input(INPUT_POST, 'weight-'.$FID, FILTER_SANITIZE_NUMBER_FLOAT,FILTER_FLAG_ALLOW_FRACTION);
$color = filter_input(INPUT_POST, 'color-'.$FID, FILTER_SANITIZE_STRING);
$owner = filter_input(INPUT_POST, 'owner-'.$FID, FILTER_SANITIZE_STRING);
$thickness = filter_input(INPUT_POST, 'thickness-'.$FID, FILTER_SANITIZE_STRING);

$confirm = filter_input(INPUT_POST, 'confirm-'.$FID, FILTER_SANITIZE_STRING);
$submit = filter_input(INPUT_POST, 'submit-'.$FID, FILTER_SANITIZE_STRING);

if($activ == 'yes'){
    $activ = 1;
} else{
    $activ = 0;
}

$error_msg = '';
if (isset($FID)&& $submit == 'delete' && $confirm == 'delete') {
    $prep_stmt = "SELECT name FROM filament WHERE FID  = ? LIMIT 1";
    $stmt = $mysqli->prepare($prep_stmt);
    if ($stmt) {
        $stmt->bind_param('i', $FID);
        $stmt->execute();
        $stmt->store_result(); 
    } else {
        $error_msg .= 'Database error';
    }    
    if (empty($error_msg)) {
        if ($insert_stmt = $mysqli->prepare(
                "DELETE FROM `filament` WHERE `filament`.`FID` = ?")) {
             
            $insert_stmt->bind_param('i', $FID);
            if (!$insert_stmt->execute()) {
                header('Location: /index.php?s=managefilament&err=Filament failure: DELETE');
            }
        }
        header('Location: /index.php?s=managefilament&success=delfilament');
    }
    else{
        $url = "Location: /index.php?s=managefilament&err=$error_msg";
        header($url);
    }
} 
else if(isset($FID)&& $submit == 'edit'){
    $error_msg = '';
    $prep_stmt = "SELECT `FID` FROM `filament` WHERE `name` = ? LIMIT 1";
    $stmt = $mysqli->prepare($prep_stmt);
    if ($stmt) {
        $stmt->bind_param('s', $name);
        $stmt->execute();
        $stmt->store_result(); 
    } else {
        $error_msg .= 'Database error';
    }    
    if (empty($error_msg)) {
        
        if ($insert_stmt = $mysqli->prepare(
                "UPDATE `filament` SET "
                . "`name`= ?,"
                . "`roll_weight`= ?,"
                . "`weight`= ?,"
                . "`multiplier`= ?,"                
                . "`color`= ?,"
                . "`owner`= ?,"
                . "`thickness`= ?"
                . " WHERE `filament`.`FID` = ?")) 
        {   
            $insert_stmt->bind_param('sdddssdi', $name, $roll_weight, $weight, $multiplier, $color, $owner, $thickness, $FID);
            if (!$insert_stmt->execute()) {
                header('Location: /index.php?s=managefilament&err=Registration failure: UPDATE');
            }
        }
        header('Location: /index.php?s=managefilament&success=edited');
    }
    else
    {
        $url = "Location: /index.php?s=managefilament&err=$error_msg";
        header($url);
    }
}
elseif(isset($FID)&& $submit == 'add'){
    $error_msg = '';
    $prep_stmt = "SELECT `FID` FROM `filament` WHERE `name` = ? LIMIT 1";
    $stmt = $mysqli->prepare($prep_stmt);
    if ($stmt) {
        $stmt->bind_param('s', $name);
        $stmt->execute();
        $stmt->store_result(); 
    } else {
        $error_msg .= 'Database error';
    }    
    if (empty($error_msg)) {
        
        if ($insert_stmt = $mysqli->prepare(
                "INSERT INTO `filament` 
                    (`FID`, `weight`, `name`, `multiplier`, `roll_weight`, `color`, `owner`, `thickness`)
                    VALUES 
                    (NULL, ?, ?, ?, ?, ?, ?, ?);")) 
        {   
            $insert_stmt->bind_param('dsddssd', $weight, $name, $multiplier, $roll_weight, $color, $owner, $thickness);
            if (!$insert_stmt->execute()) {
                header('Location: /index.php?s=managefilament&err=Filament failure: Add');
            }
        }
        header('Location: /index.php?s=managefilament&success=add');
    }
    else
    {
        $url = "Location: /index.php?s=managefilament&err=$error_msg";
        header($url);
    }
}
else
{
     $url = "Location: /index.php?s=managefilament&err=nointention";
     header($url);
}
