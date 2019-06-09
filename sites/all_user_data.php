<?php
include 'config.php';
include 'functions.php';
include 'sessions.php';
if (login_check($mysqli) != true){   $url = "Location: /index.php?s=e403";header($url);} 
if (chk_rights($mysqli, ADMIN) != true){$url = "Location: /index.php?s=e403";header($url);}
function chked($c){
    if($c){
        return "checked";
    }
}

function get_rank_names($mysqli,$rank,$ID){
    $query = "SELECT `RID`,`name`,`rank` FROM `ranks` WHERE 1";
    $stmt = $mysqli->query($query);
    $x = 1;
    while ($opname[$x] = $stmt->fetch_assoc()) {
        $x++;
    }
    $stmt->free();
    
    $query = "SELECT `rank` FROM `userbase` WHERE `username` = '".$_SESSION['user']."'";
    $stmt = $mysqli->query($query);
    $rights = $stmt->fetch_assoc();
    
    if($rights['rank'] != $rank){
        $output = '';
        foreach ($opname as $value) {
            if($rank == $value['RID'] && $value !== NULL ) 
                $output .= $value['name'];
        }
        $output .= '';
    }else{
        foreach ($opname as $value) {
            if($rank == $value['RID'] && $value !== NULL) 
                $output = $value['name'];
        }
    }      
    return $output;
}
function get_sum_of_prints($mysqli){
    $queryprints = "SELECT UID,username FROM `userbase` ORDER BY `userbase`.`UID` ASC";
    if ($stmt = $mysqli->query($queryprints))
    {   
        $n =0;
        while ($userprints[$n] = $stmt->fetch_assoc()) {
            $n++;
        }
    }
    $n =0;
    foreach ($userprints as $user) {        
        $op = $user['username'];
        $UID = $user['UID'];
        $query_userprints = "SELECT SUM(`weight`),COUNT(`weight`) FROM `history` WHERE `history`.`operator` = '$op'";   
        if ($stmt2 = $mysqli->query($query_userprints))
        {   
            $printhistory = $stmt2->fetch_assoc();
            if($printhistory['SUM(`weight`)']== NULL){$printhistory['SUM(`weight`)'] = 0;}
            if($printhistory['COUNT(`weight`)']== NULL){$printhistory['COUNT(`weight`)'] = 0;}
            $userprints[$UID]['weightsum'] = $printhistory['SUM(`weight`)']; 
            $userprints[$UID]['printcount'] = $printhistory['COUNT(`weight`)'];             
        }
        $n++;
        
    }
    $stmt->free();
    $stmt2->free();
    return $userprints;
}
function all_get_user($mysqli){
    $query ="SELECT * FROM `userbase` ORDER BY `userbase`.`UID` ASC";
    $query2 ="SELECT * FROM `credit` WHERE `UID` = ''";

    if ($stmt = $mysqli->query($query))
    {   
        $n =0;
        while ($result[$n] = $stmt->fetch_assoc()) {
            $n++;
        }
        $stmt->free();
    }
    $n =0;
    foreach ($result as $u) {
        $uid= $u['UID'];
        $query2 ="SELECT `value` FROM `credit` WHERE `userid` = '$uid'";
        if ($stmt2 = $mysqli->query($query2)){
            $result[$n]['value']= mysqli_fetch_array($stmt2)[0];
        }
        $n++;
    }
        $stmt2->free();
    $history = get_sum_of_prints($mysqli);
    $x = 0;
    foreach ($result as $row){
        $selected = '';
        $nselected = '';
        $activ = '';
        if($row['activ']){
            $activ = 'yes';
        }else{
            $activ = 'no';
        }        
        
        $rank = get_rank_names($mysqli,$row['rank'],$row['UID']);
        if($row!= NULL)
           echo '<tr>
            <th scope="row">'.$row['UID'].'</th>
                <td>'.$row['username'].'</td>
                <td>'.$row['value'].' g</td>
                <td>'.$history[$row['UID']]['weightsum'].' g</td>
                <td>'.$history[$row['UID']]['printcount'].' g</td>
                <td>'.$rank.'</td>
                <td>'.$activ.'</td>
                <td>'.$row['opedby'].'</td>
                <td>'.$row['created'].'</td>
                <td>'.$row['lastprint'].'</td>
            </tr></form>';
           $x++;
    }   
    return $n+1;
}
?>

<table id="tablePreview" class="table">
  <thead>
    <tr>
      <th>UID</th>
      <th>Name</th>
      <th>Credits</th>
      <th>Printed Material</th>
      <th>Number Prints</th>
      <th>Rank</th>
      <th>Activ</th>
      <th>Oped by</th>
      <th>Created</th>
      <th>Last print</th>
      <th>&nbsp;</th>
      <th>&nbsp;</th>
      <th>&nbsp;</th>
    </tr>
  </thead>
  <tbody>
    <?php all_get_user($mysqli);?>
  </tbody>
</table>