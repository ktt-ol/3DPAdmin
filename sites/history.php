<?php

if (login_check($mysqli) != true)
    {   $url = "Location: /index.php?s=e403";
        header($url);
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
            . " `description`"
            . " FROM `history` ORDER BY `history`.`printedat` DESC";
    if ($stmt = $mysqli->query($query))
    {   
        $n =0;
        while ($history[$n] = $stmt->fetch_assoc()) {
            $n++;
        }
        $stmt->free();
    }
    foreach ($history as $row){
      if($row!=NULL)
      echo '<tr>
      <th scope="row">'.$row['PID'].'</th>
      <td>'.$row['username'].'</td>
      <td>'.$row['operator'].'</td>
      <td>'.$row['weight'].' g</td>
      <td>'.$row['pricecat'].'</td>
      <td>'.$row['price'].' &euro;</td>
      <td>'.$row['filament'].'</td>
      <td>'.$row['printer'].'</td>
      <td>'.$row['printedat'].'</td>
      <td>'.$row['description'].'</td>
    </tr>';
    }
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
      <th>Gewicht</th>
      <th>Kategorie</th>
      <th>Spendenvorschlag</th>
      <th>Filament</th>
      <th>Drucker</th>
      <th>Zeit</th>
      <th>Beschreibung</th>
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