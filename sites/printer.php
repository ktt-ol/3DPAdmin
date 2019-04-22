<?php
if (login_check($mysqli) != true){   $url = "Location: /index.php?s=e403";header($url);} 
if (chk_rights($mysqli,OP_OP) != true){$url = "Location: /index.php?s=e403";header($url);}

/* 
 * The MIT License
 *
 * Copyright 2018 Arne Hude.
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 * 
 * 
 * Bootstraptoggle from http://www.bootstraptoggle.com/ (MIT)
 */

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
            . " `owner`,"
            . " `description`,"
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
        <th scope="row"><input type="hidden" id="PrID" name="PrID-'.$row['PrID'].'" value="'.$row['PrID'].'">#00'.$row['PrID'].'</th>
        <td><input class="form-control input-sm" type="text" name="owner-'.$row['PrID'].'" placeholder="'.$row['owner'].'" value="'.$row['owner'].'"/></td>
        <td><textarea class="form-control" rows="3" name="description-'.$row['PrID'].'" placeholder="'.$row['description'].'" value="'.$row['description'].'">'.$row['description'].'</textarea></td>
        <td><button type="submit" name="statusbtn-'.$row['PrID'].'" value="none" class="btn btn-'.convertstatus($row['status'])['type'].'">'.convertstatus($row['status'])['text'].'</button>'.$output.'</td> 
        <td><textarea class="form-control" rows="3" name="nozzle-'.$row['PrID'].'">'.$row['nozzle'].'</textarea></td>
        <td><textarea class="form-control" rows="3" name="filament-'.$row['PrID'].'">'.$row['filament'].'</textarea></td>
        <td><textarea class="form-control" rows="3" name="defects-'.$row['PrID'].'">'.$row['defects'].'</textarea></td>
        <td style="background-color: rgba(0,255,0,0.1)"><button type="submit" name="submit-'.$row['PrID'].'" value="edit" class="btn btn-success">Edit</button></td>
        <td style="background-color: rgba(255,0,0,0.1)"><button type="submit" name="submit-'.$row['PrID'].'" value="delete" class="btn btn-danger">Delete</button></br></br>'
         . '<div class="form-check"><input class="form-check-input" type="checkbox" value="delete" name="confirm-'.$row['PrID'].'" id="autoSizingCheck2 id-'.$row['PrID'].'">
            <label class="form-check-label" for="autoSizingCheck2 id-'.$row['PrID'].'">
          Sure?
        </label></div></td>
        </tr></form>';
    }   
    return $n+2;
}
?>
<h3>Printer</h3>
<table id="tablePreview" class="table">
  <thead>
    <tr>
      <th>PrinterID</th>
      <th>Owner</th>
      <th>Descripton</th>
      <th>Status</th>
      <th>Nozzle</th>
      <th>Filament</th>
      <th>Defects</th>
      <th>&nbsp;</th>
      <th>&nbsp;</th>
    </tr>
  </thead>
  <tbody>
    <?php get_printers($mysqli);?>
  </tbody>
</table>