<nav class="navbar navbar-dark sticky-top bg-dark flex-md-nowrap p-0">
  <a class="navbar-brand col-sm-3 col-md-2 mr-0" href="/">3D-Druck-Administration</a>
 <ul class="navbar-nav px-3">
      <?php 
        if(login_check($mysqli) == true) {
            echo "<li class=\"nav-item text-nowrap\"><a class=\"nav-link\" href=\"/php/logout.php\">logout ".$_SESSION['user']."</a></li>";
        } else { 
            echo "<li class=\"nav-item text-nowrap\"><a class=\"nav-link\" href=\"/index.php?s=login\">login</a></li>";
        }
    ?>        
    </li>
  </ul>
</nav>