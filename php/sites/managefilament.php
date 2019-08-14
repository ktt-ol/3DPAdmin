<?php if(isset($_SESSION['np'])){$np = $_SESSION['np'];$_SESSION['np']='';}
if (login_check($mysqli) != true){   $url = "Location: /index.php?s=e403";header($url);} 
if (chk_rights($mysqli,OP_OP) != true){$url = "Location: /index.php?s=e403";header($url);}

function chked($c){
    if($c){
        return "checked";
    }
}

function get_filaments($mysqli){
    $query ="SELECT * FROM `filament` ORDER BY `filament`.`FID` ASC";
    if ($stmt = $mysqli->query($query))
    {   
        $n =0;
        while ($result[$n] = $stmt->fetch_assoc()) {
            $n++;
        }
        $stmt->free();
    }

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
                
        if($row!=NULL){
            echo '<form action="/php/edit_filament.php?id='.$row['FID'].'" method="post" class="form-horizontal"><tr>
            <th scope="row"><input type="hidden" id="FID" name="FID-'.$row['FID'].'" value="'.$row['FID'].'">'.$row['FID'].'</th>
                <td><input class="form-control input-sm" type="text" name="name-'.$row['FID'].'" placeholder="'.$row['name'].'" value="'.$row['name'].'"/></td>
                <td><input class="form-control input-sm" type="text" name="roll_weight-'.$row['FID'].'" placeholder="'.$row['roll_weight'].'" value="'.$row['roll_weight'].'"/></td>
                <td><input class="form-control input-sm" type="text" name="weight-'.$row['FID'].'" placeholder="'.$row['weight'].'" value="'.$row['weight'].'"/></td>
                <td><input class="form-control input-sm" type="text" name="multiplier-'.$row['FID'].'" placeholder="'.$row['multiplier'].'" value="'.$row['multiplier'].'"/></td>
                <td><input class="form-control input-sm" type="text" name="color-'.$row['FID'].'" placeholder="'.$row['color'].'" value="'.$row['color'].'"/></td>
                <td><input class="form-control input-sm" type="text" name="owner-'.$row['FID'].'" placeholder="'.$row['owner'].'" value="'.$row['owner'].'"/></td>
                <td><input class="form-control input-sm" type="text" name="thickness-'.$row['FID'].'" placeholder="'.$row['thickness'].'" value="'.$row['thickness'].'"/></td>

                <td style="background-color: rgba(0,255,0,0.1)"><button type="submit" name="submit-'.$row['FID'].'" value="edit" class="btn btn-success">Edit</button></td>
                <td style="background-color: rgba(255,0,0,0.1)"><button type="submit" name="submit-'.$row['FID'].'" value="delete" class="btn btn-danger">Delete</button></br></br>'
                 . '<div class="form-check"><input class="form-check-input" type="checkbox" value="delete" name="confirm-'.$row['FID'].'" id="autoSizingCheck2 id-'.$row['FID'].'">
                    <label class="form-check-label" for="autoSizingCheck2 id-'.$row['FID'].'">
                  Sure?
                </label></div></td>
            </tr></form>';
        }
    }   
    return $n+1;
}
?>
<h3>Filament-Management</h3>

<table id="tablePreview" class="table">
  <thead>
    <tr>
      <th>FID</th>
      <th>Name</th>
      <th>Startweight</th>
      <th>Momentary weight</th>
      <th>Pricemultiplicator</th>
      <th>Color</th>
      <th>Owner</th>
      <th>Thickness</th>
      <th>&nbsp;</th>
      <th>&nbsp;</th>
      <th>&nbsp;</th>
    </tr>
  </thead>
  <tbody>
    <?php $last = get_filaments($mysqli);?>
    <?php echo '<form action="/php/edit_filament.php?add=true&id='.$last.'" method="post" class="form-horizontal"><tr>
            <th scope="row"><input type="hidden" id="FID" name="FID-'.$last.'" value="'.$last.'">'.$last.'</th>
                <td><input class="form-control input-sm" type="text" name="name-'.$last.'" placeholder="Beispiel-Filament" value=""/></td>
                <td><input class="form-control input-sm" type="text" name="roll_weight-'.$last.'" placeholder="750" value="750"/></td>
                <td><input class="form-control input-sm" type="text" name="weight-'.$last.'" placeholder="750" value=""/></td>
                <td><input class="form-control input-sm" type="text" name="multiplier-'.$last.'" placeholder="1.0" value=""/></td>
                <td><input class="form-control input-sm" type="text" name="color-'.$last.'" placeholder="rainbow" value=""/></td>
                <td><input class="form-control input-sm" type="text" name="owner-'.$last.'" placeholder="ktt" value="ktt"/></td>
                <td><input class="form-control input-sm" type="text" name="thickness-'.$last.'" placeholder="1.75" value="1.75"/></td>
                <td>&nbsp;</td>
                <td style="background-color: rgba(0,0,255,0.1)"><button type="submit" name="submit-'.$last.'" value="add" class="btn btn-info">Add</button></td>
                </label></div></td>
            </tr></form>'; ?>
  </tbody>
</table>

