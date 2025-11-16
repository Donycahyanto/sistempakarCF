 <title>Login Gagal ! - Chirexs 1.0</title>
<?php
session_start();
include "config/koneksi.php";

$user = mysqli_real_escape_string($conn, $_POST['username']);
$pass = $_POST['password']; // Jangan di-md5!

// Cari user berdasarkan username saja
$login = mysqli_query($conn, "SELECT * FROM admin WHERE username='$user'");
$ketemu = mysqli_num_rows($login);

if ($ketemu > 0) {
    $r = mysqli_fetch_array($login);
    
    // ✅ BENAR - Gunakan password_verify()
    if (password_verify($pass, $r['password'])) {
        $_SESSION['username'] = $r['username'];
        $_SESSION['password'] = $r['password'];
        $_SESSION['nama_lengkap'] = $r['nama_lengkap'];
        header("location: index.php");
        exit;
    } else {
        tampilkanErrorLogin();
    }
} else {
    tampilkanErrorLogin();
}

function tampilkanErrorLogin() {
    echo " <link href='css/font-awesome-4.2.0/font-awesome-4.2.0/css/font-awesome.min.css' rel='stylesheet'>
        <link rel='stylesheet' href='animasi/login/ayam.css'>
        <link rel='stylesheet' href='aset/cinta.css'>
        <link href='css/main.css' rel='stylesheet' type='text/css' media='all'/>
        <link rel='stylesheet' href='aset/bootstrap.css'>
        <div class='errorpage'> <center><div class='danger'><i class='fa fa-exclamation-triangle'></i></div><br><h1>LOGIN GAGAL!</h1>
        Username dan Password anda salah.<br><br><input name='submit' id='submitku' type=submit style='padding: 6px 12px;' value='ULANGI LAGI' onclick=location.href='formlogin'></a><br></center></div>
<div class='chick-wrapper-landing show'>
  <div class='wing-back'></div>
  <div class='body'>
    <div class='eye-left'></div>
    <div class='eye-right'></div>
  </div>
  <div class='wing-front'></div>
</div>
<div class='chick-wrapper-run run'><img class='egg-lay' src='animasi/login/lay_egg.png'/>
  <div class='legs'>
    <div class='leg-l'></div>
    <div class='leg-r'></div>
  </div>
  <div class='wing-back'> </div>
  <div class='sweat-1'></div>
  <div class='sweat-2'></div>
  <div class='sweat-3'></div>
  <div class='body'>
    <div class='eye-liner'>
      <div class='eye'></div>
    </div>
    <div class='eye-lid'></div>
    <div class='cheek'></div>
  </div>
  <div class='sweat-last'></div>
  <div class='wing-front'></div>
</div>
    <script  src='animasi/login/index.js'></script>";
}
?>