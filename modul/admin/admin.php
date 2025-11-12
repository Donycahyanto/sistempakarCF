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
  // hanya periksa password jika field ada (mis. saat tambah admin)
  if (form.password && form.password.value.trim() == "")
  {
     alert("Password tidak boleh kosong !");
     form.password.focus();
     return false;
  }
  return true;
}
// Fungsi Blank_TextField_Validator_Cari() dihapus karena kita pakai filter real-time (onsubmit='return false;')
</script>
<?php
include "config/fungsi_alert.php";
$act = $_GET['act'] ?? '';
$aksi="modul/admin/aksi_admin.php";

switch($act){
	// Tampil admin
  default:
  $offset=$_GET['offset'] ?? 0;
  // Variabel pencarian PHP dihapus karena tidak lagi digunakan

	//jumlah data yang ditampilkan perpage
	$limit = 10;
	if (empty ($offset)) {
		$offset = 0;
	}
  $tampil=mysqli_query($conn,"SELECT * FROM admin ORDER BY username");
  
  // FORM: onsubmit='return false;' dan tombol Cari dihapus
	echo "<br><form method=POST action='?module=admin' name=text_form onsubmit='return false;'>
          <br><table class='table table-bordered'>
		  <tr><td><input class='btn bg-olive margin' type=button name=tambah value='Tambah Admin' onclick=\"window.location.href='?module=admin&act=tambahadmin';\">
  <input type=text name='keyword' id='keyword_search' style='margin-left: 10px;' placeholder='Ketik dan tekan cari...' class='form-control' value='' /> 
  </td></tr></table></form>";

  // CONTAINER UNTUK PESAN SUKSES/GAGAL (Akan diisi oleh JavaScript)
  echo "<div id='search_message_container'></div>";

  $baris=mysqli_num_rows($tampil);
  
  // LOGIKA PENCARIAN PHP LAMA DIHAPUS

	if($baris>0){
	echo" <table class='table table-bordered' style='overflow-x=auto' cellpadding='0' cellspacing='0' id='adminTable'>
          <thead>
            <tr>
              <th width='5%' style='text-align: center;'>No</th>
              <th>Username</th>
              <th>Nama Lengkap</th>
              <th width='21%' style='text-align: center;'>Aksi</th>
            </tr>
          </thead>
		  <tbody>
		  "; 
	// QUERY MEMAKAI LIMIT UNTUK PAGINATION PHP
	$hasil = mysqli_query($conn,"SELECT * FROM admin ORDER BY username limit $offset,$limit");
	$no = 1 + $offset;
	$counter = 1;
    while ($r=mysqli_fetch_array($hasil)){
	if ($counter % 2 == 0) $warna = "dark";
	else $warna = "light";
    // Tambahkan data-filter attribute untuk JavaScript
    $data_filter = htmlspecialchars($r['username'] . " " . $r['nama_lengkap'], ENT_QUOTES);

       echo "<tr class='".$warna."' data-filter='".$data_filter."'>
             <td align=center>$no</td>
             <td>".htmlspecialchars($r['username'], ENT_QUOTES)."</td>
             <td>".htmlspecialchars($r['nama_lengkap'], ENT_QUOTES)."</td>
             <td align=center>
             <a type='button' class='btn btn-success margin' href=\"index.php?module=admin&act=editadmin&id=".rawurlencode($r['username'])."\"><i class='fa fa-pencil-square-o' aria-hidden='true'></i> Ubah </a> &nbsp;
              <a type='button' class='btn btn-danger margin' href=\"JavaScript: confirmIt('Anda yakin akan menghapusnya ?','$aksi?module=admin&act=hapus&id=".rawurlencode($r['username'])."','','','','u','n','Self','Self')\"><i class='fa fa-trash-o' aria-hidden='true'></i> Hapus</a>
             </td></tr>";
      $no++;
	  $counter++;
    }
    echo "</tbody></table>";
    
    // KODE PAGINATION PHP (DI PERTAHANKAN)
	echo "<div class=paging>";

	if ($offset!=0) {
		$prevoffset = $offset-10;
		echo "<span class=prevnext> <a href=index.php?module=admin&offset=$prevoffset>Back</a></span>";
	}
	else {
		echo "<span class=disabled>Back</span>";//cetak halaman tanpa link
	}
	//hitung jumlah halaman
	$halaman = intval($baris/$limit);//Pembulatan

	if ($baris%$limit){
		$halaman++;
	}
	for($i=1;$i<=$halaman;$i++){
		$newoffset = $limit * ($i-1);
		if($offset!=$newoffset){
			echo "<a href=index.php?module=admin&offset=$newoffset>$i</a>";
			//cetak halaman
		}
		else {
			echo "<span class=current>".$i."</span>";//cetak halaman tanpa link
		}
	}

	//cek halaman akhir
	if(!(($offset/$limit)+1==$halaman) && $halaman !=1){

		//jika bukan halaman terakhir maka berikan next
		$newoffset = $offset + $limit;
		echo "<span class=prevnext><a href=index.php?module=admin&offset=$newoffset>Next</a>";
	}
	else {
		echo "<span class=disabled>Next</span>";//cetak halaman tanpa link
	}
	
	echo "</div>";
	}else{
	echo "<br><b>Data Kosong !</b>";
	}
    
    // Tambahkan SKRIP JAVASCRIPT/JQUERY
    ?>
    <style>
    /* CSS untuk highlight baris */
    .highlight {
        background-color: #ffff99 !important;
    }
    </style>
    <script>
    $(function () {
        // Fungsi untuk filtering Admin
        function filterTableAdmin() {
            var $rows = $('#adminTable tbody tr');
            var filterValue = $('#keyword_search').val().toLowerCase().trim();
            var visibleRowCount = 0;
            var messageContainer = $('#search_message_container');
            
            $rows.each(function() {
                // Ambil data dari atribut data-filter (Username dan Nama Lengkap)
                var dataText = $(this).data('filter').toLowerCase();
                
                // Cek apakah teks dimulai dengan nilai filter (filter 'starts with')
                if (dataText.startsWith(filterValue)) { 
                    $(this).show();
                    $(this).removeClass('highlight');
                    if (filterValue !== '') {
                        $(this).addClass('highlight');
                    }
                    visibleRowCount++;
                } else {
                    $(this).hide();
                }
            });

            // Logika Tampilkan/Sembunyikan pesan dan Paging
            if (filterValue !== '') {
                // Mode Pencarian Aktif (Filter tidak kosong)
                $('.paging').hide(); // Sembunyikan paging saat mencari
                
                if (visibleRowCount === 0) {
                    $('#adminTable').hide();
                    
                    // Tampilkan pesan GAGAL (alert-danger)
                    messageContainer.html(
                        "<div class='alert alert-danger alert-dismissible'>" +
                        "<h4><i class='icon fa fa-ban'></i> Gagal!</h4>" +
                        "Maaf, Admin yang anda cari tidak ditemukan pada halaman ini.</div>"
                    ).show();

                } else {
                    $('#adminTable').show();
                    
                    // Tampilkan pesan SUKSES (alert-success)
                    messageContainer.html(
                        "<div class='alert alert-success alert-dismissible'>" +
                        "<h4><i class='icon fa fa-check'></i> Sukses!</h4>" +
                        "Admin yang anda cari di temukan pada halaman ini.</div>"
                    ).show();
                }
            } else {
                // Mode Default (Filter kosong)
                $('#adminTable').show();
                messageContainer.empty().hide(); // Hapus pesan
                $('.paging').show(); // Tampilkan paging
            }
        }

        // Event handler untuk filtering: jalankan filter saat ada input (real-time)
        $('#keyword_search').on('keyup', function() {
            filterTableAdmin();
        });

        // Jalankan filter saat halaman dimuat
        filterTableAdmin();
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
    $id_admin = isset($_GET['id']) ? mysqli_real_escape_string($conn, $_GET['id']) : '';

    if (!empty($id_admin)) {
        $edit=mysqli_query($conn,"SELECT * FROM admin WHERE username='$id_admin'");
        
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
            echo "<div class='alert alert-danger'>Admin dengan username '$id_admin' tidak ditemukan.</div>";
        }
    } else {
        echo "<div class='alert alert-warning'>ID Admin tidak disediakan.</div>";
    }
    break;  
}
?>
<?php } ?>