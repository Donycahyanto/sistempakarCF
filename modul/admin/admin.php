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
  // hanya periksa password jika field ada (saat tambah admin)
  if (form.password && form.password.value.trim() == "")
  {
     alert("Password tidak boleh kosong !");
     form.password.focus();
     return false;
  }
  return true;
}
// Fungsi Blank_TextField_Validator_Cari() dihapus karena kita pakai filter real-time
</script>
<?php
include "config/fungsi_alert.php";
$act = $_GET['act'] ?? '';
$aksi="modul/admin/aksi_admin.php";

switch ($act) {
	// Tampil Admin
  default:
    $offset = $_GET['offset'] ?? 0; // PAGING: Ambil offset
    $keyword = ''; // PENCARIAN PHP LAMA DIHAPUS

    //jumlah data yang ditampilkan perpage
	$limit = 10; // PAGING: Limit data
	if (empty ($offset)) {
		$offset = 0;
	}

    $tampil=mysqli_query($conn,"SELECT * FROM admin ORDER BY username");
    $baris=mysqli_num_rows($tampil);
    
    // Form filter real-time: onsubmit='return false;' dan tombol Cari dihapus
	echo "<form method=POST action='?module=admin' name=text_form onsubmit='return false;'>
          <br><br><table class='table table-bordered'>
          <tr><td><input class='btn bg-olive margin' type=button name=tambah value='Tambah Admin' onclick=\"window.location.href='?module=admin&act=tambahadmin';\">".
          // Input pencarian diberi ID
          "<input type=text name='keyword' id='keyword_search' style='margin-left: 10px;' placeholder='Ketik dan tekan cari...' class='form-control' value='' />".
          "</td> </tr>
          </table></form>";
	
    
	if($baris>0){
    // Tabel diberi ID untuk JavaScript Filtering
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
    // QUERY TETAP MEMAKAI LIMIT UNTUK PAGINATION PHP
	$hasil = mysqli_query($conn,"SELECT * FROM admin ORDER BY username limit $offset,$limit");
    $no = 1 + $offset; // MULAI NOMOR URUT DARI OFFSET
    while ($r=mysqli_fetch_array($hasil)){
        // Data filter menggunakan gabungan Username dan Nama Lengkap
        $data_filter = htmlspecialchars($r['username'] . " " . $r['nama_lengkap'], ENT_QUOTES);
        
        echo "<tr data-nama='".$data_filter."'>
             <td align=center>$no</td> <td>".htmlspecialchars($r['username'], ENT_QUOTES)."</td>
             <td>".htmlspecialchars($r['nama_lengkap'], ENT_QUOTES)."</td>
             <td align=center>
               <a class='btn btn-success margin' href='?module=admin&act=editadmin&id=".urlencode($r['username'])."'><i class='fa fa-pencil-square-o' aria-hidden='true'></i> Ubah </a> &nbsp;
               <a class='btn btn-danger margin' href=\"JavaScript: confirmIt('Anda yakin akan menghapusnya ?','$aksi?module=admin&act=hapus&id=".urlencode($r['username'])."','','','','u','n','Self','Self')\" onMouseOver=\"self.status=''; return true\" onMouseOut=\"self.status=''; return true\">
               <i class='fa fa-trash-o' aria-hidden='true'></i> Hapus</a>
             </td></tr>";
        $no++; // INCREMENT NOMOR URUT
    }
    echo "</tbody></table>";
	
    // KODE PAGINATION PHP (DI PERTAHANKAN SESUAI PERMINTAAN)
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
	
	} else {
	    // KASUS: Data Admin kosong dari database
	    echo "<br><b>Data Kosong !</b>";
	}

    // Tambahkan CSS dan Skrip Filter Real-Time (Disesuaikan dari file lain)
    ?>
    <style>
    .highlight {
        background-color: #ffff99 !important;
    }
    </style>

    <script>
    $(function () {
        // Fungsi untuk filtering Admin
        function filterTableAdmin() {
            var $rows = $('#adminTable tbody tr');
            // Ambil nilai dari input keyword_search
            var filterValue = $('#keyword_search').val().toLowerCase();
            var visibleRowCount = 0;
            
            $rows.each(function() {
                // Ambil data gabungan (Username dan Nama Lengkap) dari atribut data-nama
                var dataText = $(this).data('nama').toLowerCase();
                
                // Filter hanya pada data di halaman ini
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

            // Logika Tampilkan/Sembunyikan pesan "Data tidak ditemukan"
            if (visibleRowCount === 0 && $rows.length > 0) {
                $('#adminTable').hide();
                // Sembunyikan juga Paging saat filter kosong
                $('.paging').hide(); 
                if (!$('#no_data_message').length) {
                    $('<div id="no_data_message"><br><b>Data Admin tidak ditemukan pada halaman ini.</b></div>').insertAfter('#adminTable');
                }
            } else {
                $('#adminTable').show();
                $('.paging').show(); // Tampilkan Paging lagi
                $('#no_data_message').remove();
            }
        }

        // Event handler untuk filtering: jalankan filter saat ada input (real-time)
        $('#keyword_search').on('keyup', function() {
            filterTableAdmin();
        });

        // Jalankan filter saat halaman dimuat (untuk konsistensi)
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