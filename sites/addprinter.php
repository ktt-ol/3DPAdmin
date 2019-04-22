<?php
if (login_check($mysqli) != true){   $url = "Location: /index.php?s=e403";header($url);} 
if (chk_rights($mysqli,OP_OP) != true){$url = "Location: /index.php?s=e403";header($url);}
function get_next_printerid($mysqli){
    $query ="SELECT `PrID` FROM `printer` ORDER BY `printer`.`PrID` ASC LIMIT 1";
    if ($stmt = $mysqli->query($query))
    {   
        $pinter = $stmt->fetch_assoc();
        $next_printer_id = $pinter['PrID']+1;
        $stmt->free();
    }   
    return $next_printer_id;
}
?>
<form action="<?php echo '/php/';?>new_printer.php" method="post" class="form-horizontal" class="form-horizontal">
<fieldset>   
<legend>Add Printer</legend>

    <input type="hidden" id="PID" name="PID" value="<?php echo get_next_printerid($mysqli); ?>"/>
    <div class="form-group">
      <label class="col-md-4 control-label" for="printername">Printername</label>  
      <div class="col-md-4">
        <input id="printername" name="printername" type="text" placeholder="" class="form-control input-md" required="">
      </div>
    </div>

    <div class="form-group">
      <label class="col-md-4 control-label" for="owner">Owner</label>  
      <div class="col-md-4">
        <input id="owner" name="owner" type="text" value="KtT e.V." class="form-control input-md">
      </div>
    </div>

    <div class="form-group">
      <label class="col-md-4 control-label" for="nozzle">Nozzle</label>
      <div class="col-md-4">
        <select id="nozzle" name="nozzle" class="form-control">
          <option value="0.1">0.10 mm</option>
          <option value="0.15">0.15 mm</option>
          <option value="0.20">0.20 mm</option>
          <option value="0.25">0.25 mm</option>
          <option value="0.30">0.30 mm</option>
          <option value="0.35">0.35 mm</option>
          <option value="0.40">0.40 mm</option>
          <option value="0.45">0.45 mm</option>
          <option value="0.50">0.50 mm</option>
          <option value="0.55">0.55 mm</option>
        </select>
      </div>
    </div>
    
    <div class="form-group">
      <label class="col-md-4 control-label" for="filamentdia">Filament-Diameter</label>
      <div class="col-md-4">
        <select id="filamentdia" name="filamentdia" class="form-control">
          <option value="1.75">1.75 mm</option>
          <option value="2.85">2.85 mm</option>
          <option value="3.00">3.00 mm</option>
        </select>
      </div>
    </div>
    
    <div class="form-group">
        <label class="col-md-4 control-label" for="dimx">Bed-Dimensions in X</label>
        <div class="col-md-2">
          <div class="input-group">
            <input id="dimx" name="dimx" class="form-control" placeholder="200" type="text">
            <span class="input-group-addon">mm</span>
          </div>
        </div>
    </div>
    
    <div class="form-group">
        <label class="col-md-4 control-label" for="dimy">Bed-Dimensions in Y</label>
        <div class="col-md-2">
          <div class="input-group">
            <input id="dimy" name="dimy" class="form-control" placeholder="200" type="text">
            <span class="input-group-addon">mm</span>
          </div>
        </div>
    </div>
    
    <div class="form-group">
      <label class="col-md-4 control-label" for="features">Features</label>
      <div class="col-md-4">
        <select id="features" name="features" class="form-control" multiple="multiple">
          <option value="1">Gear-Extruder</option>
          <option value="2">Direct-Drive</option>
          <option value="3">Bowden</option>
          <option value="4">mk8-Extrunder</option>
          <option value="5">e3dv6-Extrunder</option>
          <option value="6">Auto-Bed-Leveling</option>
          <option value="7">Octoprint</option>
        </select>
      </div>
    </div>

    <div class="form-group">
      <label class="col-md-4 control-label" for="selectbasic">Status</label>
      <div class="col-md-4">
        <select id="selectbasic" name="selectbasic" class="form-control">
          <option value="0">undefined</option>
          <option value="1">L&auml;uft</option>
          <option value="2">Kleiner Defekt</option>
          <option value="3">Gro&szliger Defekt</option>
          <option value="4">Out of Order</option>
          <option value="5">Work in progress</option>
        </select>
      </div>
    </div>

    <div class="form-group">
      <label class="col-md-4 control-label" for="descp">Description and Extras</label>
      <div class="col-md-4">                     
        <textarea class="form-control" id="descp" name="descp">Brief description about the printer, results, extras or ticks the printer has</textarea>
      </div>
    </div>

    <div class="form-group">
      <label class="col-md-4 control-label" for="issues">Momentary Issues</label>
      <div class="col-md-4">                     
        <textarea class="form-control" id="issues" name="issues">If the printer has real issues, point it out here</textarea>
      </div>
    </div>
  <div class="col-md-8">
    <button id="Eintragen" name="submit" class="btn btn-info">Absenden</button>
  </div>
</fieldset>  
</form>
