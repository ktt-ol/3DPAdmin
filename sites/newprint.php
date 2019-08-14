    <?php
if (login_check($mysqli) != true){  $url = "Location: /index.php?s=e403";header($url);} 
if (chk_rights($mysqli,OP) != true){$url = "Location: /index.php?s=e403";header($url);} 
    if(isset($_GET['p'])){        
        @$customer = $_GET['c'];
        @$operator = $_GET['o'];
        @$pricecat = $_GET['pc'];
        @$weight = $_GET['w'];
        @$price = $_GET['p'];
        @$filament = $_GET['f'];
        @$printer = $_GET['pr'];
        @$descriptopn = $_GET['d'];
    } 
    if(!isset($_GET['pc'])){
        $pricecat = usergrp($_SESSION['user']);
    }
if(isset($_GET['tag'])){@$tag = $_GET['tag'];}
?>
<legend>3D-Druck eintragen</legend>
<div>
<nav class="nav nav-pills flex-column flex-sm-row">
    <a class="flex-sm-fill text-sm-center nav-link <?php if($tag=="extern"){echo 'active bg-danger';} ?>" href="/index.php?s=newprint&tag=extern">Extern/Member</a>
    <a class="flex-sm-fill text-sm-center nav-link <?php if($tag=="self"){echo 'active bg-danger';} ?>" href="/index.php?s=newprint&tag=self">Eigendruck</a>
    <a class="flex-sm-fill text-sm-center nav-link <?php if($tag=="credit"){echo 'active bg-danger';} ?>" href="/index.php?s=newprint&tag=credit">Guthabendruck</a>
</nav> 
</div>
<br/>
<div>
<form action="<?php echo '/php/';?>send_newprint.php<?php if($tag=="credit" || $tag=="self"){echo '?creditprint=1';} ?>" method="post" class="form-horizontal">
<fieldset>
<div class="form-group">
    <label class="col-md-4 control-label" for="costomer">Eigent&uuml;mer</label>  
  <div class="col-md-4">
  <?php if($tag=="self"){?>
    <input type="text" disabled="" id="customer" name="customer" class="form-control input-md" value="<?php echo $_SESSION['user'];?>"/>
  <?php }else{?>
    <input id="customer" name="customer" type="text" placeholder="<?php echo $_SESSION['user'];?>" <?php if(isset($_GET)){ echo 'value="'.$customer.'"';}?> class="form-control input-md" required="">
  <?php };?>
  </div>
</div>

<div class="form-group">
  <label class="col-md-4 control-label" for="operator">Operator</label>
  <div class="col-md-4">
    <?php if($tag=="self"||$tag=="credit"){?>
      <select id="operator" disabled="" name="operator" class="form-control" required="">
    <?php }else{?>
        <select id="operator" name="operator" class="form-control" required="">
    <?php };?>   
    <?php get_ops($mysqli,$_SESSION['uid']); ?>
    </select>
  </div>
</div>

<div class="form-group">
  <label class="col-md-4 control-label" for="pricecat" required="">Kategorie</label>
  <div class="col-md-4">
  <?php if($tag=="self"||$tag=="credit"){get_category($mysqli,$pricecat,1);}else{get_category($mysqli,$pricecat);}?>  
  </div>
</div>

<div class="form-group">
  <label class="col-md-4 control-label" for="weight" >Gewicht in Gramm</label>  
  <div class="col-md-4">
  <input id="weight" name="weight" type="text" required="" placeholder="1000" <?php if(isset($_GET)){ echo 'value="'.$weight.'"';} ?> class="form-control input-md" required="">
  </div>
</div>

<div class="form-group">
  <label class="col-md-4 control-label" for="printer">Drucker</label>
  <div class="col-md-4">
    <select id="printer" name="printer" class="form-control" required="">
    <?php get_printer($mysqli, $printer) ?>
    </select>
  </div>
</div>

<div class="form-group">
    <label class="col-md-4 control-label" for="filament">Filament<br/>ID | Weight left | Color | Diameter | Owner | Pricemultiplicator</label>
  <div class="col-md-4">
    <select id="filament" name="filament" class="form-control" required="">
    <?php get_filament($mysqli, $filament)?>
    </select>
  </div>
</div>

<div class="form-group">
  <label class="col-md-4 control-label" for="description">Beschreibung</label>
  <div class="col-md-4">                     
    <textarea class="form-control" id="description" name="description"><?php if(isset($_GET)){ echo $descriptopn;} ?> </textarea>
  </div>
</div>

<div class="form-group">
  <label class="col-md-4 control-label" for="pricecalc"></label>
  <div class="col-md-8">
    <?php if(isset($price)){ echo "<button id=\"calc\" name=\"calc\" class=\"btn btn-success\"><b>Spendenvorschlag: ".$price." &euro;</b></button>";}?>
    <?php if(!isset($price)){ echo "<button id=\"calc\" name=\"calc\" class=\"btn btn-success\">Spendenvorschlag berechnen</button>";}?>
    <button id="Eintragen" name="submit" class="btn btn-info">Absenden</button>
  </div>
</div>
</fieldset>
</form>
</div>