<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

?>

<h3>Angefragte 3D-Drucke</h3>
<!--Table-->
<table id="tablePreview" class="table">
<!--Table head-->
  <thead>
    <tr>
      <th>#</th>
      <th>Voransicht</th>
      <th>Von</th>
      <th>Bezeichnung</th>
      <th>Qualität</th>
      <th>Stabilit&auml;t</th>   
      <th>Filament</th>
      <th>Länge</th>
      <th>Breite</th>
      <th>Höhe</th>
      <th>STL</th>
      <th>Zeit</th>      
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
