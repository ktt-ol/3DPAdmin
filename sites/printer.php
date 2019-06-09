<?php
if (login_check($mysqli) != true){   $url = "Location: /index.php?s=e403";header($url);} 
if (chk_rights($mysqli,OP) != true){$url = "Location: /index.php?s=e403";header($url);}

function convertstatus($status){
    array($return);
    switch ($status) {
        case 0:
            $return['text'] = 'undefined';
            $return['type'] = 'info';
            $return['color'] = 'lightblue';
            break;
        case 1:
            $return['text'] = 'L&auml;uft';
            $return['type'] = 'success';
            $return['color'] = 'green';
            break;
        case 2:
            $return['text'] = 'Kleiner Defekt';
            $return['type'] = 'warning';
            $return['color'] = 'yellow';
            break;
        case 3:
            $return['text'] = 'Gro&szliger Defekt';
            $return['type'] = 'error';
            $return['color'] = 'red';
            break;
        case 4:
            $return['text'] = 'Out of Order';
            $return['type'] = 'dark';
            $return['color'] = 'grey';
            break;
        
        default:
            $return['text'] = 'undefined';
            $return['type'] = 'info';
            $return['color'] = 'blue';
            break;        
    }    
    return $return;
}

function get_printers($mysqli){
    $query ="SELECT "
            . "`PrID`,"
            . " `printername`,"
            . " `owner`,"
            . " `description`,"
            . " `xdim`,"
            . " `ydim`,"
            . " `status`,"
            . " `nozzle`,"
            . " `filament`,"
            . " `defects`"
            . " FROM `printer` ORDER BY `printer`.`PrID` ASC";
    if ($stmt = $mysqli->query($query))
    {   
        $n =0;
        while ($pinter[$n] = $stmt->fetch_assoc()) {
            $n++;
        }
        $stmt->free();
    }
    foreach ($pinter as $row){
        $placeholder_status = convertstatus($row['status']);       
        $output = '<select class="form-control" name="status-'.$row['PrID'].'">';
        for ($index = 0; $index <= 4; $index++) {
            if($index == $row['status'] )
                $output .= '<option style="background-color:'.convertstatus($index)['color'].'" value="'.$index.'" selected>'.convertstatus($index)['text'].'</option>';
            elseif($index != $row['status']){
                 $output .= '<option style="background-color:'.convertstatus($index)['color'].'" value="'.$index.'">'.convertstatus($index)['text'].'</option>';
            } else {
                $output.= "";
            }
        }
        $output .= '</select>';               
        if($row!=NULL)
        echo '<form action="/php/edit_printer.php?id='.$row['PrID'].'" method="post" class="form-horizontal"><tr>
        <th scope="row"><input type="hidden" id="PrID" name="PrID-'.$row['PrID'].'" value="'.$row['PrID'].'">'.$row['PrID'].'</th>
        <td><input class="form-control input-sm" type="text" name="printername-'.$row['PrID'].'" placeholder="'.$row['printername'].'" value="'.$row['printername'].'"/></td>    
        <td><input class="form-control input-sm" type="text" name="owner-'.$row['PrID'].'" placeholder="'.$row['owner'].'" value="'.$row['owner'].'"/></td>
        <!--<td><input class="form-control input-sm" type="text" name="xdim-'.$row['PrID'].'" placeholder="'.$row['xdim'].'" value="'.$row['xdim'].'"/></td>-->
        <!--<td><input class="form-control input-sm" type="text" name="ydim-'.$row['PrID'].'" placeholder="'.$row['ydim'].'" value="'.$row['ydim'].'"/></td>-->
        <td><textarea class="form-control" rows="3" name="description-'.$row['PrID'].'" placeholder="'.$row['description'].'" value="'.$row['description'].'">'.$row['description'].'</textarea></td>
        <td><button type="submit" name="status-'.$row['PrID'].'" value="none" class="btn btn-'.convertstatus($row['status'])['type'].'">'.convertstatus($row['status'])['text'].'</button>'.$output.'</td> 
        <td><textarea class="form-control" rows="3" name="nozzle-'.$row['PrID'].'">'.$row['nozzle'].'</textarea></td>
        <td><textarea class="form-control" rows="3" name="filament-'.$row['PrID'].'">'.$row['filament'].'</textarea></td>
        <td><textarea class="form-control" rows="3" name="defects-'.$row['PrID'].'">'.$row['defects'].'</textarea></td>
        <td style="background-color: rgba(0,255,0,0.1)"><button type="submit" name="submit-'.$row['PrID'].'" value="edit" class="btn btn-success">Edit</button></td>
        ';
        if(chk_rights($mysqli,OP_OP) == true)
        echo '<td style="background-color: rgba(255,0,0,0.1)"><button type="submit" name="submit-'.$row['PrID'].'" value="delete" class="btn btn-danger">Delete</button></br></br>'
         . '<div class="form-check"><input class="form-check-input" type="checkbox" value="delete" name="confirm-'.$row['PrID'].'" id="autoSizingCheck2 id-'.$row['PrID'].'">
            <label class="form-check-label" for="autoSizingCheck2 id-'.$row['PrID'].'">
          Sure?
        </label></div></td>';
        echo '  </tr></form>';
    }   
    return $n+2;
}
?>
<h3>Printer</h3>
<table id="tablePreview" class="table">
  <thead>
      <tr>
      <th>PrinterID</th>
      <th>Printername</th>
      <th>Owner</th>
      <!--<th>X-Dimension in mm</th>-->
      <!--<th>Y-Dimension in mm</th>-->
      <th>Descripton</th>
      <th>Status</th>
      <th>Nozzle</th>
      <th>Filament</th>
      <th>Defects</th>
      <th>&nbsp;</th>
     <?php if(chk_rights($mysqli,OP_OP) == true){?>
      <th>&nbsp;</th>
     <?php  }?>
    </tr>
  </thead>
  <tbody>
    <?php get_printers($mysqli);?>
  </tbody>
</table>
