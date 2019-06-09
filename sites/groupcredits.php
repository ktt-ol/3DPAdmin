<?php

include 'config.php';
include 'functions.php';
include 'sessions.php';
if (login_check($mysqli) != true){   $url = "Location: /index.php?s=e403";header($url);} 
if (chk_rights($mysqli, ADMIN) != true){$url = "Location: /index.php?s=e403";header($url);}


function get_print_value($mysqli,$groupid){
    $query ="SELECT COUNT(`weight`),SUM(`weight`),SUM(`price`),`is_creditprint` FROM `history` WHERE `history`.`pricecat` = '$groupid'";

    if ($stmt = $mysqli->query($query))
    {   
        $return = mysqli_fetch_assoc($stmt);
        return $return;
    }    
} 
function select_ranks($mysqli){
    $query ="SELECT `RID`,`name`,`pricepergramm` FROM `ranks` WHERE 1 ORDER BY `ranks`.`rank` ASC";
    if ($stmt = $mysqli->query($query))
    {   
        $n =0;
        while ($result[$n] = $stmt->fetch_assoc()) {
            $n++;
        }
        $stmt->free();
    } 
    return $result;
} 
function get_group_infos($mysqli) {
    $ranks = select_ranks($mysqli);
    $ranks_rid = $ranks;
    foreach ($ranks_rid as $row){
        $rank_prints[$row['RID']] = get_print_value($mysqli,$row['RID']);
    }
    sort($ranks);
    foreach ($ranks as $row){
        if($row!= NULL){
            echo '<tr>
            <th scope="row">'.$row['RID'].'</th>
                <td>'.$row['name'].'</td>
                <td>'.$rank_prints[$row['RID']]['COUNT(`weight`)'].'</td>
                <td>'.$rank_prints[$row['RID']]['SUM(`weight`)'].' g</td>
                ';
            if($row['pricepergramm']< 0.01){
                echo '<td>Entspricht: '.number_format ($rank_prints[$row['RID']]['SUM(`weight`)']*0.09,2).' &euro;</td>';
            }else{
                echo '<td>'.number_format ($rank_prints[$row['RID']]['SUM(`price`)'],2).' &euro;</td>';
            }
            echo '</tr></form>';
        }        
    }    
}
    
?>

<table id="tablePreview" class="table">
  <thead>
    <tr>
      <th>GID</th>
      <th>Name</th>
      <th>No. of Prints</th>
      <th>Printed Material</th>
      <th>Price of Printing</th>
    </tr>
  </thead>
  <tbody>
    <?php get_group_infos($mysqli);?>
  </tbody>
</table>
