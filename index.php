<?php require 'php/sessions.php';?>
<!doctype html>
<?php 
    include_once 'php/functions.php';    
    $site = $_GET['s'];  
    if($site == NULL || $site == 'dashboard')
        $site = 'dashboard';
?>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<meta name="description" content="">
<meta name="author" content="">
<title>3D-Drucker Administration</title>
<link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
<link href="css/bootstrap-toggle.min.css" rel="stylesheet" type="text/css"/>
<link href="css/dashboard.css" rel="stylesheet">
</head>
<body>
<?php include_once 'tmp/header.php'; ?>
<div class="container-fluid">
  <div class="row">
    <nav class="col-md-2 d-none d-md-block bg-light sidebar">
      <div class="sidebar-sticky">
        <?php include_once 'tmp/sidebar.php'; ?>
      </div>
    </nav>
    <main role="main" class="col-md-9 ml-sm-auto col-lg-10 pt-3 px-4">
    <?php include chksite($site);?>
    </main>
  </div>
</div>
<script src="jquery/jquery-3.3.1.min.js"></script>
<script src="bootstrap/js/bootstrap.min.js"></script>
<script src="js/bootstrap2-toggle.min.js" type="text/javascript"></script>
</body>
</html>
