<?php if(isset($_SESSION['np'])){$np = $_SESSION['np'];$_SESSION['np']='';}
if (login_check($mysqli) != true){   $url = "Location: /index.php?s=e403";header($url);} 
if (chk_rights($mysqli,OP_OP) != true){$url = "Location: /index.php?s=e403";header($url);}

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
        $output = '<select class="form-control" name="rank-'.$ID.'" id="rank">';
        foreach ($opname as $value) {
            if($rank == $value['RID'] && $value !== NULL && $value['name']!="Administrator") 
                $output .= '<option value="'.$value['RID'].'" selected>'.$value['name'].'</option>';
            elseif($value != NULL && $value['name']!= "Administrator"){
                $output .= '<option value="'.$value['RID'].'">'.$value['name'].'</option>';
            }
        }
        $output .= '</select>';
    }else{
        foreach ($opname as $value) {
            if($rank == $value['RID'] && $value !== NULL) 
                $output = $value['name'];
        }
    }
      
    return $output;
}

function get_user($mysqli){
    $query ="SELECT `UID`, `username`, `activ`, `status`, `opedby`, `created`, `lastprint`, `rank` FROM `userbase` ORDER BY `userbase`.`UID` ASC";
    if ($stmt = $mysqli->query($query))
    {   
        $n =0;
        while ($result[$n] = $stmt->fetch_assoc()) {
            $n++;
        }
        $stmt->free();
    }
    $query = "SELECT `RID`,`name` FROM `ranks` WHERE 1";
    $stmt = $mysqli->query($query);

    foreach ($result as $row){
        $selected = '';
        $nselected = '';
        $activ = '';
        if($row['activ']){
            $selected = 'selected';
            $activ = 'yes';
        }else{
            $nselected = 'selected';
            $activ = 'no';
        }        
        
        $rank = get_rank_names($mysqli,$row['rank'],$row['UID']);
        
        if($row!=NULL && $_SESSION['uid']!=$row['UID']){
            echo '<form action="/php/edit_user.php?id='.$row['UID'].'" method="post" class="form-horizontal"><tr>
            <th scope="row"><input type="hidden" id="UID" name="UID-'.$row['UID'].'" value="'.$row['UID'].'">'.$row['UID'].'</th>
                <td><input class="form-control input-sm" type="text" name="username-'.$row['UID'].'" placeholder="'.$row['username'].'" value="'.$row['username'].'"/></td>

                <td>'.$rank.'</td>
                <td><select class="form-control" name="activ-'.$row['UID'].'" id="activ"><option '.$selected.' >yes</option><option '.$nselected.' >no</option></select></td>
                <td>'.$row['opedby'].'</td>
                <td>'.$row['created'].'</td>
                <td>'.$row['lastprint'].'</td>
                <td style="background-color: rgba(0,0,255,0.1)"><button type="submit" name="submit-'.$row['UID'].'" value="newpw" class="btn btn-info">New PW</button></td>
                <td style="background-color: rgba(0,255,0,0.1)"><button type="submit" name="submit-'.$row['UID'].'" value="edit" class="btn btn-success">Edit</button></td>
                <td style="background-color: rgba(255,0,0,0.1)"><button type="submit" name="submit-'.$row['UID'].'" value="delete" class="btn btn-danger">Delete</button></br></br>'
                 . '<div class="form-check"><input class="form-check-input" type="checkbox" value="delete" name="confirm-'.$row['UID'].'" id="autoSizingCheck2 id-'.$row['UID'].'">
                    <label class="form-check-label" for="autoSizingCheck2 id-'.$row['UID'].'">
                  Sure?
                </label></div></td>
            </tr></form>';
        }elseif($row!=NULL && $_SESSION['uid']==$row['UID']){
            echo '<tr>
            <th scope="row"><input type="hidden" id="UID" name="UID" value="'.$row['UID'].'">'.$row['UID'].'</th>
                <td>'.$row['username'].'</td>
                <td>'.$rank.'</td>
                <td>'.$activ.'</td>
                <td>'.$row['opedby'].'</td>
                <td>'.$row['created'].'</td>
                <td>'.$row['lastprint'].'</td>
                <td style="background-color: rgba(0,0,255,0.1)"><button type="submit" name="submit-'.$row['UID'].'" value="newpw" class="btn btn-info">New PW</button></td>
                <td style="background-color: rgba(0,255,0,0.1)">&nbsp;</td>
                <td style="background-color: rgba(255,0,0,0.1)">&nbsp;</td>
            </tr>';
        }
    }   
    return $n+2;
}
?>
<h3>User</h3>
<?php
if(@isset($np,$_GET['newpw']))
{
    echo "</br></br><h4>Das Passwort lautet: ".$np."</h4></br></br>"; 
}elseif(@isset($_GET['err']))
{
    echo "</br></br><h4>Fehler: ".$_GET['err']."</h4></br></br>"; 
}
?>
<table id="tablePreview" class="table">
  <thead>
    <tr>
      <th>UID</th>
      <th>Name</th>
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
    <?php get_user($mysqli);?>
  </tbody>
</table>

