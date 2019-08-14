<?php 
if (login_check($mysqli) != true){$url = "Location: /index.php?s=e403";header($url);}
if (chk_rights($mysqli,ADMIN) != true){$url = "Location: /index.php?s=e403";header($url);}
if (!empty($_SESSION['errormsg'])&&$_SESSION['errormsg']!=''){echo $_SESSION['errormsg'];$_SESSION['errormsg'] ='';}

?>
<form action="/php/new_operator.php" method="post" class="form-horizontal">
<fieldset>

<!-- Form Name -->
<legend>Add new Operator</legend>

<!-- Text input-->
<div class="form-group">
  <label class="col-md-4 control-label" for="username">Username</label>  
  <div class="col-md-4">
  <input id="username" name="username" type="text" placeholder="" class="form-control input-md" required="">
  </div>
</div>
<!-- Multiple Checkboxes (inline) -->
<div class="form-group">
  <label class="col-md-4 control-label" for="activ">Activ</label>
  <div class="col-md-4">
    <label class="checkbox-inline" for="activ-0">
      <input type="checkbox" name="activ" id="activ-0" value="1">
      Yes
    </label>
    <label class="checkbox-inline" for="activ-1">
      <input type="checkbox" name="activ" id="activ-1" value="0">
      No
    </label>
  </div>
</div>
<!-- Button -->
<div class="form-group">
  <label class="col-md-4 control-label" for="submit"></label>
  <div class="col-md-4">
    <button id="submit" name="submit" class="btn btn-success">Add Operator (password will be displayed later)</button>
  </div>
</div>

</fieldset>
</form>
<?php
if(@isset($_SESSION['post']['newoppass'],$_GET['success']))
{
    echo "</br></br><h3>Das Passwort lautet: ".$_SESSION['post']['newoppass']."</h3></br></br>"; 
    $_SESSION['post']['newoppass']='';
}elseif(@isset($_GET['err'])){
    echo "</br></br><h3>Fehler: ".$_GET['err']."</h3></br></br>"; 
}
?>