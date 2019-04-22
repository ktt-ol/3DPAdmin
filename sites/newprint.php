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
?>
<legend>3D-Druck eintragen</legend>
<div>
<form action="<?php echo '/php/';?>send_newprint.php" method="post" class="form-horizontal">
<fieldset>

<div class="form-group">
    <label class="col-md-4 control-label" for="costomer">Eigent&uuml;mer</label>  
  <div class="col-md-4">
  <input id="customer" name="customer" type="text" placeholder="ID_USERNAME" <?php if(isset($_GET)){ echo 'value="'.$customer.'"';} ?> class="form-control input-md" required="">
  </div>
</div>

<div class="form-group">
  <label class="col-md-4 control-label" for="operator">Operator</label>
  <div class="col-md-4">
    <select id="operator" name="operator" class="form-control">
    <?php get_ops($mysqli,$operator) ?>
    </select>
  </div>
</div>

<div class="form-group">
  <label class="col-md-4 control-label" for="pricecat">Kategorie</label>
  <div class="col-md-4">
  <?php get_category($mysqli,$pricecat);?>
  </div>
</div>

<div class="form-group">
  <label class="col-md-4 control-label" for="weight">Gewicht in Gramm</label>  
  <div class="col-md-4">
  <input id="weight" name="weight" type="text" placeholder="1000" <?php if(isset($_GET)){ echo 'value="'.$weight.'"';} ?> class="form-control input-md" required="">
  </div>
</div>

<div class="form-group">
  <label class="col-md-4 control-label" for="printer">Drucker</label>
  <div class="col-md-4">
    <select id="printer" name="printer" class="form-control">
    <?php get_printer($mysqli, $printer) ?>
    </select>
  </div>
</div>

<div class="form-group">
  <label class="col-md-4 control-label" for="filament">Filament</label>
  <div class="col-md-4">
    <select id="filament" name="filament" class="form-control">
    <?php get_filament($mysqli, $filament)?>
    </select>
  </div>
</div>

<div class="form-group">
  <label class="col-md-4 control-label" for="description">Beschreibung</label>
  <div class="col-md-4">                     
    <textarea class="form-control" id="description" name="description" required=""><?php if(isset($_GET)){ echo $descriptopn;} ?> </textarea>
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