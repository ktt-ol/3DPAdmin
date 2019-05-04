<?php

if (login_check($mysqli) != true)
    {   $url = "Location: /index.php?s=e403";
        header($url);
    }    
    
function get_member_group($mysqli,$cat){
    $query = "SELECT `name` FROM `ranks` WHERE `RID`= $cat LIMIT 1";
    $result = mysqli_query($mysqli, $query);
    $group = mysqli_fetch_assoc($result);
    return $group['name'];
}

function get_printer_name($mysqli,$printerid){
    $query = "SELECT 'printername' FROM `printer` WHERE `PrID` = $printerid LIMIT 1";
    $result = mysqli_query($mysqli, $query);
    $group = mysqli_fetch_assoc($result);
    return $group['printername'];
}

function get_filament_name($mysqli,$FID){
    $query = "SELECT `FID`,`name`,`color` FROM `filament` WHERE `FID` = $FID LIMIT 1";
    $result = mysqli_query($mysqli, $query);
    $group = mysqli_fetch_assoc($result);
    return $group;
}
    
function get_history($mysqli){
    $query ="SELECT "
            . "`PID`,"
            . " `username`,"
            . " `operator`,"
            . " `weight`,"
            . " `pricecat`,"
            . " `price`,"
            . " `filament`,"
            . " `printer`,"
            . " `printedat`,"
            . " `description`,"
            . " `is_creditprint`"
            . " FROM `history` ORDER BY `history`.`PID` DESC";
    if ($stmt = $mysqli->query($query))
    {   
        $n =0;
        while ($history[$n] = $stmt->fetch_assoc()) {
            $n++;
        }
        $stmt->free();
    }
    $sumweight = 0;
    $sumprice = 0;
    $table = "";
    foreach ($history as $row){
      $creditstyle = "";
      if($row['is_creditprint'] == 1){$creditstyle = "style=\"background-color: #ddffdd\"";}
      if($row!=NULL){
      $fila = get_filament_name($mysqli, $row['filament']);
      $table .= '<tr '.$creditstyle.'><th scope="row">'.$row['PID'].'</th><td>'.$row['username'].'</td>
      <td>'.$row['operator'].'</td><td>'.$row['description'].'</td><td>'.get_member_group($mysqli, $row['pricecat']).'</td>
      <td>'.$row['weight'].' g</td><td>'.$row['price'].' &euro;</td><td>'. $fila['FID'].' | '.$fila['name'].' | '.$fila['color'].'</td>
      <td>'.get_printer_name($mysqli, $row['printer']).'</td><td>'.$row['printedat'].'</td>      
    </tr>';
      $sumweight += $row['weight'];
      $sumprice += $row['price'];
      }

    }
    $summe =  '<tr style="background-color:#eeeeee"><th scope="row">&nbsp;</th><td>&nbsp;</td>
      <td>&nbsp;</td><td>&nbsp;</td><td><b>Summe:</b></td>
      <td>'.$sumweight.' g</td><td>'.$sumprice.' &euro;</td><td>&nbsp;</td>
      <td>&nbsp;</td><td>&nbsp;</td>      
    </tr>';
    echo $summe.$table;
    
}

?>

<h3>History</h3>
<!--Table-->
<table id="tablePreview" class="table">
<!--Table head-->
  <thead>
    <tr>
      <th>#</th>
      <th>Eigentümer</th>
      <th>Operator</th>
      <th>Beschreibung</th>
      <th>Kategorie</th>   
      <th>Gewicht</th>
      <th>Spendenvorschlag</th>
      <th>Filament</th>
      <th>Drucker</th>
      <th>Zeit</th>      
    </tr>
  </thead>
  <!--Table head-->
  <!--Table body-->
  <tbody>
    <?php get_history($mysqli); ?>
  </tbody>
  <!--Table body-->
</table>
<!--Table-->