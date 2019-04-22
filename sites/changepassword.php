<?php 
if (login_check($mysqli) != true){$url = "Location: /index.php?s=e403";header($url);}
if (chk_rights($mysqli,MEMBER) != true){$url = "Location: /index.php?s=e403";header($url);}
if (!empty($_SESSION['errormsg'])&&$_SESSION['errormsg']!=''){echo $_SESSION['errormsg'];$_SESSION['errormsg'] ='';}
?>
<form action="/php/changepassword.php" method="post" class="form-horizontal">
<fieldset>

<!-- Form Name -->
<legend>Change my password</legend>

<!-- Text input-->
<div class="form-group">
  <label class="col-md-4 control-label" for="password_old">Old password</label>  
  <div class="col-md-4">
  <input id="password_old" name="password_old" type="password" placeholder="" class="form-control input-md" required="">
  </div>
</div>
<!-- Text input-->
<div class="form-group">
  <label class="col-md-4 control-label" for="password_new">New Password</label>  
  <div class="col-md-4">
  <input id="password_new" name="password_new" type="password" placeholder="" class="form-control input-md" required="">
  </div>
</div>
<!-- Text input-->
<div class="form-group">
  <label class="col-md-4 control-label" for="password_new_confirm">Confirm new Password</label>  
  <div class="col-md-4">
  <input id="password_new_confirm" name="password_new_confirm" type="password" placeholder="" class="form-control input-md" required="">
  </div>
</div>
<!-- Button -->
<div class="form-group">
  <label class="col-md-4 control-label" for="submit"></label>
  <div class="col-md-4">
    <button id="submit" name="submit" class="btn btn-success">Submit</button>
  </div>
</div>

</fieldset>
</form>
<?php
if(@isset($_SESSION['post']['passwordchanged'],$_GET['success']))
{
    echo "</br></br><h3>Your password has been changed</h3></br></br>"; 
}elseif(@isset($_GET['err'])){
    echo "</br></br><h3>Error: ".$_GET['err']."</h3></br></br>"; 
}
?>