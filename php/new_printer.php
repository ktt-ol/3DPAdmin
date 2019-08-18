<?php
require 'functions.php';
require 'sessions.php';
$error_msg = "";

$ID = filter_input(INPUT_POST, 'PID', FILTER_SANITIZE_NUMBER_INT);
$printername = filter_input(INPUT_POST, 'printername', FILTER_SANITIZE_STRING);
$owner = filter_input(INPUT_POST, 'owner', FILTER_SANITIZE_STRING);
$xdim = filter_input(INPUT_POST, 'dimx', FILTER_SANITIZE_NUMBER_INT);
$ydim = filter_input(INPUT_POST, 'dimy', FILTER_SANITIZE_NUMBER_INT);
$description = filter_input(INPUT_POST, 'description', FILTER_SANITIZE_SPECIAL_CHARS);
$statusbtn = filter_input(INPUT_POST, 'status', FILTER_SANITIZE_NUMBER_INT);
$nozzle = filter_input(INPUT_POST, 'nozzle', FILTER_SANITIZE_STRING);
$filament = filter_input(INPUT_POST, 'filamentdia', FILTER_SANITIZE_STRING);
$defects = filter_input(INPUT_POST, 'defects', FILTER_SANITIZE_SPECIAL_CHARS);
$submit = filter_input(INPUT_POST, 'submit', FILTER_SANITIZE_STRING);

if($defects == NULL){
    $defects =" ";
}

if (isset($ID,$printername,$owner)) {
    $prep_stmt = "SELECT PrID FROM printer WHERE printername = ? LIMIT 1";
    $stmt = $mysqli->prepare($prep_stmt);
    if ($stmt) {
        $stmt->bind_param('s', $printername);
        $stmt->execute();
        $stmt->store_result(); 
        if ($stmt->num_rows >= 1) {
            $error_msg .= 'There is already a printer with the name '. $printername;
        }
    }
    else {
        $error_msg .= 'Database error';
    }     
    
    if (empty($error_msg)) {
        $SQL = "INSERT INTO `printer` 
            (`PrID`, 
            `printername`, 
            `owner`, 
            `xdim`, 
            `ydim`, 
            `description`, 
            `status`, 
            `nozzle`, 
            `filament`, 
            `defects`) VALUES  (NULL, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        if ($insert_stmt = $mysqli->prepare($SQL)) {
            if($insert_stmt->bind_param('sssssssss', $printername, $owner, $xdim, $ydim, $description,$statusbtn,$nozzle,$filament,$defects))            
            {   
                if (! $insert_stmt->execute()) {                
                    header('Location: /index.php?s=printer&err=Registration failure: INSERT');
                }
                header('Location: /index.php?s=printer&success=newprinter');
            }
        }
       
            
        
    }
    else{
        $url = "Location: /index.php?s=printer&err=$error_msg";
        header($url);
    }
}
  

