<?php
if (login_check($mysqli) != true){   $url = "Location: /index.php?s=e403";header($url);} 

function get_prices($mysqli){
    $op = OP_OP;
    $query ="SELECT "
            . "`RID`,"
            . " `name`,"
            . " `pricepergramm`"
            . " FROM `ranks` WHERE `rank` < ". $op." ORDER BY `ranks`.`rank` DESC";
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
      echo '<tr>
      <th scope="row">'.$row['RID'].'</th>
      <td>'.$row['name'].'</td>
      <td>'.$row['pricepergramm'].' g</td>
    </tr>';
    }    
}
?>

<h3>Prices</h3>
<!--Table-->
<table id="tablePreview" class="table">
<!--Table head-->
  <thead>
    <tr>
      <th>Rang</th>
      <th>Name</th>
      <th>Spendenvorschlag</th>
    </tr>
  </thead>
  <!--Table head-->
  <!--Table body-->
  <tbody>
    <?php get_prices($mysqli); ?>
  </tbody>
  <!--Table body-->
</table>
<!--Table-->