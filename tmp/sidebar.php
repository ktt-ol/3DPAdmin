<ul class="nav flex-column">
<li class="nav-item">
<a class="nav-link active" href="/index.php">
Dashboard 
</a>
</li>
<?php if(login_check($mysqli) == true) :?>
  <li class="nav-item">
    <a class="nav-link" href="/index.php?s=history">
      Historie
    </a>
  </li>
  <li class="nav-item">
    <a class="nav-link" href="/index.php?s=prices">
      Preise
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
      <a class="nav-link" href="/index.php?s=newprint">
      Neuen 3D-Druck
    </a>
  </li>
<?php endif; if(chk_rights($mysqli, OP_OP) == true):?>
  <hr />
  <li class="nav-item">   
      <a class="nav-link" href="/index.php?s=printer">
      Drucker verwalten
    </a>
  </li>
  <li class="nav-item">
    <a class="nav-link" href="/index.php?s=newoperator">
      Neuen Operator erstellen
    </a>
  </li> 
  <li class="nav-item">
    <a class="nav-link" href="/index.php?s=manageop">
      Gruppen verwalten
    </a>
  </li>
  <li class="nav-item">
    <a class="nav-link" href="/index.php?s=manageuser">
      User verwalten
    </a>
  </li>
<?php endif; if(chk_rights($mysqli, ADMIN) == true):?>
  <hr />
  <li class="nav-item">   
      <a class="nav-link" href="/index.php?s=settings">
      Ganz-spezieller-kram
    </a>
  </li>
<?php endif;?>
</ul>