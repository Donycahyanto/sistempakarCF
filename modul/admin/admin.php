<title>Admin - Chirexs 1.0</title>
<?php

if (!(isset($_SESSION['username']) && isset($_SESSION['password']))) {
    header('location:index.php');
    exit();
} else {
    ?>
<script Language="JavaScript">
function Blank_TextField_Validator()
{
  var form = document.forms['text_form'];
  if (!form) return true;
  if (form.username && form.username.value.trim() == "")
  {
     alert("Username tidak boleh kosong !");
     form.username.focus();
     return false;
  }
  // Baris validasi password ini akan diabaikan pada form Edit karena field password dihapus.
  if (form.password && form.password.value.trim() == "")
  {
     alert("Password tidak boleh kosong !");
     form.password.focus();
     return false;
  }
  return true;
}
</script>
<?php
include "config/fungsi_alert.php";
$act = $_GET['act'] ?? '';
$aksi="modul/admin/aksi_admin.php";

switch ($act) {
	// Tampil Admin
  default:
    $tampil=mysqli_query($conn,"SELECT * FROM admin ORDER BY username");
    $baris=mysqli_num_rows($tampil);
    
    // Form filter dan tombol Tambah
	echo "<form method=POST action='?module=admin' name=text_form onsubmit='return false;'>
          <br><br><table class='table table-bordered'>
          <tr><td><input class='btn bg-olive margin' type=button name=tambah value='Tambah Admin' onclick=\"window.location.href='?module=admin&act=tambahadmin';\">".
          // Input pencarian diberi ID
          "<input type=text name='keyword' id='keyword_search' style='margin-left: 10px;' placeholder='Ketik dan tekan cari...' class='form-control' value='' />".
          "</td> </tr>
          </table></form>";
	
    
	if($baris>0){
    // Tabel Daftar Admin
	echo" <table class='table table-bordered' style='overflow-x=auto' cellpadding='0' cellspacing='0' id='adminTable'>
          <thead>
            <tr>
              <th width='5%' style='text-align: center;'>No</th> 
              <th width='20%'>Username</th>
              <th>Nama Lengkap</th>
              <th width='21%' style='text-align: center;'>Aksi</th>
            </tr>
          </thead>
		  <tbody>
		  "; 
	$hasil = mysqli_query($conn,"SELECT * FROM admin ORDER BY username");
    $no = 1; // INISIALISASI NOMOR URUT
    while ($r=mysqli_fetch_array($hasil)){
        $data_filter = htmlspecialchars($r['username'] . " " . $r['nama_lengkap'], ENT_QUOTES);
        
        echo "<tr data-nama='".$data_filter."'>
             <td align=center>$no</td>
             <td>".htmlspecialchars($r['username'], ENT_QUOTES)."</td>
             <td>".htmlspecialchars($r['nama_lengkap'], ENT_QUOTES)."</td>
             <td align=center>
               <a class='btn btn-success margin' href='?module=admin&act=editadmin&id=".urlencode($r['username'])."'><i class='fa fa-pencil-square-o' aria-hidden='true'></i> Ubah </a> &nbsp;
               <a class='btn btn-danger margin' href=\"JavaScript: confirmIt('Anda yakin akan menghapusnya ?','$aksi?module=admin&act=hapus&id=".urlencode($r['username'])."','','','','u','n','Self','Self')\" onMouseOver=\"self.status=''; return true\" onMouseOut=\"self.status=''; return true\">
               <i class='fa fa-trash-o' aria-hidden='true'></i> Hapus</a>
             </td></tr>";
        $no++;
    }
    echo "</tbody></table>";
	
	} else {
	    echo "<br><b>Data Kosong !</b>";
	}

    // Skrip Filter Real-Time
    ?>
    <style>
    .highlight {
        background-color: #ffff99 !important;
    }
    </style>

    <script>
    $(function () {
        function filterTableAdmin() {
            var $rows = $('#adminTable tbody tr');
            var filterValue = $('#keyword_search').val().toLowerCase();
            
            $rows.each(function() {
                var dataText = $(this).data('nama').toLowerCase();
                
                if (dataText.indexOf(filterValue) === 0) {
                    $(this).show();
                    $(this).removeClass('highlight');
                    if (filterValue !== '') {
                        $(this).addClass('highlight');
                    }
                } else {
                    $(this).hide();
                }
            });
        }

        $('#keyword_search').on('keyup', function() {
            filterTableAdmin();
        });
    });
    </script>
    <?php
    break;

  case "tambahadmin":
    echo "<form name=text_form method=POST action='$aksi?module=admin&act=input' onsubmit='return Blank_TextField_Validator()'>
          <br><br><table class='table table-bordered'>
		  <tr><td>Username</td> <td>  <input autocomplete='off' type=text class='form-control' name='username' size=30></td></tr>
		  <tr><td>Password</td> <td>  <input autocomplete='off' type=password class='form-control' name='password' size=30></td></tr>
		  <tr><td>Nama Lengkap</td> <td>  <input autocomplete='off' type=text class='form-control' name='nama_lengkap' size=30></td></tr>
		  <tr><td></td><td><input class='btn btn-success' type=submit name=submit value='Simpan' >
		  <input class='btn btn-danger' type=button name=batal value='Batal' onclick=\"window.location.href='?module=admin';\"></td></tr>
          </table></form>";
     break;
    
  case "editadmin":
    if (isset($_GET['id'])) {
        $id_admin = mysqli_real_escape_string($conn, $_GET['id']);
        $edit = mysqli_query($conn,"SELECT * FROM admin WHERE username='{$id_admin}'");
        
        if ($r=mysqli_fetch_array($edit)) {
        
            echo "<form name=text_form method=POST action='$aksi?module=admin&act=update' onsubmit='return Blank_TextField_Validator()'>
            <input type=hidden name=id value='".htmlspecialchars($r['username'], ENT_QUOTES)."'>
            <br><br><table class='table table-bordered'>
            <tr><td>Username</td> <td>  <input autocomplete='off' type=text class='form-control' name='username' value=\"".htmlspecialchars($r['username'], ENT_QUOTES)."\" size=30></td></tr>
            <tr><td>Nama Lengkap</td> <td>  <input autocomplete='off' type=text class='form-control' name='nama_lengkap' value=\"".htmlspecialchars($r['nama_lengkap'], ENT_QUOTES)."\" size=30></td></tr>
            <tr><td></td><td>
            <input class='btn btn-success' type=submit name=submit value='Simpan' >
            <input class='btn btn-danger' type=button name=batal value='Batal' onclick=\"window.location.href='?module=admin';\"></td></tr>
            </table></form>";
            
        } else {
            echo "<div class='alert alert-danger'>Admin dengan username '".htmlspecialchars($id_admin, ENT_QUOTES)."' tidak ditemukan.</div>";
        }
    } else {
        echo "<div class='alert alert-danger'>Parameter ID tidak disediakan.</div>";
    }
    break;  
}
?>
<?php } ?>