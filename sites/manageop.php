<?php
if (login_check($mysqli) != true){   $url = "Location: /index.php?s=e403";header($url);} 
if (chk_rights($mysqli,OP_OP) != true){$url = "Location: /index.php?s=e403";header($url);}

/* 
 * Bootstraptoggle from http://www.bootstraptoggle.com/ (MIT)
 */

function get_ranks($mysqli){
    $op = ADMIN;
    $query ="SELECT "
            . "`RID`,"
            . " `name`,"
            . " `rank`,"
            . " `description`,"
            . " `pricepergramm`"
            . " FROM `ranks` WHERE `rank` < ". $op." ORDER BY `ranks`.`rank` ASC";
    if ($stmt = $mysqli->query($query))
    {   
        $n =0;
        while ($prices[$n] = $stmt->fetch_assoc()) {
            $n++;
        }
        $stmt->free();
    }
    foreach ($prices as $row){
        if($row!=NULL)
        echo '<form action="/php/edit_group.php?id='.$row['RID'].'" method="post" class="form-horizontal"><tr>
        <th scope="row"><input type="hidden" id="RID" name="RID-'.$row['RID'].'" value="'.$row['RID'].'">'.$row['RID'].'</th>
        <td><input class="form-control input-sm" type="text" name="name-'.$row['RID'].'" placeholder="'.$row['name'].'" value="'.$row['name'].'"/></td>
        <td><input class="form-control input-sm" type="number" name="rank-'.$row['RID'].'" placeholder="'.$row['rank'].'" value="'.$row['rank'].'"/></td>
        <td><input class="form-control input-sm" type="number" name="ppg-'.$row['RID'].'" placeholder="'.$row['pricepergramm'].'" value="'.$row['pricepergramm'].'"/>ct</td>
        <td><textarea class="form-control" rows="3" name="description-'.$row['RID'].'">'.$row['description'].'</textarea></td>
        <td style="background-color: rgba(0,255,0,0.1)"><button type="submit" name="submit-'.$row['RID'].'" value="edit" class="btn btn-success">Edit</button></td>
        <td style="background-color: rgba(255,0,0,0.1)"><button type="submit" name="submit-'.$row['RID'].'" value="delete" class="btn btn-danger">Delete</button></br></br>'
         . '<div class="form-check"><input class="form-check-input" type="checkbox" value="delete" name="confirm-'.$row['RID'].'" id="autoSizingCheck2 id-'.$row['RID'].'">
            <label class="form-check-label" for="autoSizingCheck2 id-'.$row['RID'].'">
          Sure?
        </label></div></td>
        </tr></form>
        
        ';
    }   
    return $n+2;
}

function add_group($next_rank){
    if(true):?>
        <form action="/php/new_group.php" method="post" class="form-horizontal">
          <tr style='background-color: rgba(0,255,0,0.1)'>
                <th scope="row"><?php echo $next_rank ?></th>
                <td><input class="form-control input-sm" type="text" name="groupname" placeholder="groupname"/></td>
                <td><input class="form-control input-sm" type="number" name="rank" placeholder="0-100"/></td>
                <td><input class="form-control input-sm" type="number" name="ppg" placeholder="9"/> ct</td>
                <td><input class="form-control input-sm" type="text" name="description" placeholder="That's about this group?"/></td>
                <td><button type="submit" class="btn btn-success">Add</button></td>
            </tr>
        </form>
    <?php endif;
}
?>
<h3>Groups</h3>
<table id="tablePreview" class="table">
  <thead>
    <tr>
      <th>RankID</th>
      <th>Name</th>
      <th>Rank</th>
      <th>Price per Gramm</th>
      <th>Description</th>
      <th>&nbsp;</th>
      <th>&nbsp;</th>
    </tr>
  </thead>
  <tbody>
    <?php $next_rank = get_ranks($mysqli);add_group($next_rank) ?>
  </tbody>
</table>
