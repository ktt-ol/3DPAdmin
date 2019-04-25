<?php

if (login_check($mysqli) != true)
    {   $url = "Location: /index.php?s=e403";
        header($url);
    }
if (chk_rights($mysqli,OP_OP) != true){$url = "Location: /index.php?s=e403";header($url);}

    
function getcredits($mysqli){
    $query ="SELECT `userid`,`value`,`lastchange` FROM `credit` ORDER BY `userid` ASC";
    if ($stmt = $mysqli->query($query))
    {   
        $n =0;
        
        while ($credits[$n] = $stmt->fetch_assoc()) {
            $uid = $credits[$n]['userid'];
            $uid_q = "SELECT `username` FROM `userbase` WHERE `UID` = $uid LIMIT 1";
            $uid_stmt = $mysqli->query($uid_q);

            $username = mysqli_fetch_assoc($uid_stmt);            

            $credits[$n]['username'] = $username['username'];
                        
            $n++;
        }
        $stmt->free();
    }
    foreach ($credits as $persons_credit){
      if($persons_credit!=NULL)
      echo '<tr>
      <td>'.$persons_credit['username'].'</td>
      <td>'.$persons_credit['value'].' g</td>
      <td>'.$persons_credit['lastchange'].'</td>

    </tr>';
    }
}

?>

<h3>Guthabenstand</h3>
<!--Table-->
<table id="tablePreview" class="table">
<!--Table head-->
  <thead>
    <tr>
      <th>Username</th>
      <th>Guthaben</th>
      <th>Letzte &Auml;nderung</th>
    </tr>
  </thead>
  <!--Table head-->
  <!--Table body-->
  <tbody>
    <?php getcredits($mysqli); ?>
  </tbody>
  <!--Table body-->
</table>
<!--Table-->