<?php
  session_start();
  error_reporting(0);
  include('conexion.php');
  include('configuracion.php');
 
  if(isset($_SESSION['IDUSUARIO'])==''){
    header('Location: seguridad/acceso.php');
  }

  $URI = $_SERVER["REQUEST_URI"];
  $name = '';
  for ($i=strlen($URI); $i > 0; $i--) { 
    if($URI[$i]=='/')break;
    $name .= $URI[$i];
    
  }

  $name = strrev($name);
  $nameT = '';

  for ($i = 0; $i < strlen($name); $i++) { 
    if($name[$i]=='.')break;
    $nameT .= $name[$i];
  }

  $name = $nameT;
  
  
?>


<!DOCTYPE html>
<html>
  <input type="hidden" id="ServidorLocal" value="<?php echo $ServidorLocal; ?>"> 
  <input type="hidden" id="Gateway" value="<?php echo $Gateway; ?>"> 
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>SAFAC</title>
    <link rel="shortcut icon" href="safac.ico"> 
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.5 -->
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="dist/css/AdminLTE.min.css">
    <!-- AdminLTE Skins. Choose a skin from the css/skins
         folder instead of downloading all of them to reduce the load. -->
    <link rel="stylesheet" href="dist/css/skins/_all-skins.min.css">
    <!-- DataTables -->
    <link rel="stylesheet" href="plugins/datatables/dataTables.bootstrap.css">
    <!-- Select2 -->
    <link rel="stylesheet" href="plugins/select2/select2.min.css">
    <!-- Toggle -->
    <link href="bootstrap/css/bootstrap-toggle.min.css" rel="stylesheet">
     <!-- iCheck for checkboxes and radio inputs -->
    <link rel="stylesheet" href="plugins/iCheck/all.css">
    <!-- Morris charts -->
    <link rel="stylesheet" href="plugins/morris/morris.css">

    <link rel="stylesheet" href="dist/css/estilo.css">
    <link rel="stylesheet" id="css-main" href="assets/css/oneui.css">

    <link rel="stylesheet" type="text/css" href="toastr/toastr.min.css">

    <!--<script type="text/javascript" src="stacktable/stacktable.js"></script>-->

    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body class="hold-transition skin-blue fixed sidebar-mini">
    <input type="hidden" id="codUsuario" value="<?php echo $_SESSION['IDUSUARIO'] ?>">
    <!-- Site wrapper -->
    <div class="wrapper">

      <header class="main-header">
        <!-- Logo -->
        <a href="index.php" class="logo">
          <!-- mini logo for sidebar mini 50x50 pixels -->
          <span class="logo-mini"><b>S</b>AC</span>
          <!-- logo for regular state and mobile devices -->
          <span class="logo-lg"><b>SAF</b>AC</span>
        </a>
        <!-- Header Navbar: style can be found in header.less -->
        <nav class="navbar navbar-static-top" role="navigation">
          <!-- Sidebar toggle button-->
          <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </a>
          <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
              <li class="dropdown">
                <a href="#"  class="dropdown-toggle" data-toggle="dropdown">
                  <i class="fa fa-user"></i> <?php echo $_SESSION['NOMBRE'] ?> <span class="caret"></span>
                </a>
                  <ul class="dropdown-menu" role="menu">
                    <li><a href="perfil.php"><i class="fa fa-user"></i> Perfil</a></li>
                    <li><a href="seguridad/logout.php"><i class="fa fa-unlock"></i> Cerrar Sesión</a></li> 
                  </ul>
                </li>
            </ul>
          </div>
        </nav>
      </header>

      <!-- =============================================== -->

      <!-- Left side column. contains the sidebar -->
      <aside class="main-sidebar">
        <!-- sidebar: style can be found in sidebar.less -->
        <section class="sidebar">
          <!-- Sidebar user panel -->
          <div class="user-panel">
            <div class="pull-left image">
              <img src="img/icon-user-admin.png" class="img-circle" alt="User Image">
            </div>
            <div class="pull-left info">
              <p><?php echo $_SESSION['NOMBRE'] ?></p>
              <i class="fa fa-building text-info"></i><?php echo $_SESSION['NOMBREEMPRESA'] ?></a>
            </div>
          </div>
          <!-- sidebar menu: : style can be found in sidebar.less -->
          <ul class="sidebar-menu">
            <li class="header"><?php echo $_SESSION['CARGO'] ?></li>
            <?php
              include('menu.php');
              while($reg=pg_fetch_assoc($modulos)){
                
            ?>
            <li class="treeview <?php if(strtolower($reg['ges_nombre'])==$name)echo 'active'?>">
              <a href="<?= strtolower($reg['ges_nombre']);?>.php">
                <i class="<?= $reg['ges_img'];?>"></i> <span><?= $reg['ges_nombre'];?></span>
              </a>
            </li>
            <?php
              }
            ?>
            
          </ul>
        </section>
        <!-- /.sidebar -->
      </aside>