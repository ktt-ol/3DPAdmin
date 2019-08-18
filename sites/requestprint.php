<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 * 
 * <div class="slidecontainer">
  <input type="range" min="1" max="100" value="50" class="slider" id="myRange">
</div>
 * 
 *
 * <div class="form-group row">
    <label for="member" class="col-4 col-form-label">Für wen?</label> 
    <div class="col-8">
      <input id="member" name="member" placeholder="member" type="text" class="form-control" required="required">
    </div>
  </div> 
 * 
 *  
 */


?>
<form>
  <div class="form-group row">
    <label for="member" class="col-4 col-form-label">Für wen?</label> 
    <div class="col-8">
      <input id="member" name="member" placeholder="member" type="text" class="form-control" required="required">
    </div>
  </div> 
    
  <div class="form-group row">
    <label for="quality" class="col-4 col-form-label">Qualität (Layerheight)</label> 
    <div class="col-1"><p>grob (0.35)</p></div>
    <div class="col-6">
      <div class="slidecontainer">
        <input type="range"required="required" min="1" max="100" value="50" class="slider" id="quality">
        <span id="value_quality"></span>
      </div>
    </div>
    <div class="col-1"><p>fein (0.10)</p></div>
  </div>
    
    
  <div class="form-group row">
    <label for="duty" class="col-4 col-form-label">Stabilität (Infill)</label> 
    <div class="col-1"><p>fragil</p></div>
    <div class="col-6">
      <div class="slidecontainer">
        <input type="range" required="required" min="1" max="100" value="50" class="slider" id="duty">
        <span id="value_duty"></span>
      </div>
    </div>
    <div class="col-1"><p>solider Block</p></div>
  </div>  
      
    
  <div class="form-group row">
    <label for="color" class="col-4 col-form-label">Farbe</label> 
    <div class="col-8">
      <select id="select" name="select" class="custom-select">
        <option value="rabbit">Rabbit</option>
        <option value="duck">Duck</option>
        <option value="fish">Fish</option>
      </select>
    </div>
  </div> 
  
  <div class="form-group row">
    <label for="xdim" class="col-2 col-form-label">Länge (ca. in cm)</label> 
    <div class="col-4">
      <input id="xdim" name="xdim" placeholder="xdim" type="text" class="form-control">
    </div>
    <label for="ydim" class="col-2 col-form-label">Breite (ca. in cm)</label> 
    <div class="col-4">
      <input id="ydim" name="ydim" placeholder="ydim" type="text" class="form-control">
    </div>
  </div> 
    
  <div class="form-group row">
      <div class="col-4"><p>STL-Datei</p></div>
    <div class="col-8    custom-file">
        <input type="file" class="custom-file-input" id="customFileLang" lang="de">
        <label class="custom-file-label col-form-label" for="customFileLang">Bitte ausschlie&szlig;lich STL-Dateien hochladen</label>
      </div>
  </div>
    
    <div class="form-group row">
        <div class="col-12 offset-4"><p>- oder -</p></div>
    </div> 
    
  <div class="form-group row">
    <label for="thingiverselink" class="col-4 col-form-label">Thingiverse-Link</label> 
    <div class="col-8">
      <input id="thingiverselink" name="thingiverselink" placeholder="thingiverselink" type="text" class="form-control">
    </div>
  </div> 
    
  <div class="form-group row">
    <div class="offset-4 col-8">
      <button name="submit" type="submit" class="btn btn-primary">Submit</button>
    </div>
  </div>
</form>
