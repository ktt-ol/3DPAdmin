<ul class="nav flex-column">
<li class="nav-item">
<a class="nav-link active" href="/index.php">
Dashboard 
</a>

<?php if(login_check($mysqli) == true) :?>
  
  <li class="nav-item">
    <a class="nav-link" href="/index.php?s=prices">
      Selbstkosten-Infos
    </a>
  </li>
<?php endif; if(chk_rights($mysqli, MEMBER) == true):?>  
  <li class="nav-item">
    <a class="nav-link" href="/index.php?s=changepassword">
        Passwort &auml;ndern
    </a>
  </li>

<?php endif; if(chk_rights($mysqli, OP) == true):?>
  <hr />
  <li class="nav-item">
    <a class="nav-link" href="/index.php?s=history">
      Historie
    </a>
  </li>
  <li class="nav-item">   
      <a class="nav-link" href="/index.php?s=newprint&tag=extern">
      Neuen 3D-Druck
    </a>
  </li>
  <li class="nav-item">   
      <a class="nav-link" href="/index.php?s=newprint&tag=self">
      Eigendruck
    </a>
  </li>
  <li class="nav-item">   
      <a class="nav-link" href="/index.php?s=newprint&tag=credit">
      Guthaben 3D-Druck
    </a>
  </li>
  <li class="nav-item">   
      <a class="nav-link" href="/index.php?s=printer">
      Drucker verwalten
    </a>
  </li>
<?php endif; if(chk_rights($mysqli, OP_OP) == true):?>
  <hr/>
  <li class="nav-item">   
      <a class="nav-link" href="/index.php?s=addprinter">
      Drucker hinzufügen
    </a>
  </li>
  <li class="nav-item">   
      <a class="nav-link" href="/index.php?s=managefilament">
      Filament-Verwaltung
    </a>
  </li>
  <li class="nav-item">   
      <a class="nav-link" href="/index.php?s=credits">
      Guthaben-Verwaltung
    </a>
  </li>
  <li class="nav-item">
    <a class="nav-link" href="/index.php?s=newoperator">
      Neuen Operator erstellen
    </a>
  </li>
  <li class="nav-item">
    <a class="nav-link" href="/index.php?s=manageuser">
      User verwalten
    </a>
  </li>
  <li class="nav-item">
    <a class="nav-link" href="/index.php?s=manageop">
      Gruppen verwalten
    </a>
  </li>

<?php endif; if(chk_rights($mysqli, ADMIN) == true):?>
  <hr />
  <li class="nav-item">   
      <a class="nav-link" href="/index.php?s=all_user_data">
      [ADMIN] Benutzerdaten
    </a>
  </li>
  <li class="nav-item">   
      <a class="nav-link" href="/index.php?s=groupcredits">
      [ADMIN] Druckkonten
    </a>
  </li>
<?php endif;?>
  <hr />

</ul>
<ul class="nav flex-column justify-content-end">
    <li class="nav-item">
        <a class="nav-link alert-dark" href="">
            Software-Version: <br/><?php echo $version?><br/>
            GIT-Version: <br/><?php echo $gitversion?><br/>
            Buildtime: <br/> <?php echo $buildtime ?>
        </a>  
    </li>
</ul>