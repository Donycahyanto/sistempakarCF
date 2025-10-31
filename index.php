<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

include "config/koneksi.php";
include "config/fungsi_alert.php";

$module = isset($_GET['module']) ? $_GET['module'] : 'beranda';
?>
<!DOCTYPE html>
<html><head>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <base href="http://localhost/sistempakarCF/">
	<link rel="icon" href="gambar/admin/favicon.png">
    <link href="css/font-awesome-4.2.0/css/font-awesome.min.css" rel="stylesheet">
    <link href="css/owl-carousel/owl.carousel.css" rel="stylesheet"  media="all">
    <link href="css/owl-carousel/owl.theme.css" rel="stylesheet"  media="all">
    <link href="css/magnific-popup.css" type="text/css" rel="stylesheet" media="all" />
    <link href="css/font.css" rel="stylesheet" type="text/css"  media="all">
    <link href="css/fontello.css" rel="stylesheet" type="text/css"  media="all">
    <link href="css/main.css" rel="stylesheet" type="text/css" media="all"/>
    <link rel=stylesheet href="css/paging.css" type="text/css" media=screen>
    <!-- Bootstrap 3.3.5 -->
    <link rel="stylesheet" href="aset/bootstrap.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="aset/AdminLTE.css">
	<link rel="stylesheet" href="aset/cinta.css">
    <link rel="stylesheet" href="aset/Ionicons/css/ionicons.min.css">
    <link rel="stylesheet" href="aset/skins/_all-skins.min.css">
    <link rel="stylesheet" href="aset/custom.css">
    <link rel="stylesheet" href="aset/icheck/green.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
    <!-- jQuery 2.1.4 -->
    <script src="aset/jQuery-2.js"></script>
    <!-- Bootstrap 3.3.5 -->
    <script src="aset/bootstrap.js"></script>
    <script src="aset/icheck/icheck.js"></script>
    <script src="aset/ckeditor/ckeditor.js"></script>
    <script src="aset/Flot/jquery.flot.js"></script>
    <!-- FLOT RESIZE PLUGIN - allows the chart to redraw when the window is resized -->
    <script src="aset/Flot/jquery.flot.resize.js"></script>
    <!-- FLOT PIE PLUGIN - also used to draw donut charts -->
    <script src="aset/Flot/jquery.flot.pie.js"></script>
    <!-- FLOT CATEGORIES PLUGIN - Used to draw bar charts -->
    <script src="aset/Flot/jquery.flot.categories.js"></script> 
    <!-- AdminLTE App -->
    <script src="aset/app.js"></script>
  </head>
  <body id="pakarayam" class="hold-transition skin-purple-light sidebar-mini">
    <div class="wrapper">
      <!-- Main Header -->
      <header class="main-header">
        <!-- Logo -->
        <a href="./" class="logo">
          <!-- mini logo for sidebar mini 50x50 pixels -->
          <span class="logo-mini"><b><i class="fa fa-contao" aria-hidden="true"></i>SP</b></span>
          <!-- logo for regular state and mobile devices -->
          <span class="logo-lg"><b><i class="fa fa-contao" aria-hidden="true"></i>Spakar CF</b></span>
        </a>
        <!-- Header Navbar -->
        <nav class="navbar navbar-static-top" role="navigation">
          <!-- Sidebar toggle button-->
          <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
            <span class="sr-only">Toggle navigation</span>
          </a>

          <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
                <?php
                if (isset($_SESSION['username']) && isset($_SESSION['password'])) {
                    ?>
                  <li class="dropdown user user-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                      <img src="gambar/admin/admin.png" class="user-image" alt="User Image">
                      <span class="hidden-xs"><?php echo htmlspecialchars(ucfirst($_SESSION['username'] ?? ''), ENT_QUOTES); ?></span>
                    </a>
                    <ul class="dropdown-menu">
                      <!-- User image -->
                      <li class="user-header">
                        <img src="gambar/admin/admin.png" class="img-circle" alt="User Image">
                        <p>
                         Login sebagai <?php echo htmlspecialchars(ucfirst($_SESSION['username'] ?? ''), ENT_QUOTES); ?>
                          <small>Pakar dari Chirexs 1.0</small>
                        </p>
                      </li>
                      <!-- Menu Footer-->
                      <li class="user-footer"> 
                        <div class="pull-left">
                          <a href="?module=tentang" class="btn btn-default btn-flat<?php if ($module == "tentang") echo ' active'; ?>"><i class="fa fa-info-circle"></i> <span>Tentang</span></a>
                        </div>
                        <div class="pull-right">
                          <a class="btn btn-default btn-flat" href="JavaScript: confirmIt('Anda yakin akan logout dari aplikasi ?','logout.php','','','','u','n','Self','Self')"><i class="fa fa-sign-out"></i> <span>LogOut</span></a>
                        </div>
                      </li>
                    </ul>
                  </li>
              <?php } else { ?>
                  <li class="dropdown messages-menu">
                    <a <?php if ($module == "formlogin") echo 'class="active"'; ?> href="formlogin"><i class="fa fa-sign-in"></i> <span>Login</span></a>
                  </li>
              <?php } ?>
            </ul>
          </div>
        </nav>
      </header>
      <!-- Left side column. contains the logo and sidebar -->
      <aside class="main-sidebar">
        <!-- sidebar: style can be found in sidebar.less -->
        <section class="sidebar">
          <!-- Sidebar Menu -->
          <ul class="sidebar-menu">
            <li class="header">Menu</li>
            <!-- Optionally, you can add icons to the links -->
            <?php include "menu.php"; ?>
        </section>
        <!-- /.sidebar -->
      </aside>
      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper" style="min-height: 310px;">
        <!-- Content Header (Page header) -->
        <section class="content-header">
        </section>
        <!-- Main content -->
        <section class="content">
          <div class="box">
            <div class="box-body">
                <?php include "content.php"; ?>		
            </div>
          </div>
        </section><!-- /.content -->
      </div><!-- /.content-wrapper -->
      <!-- Main Footer -->
      <footer class="main-footer">
        <!-- Kontak dihapus -->
        <strong><div class="cinta">Copyright © 2025 - Made with <i class="fa fa-heart pulse"></i> by <a href="http://januriawan.github.io" target="_blank">Dony Cahyanto</a></div></strong>
      </footer>
      <!-- Add the sidebar's background. This div must be placed
           immediately after the control sidebar -->
      <div class="control-sidebar-bg" style="position: fixed; height: auto;"></div>
    </div><!-- ./wrapper -->
  </body></html>
<?php
if (ob_get_level()) {
    ob_end_flush();
}
?>