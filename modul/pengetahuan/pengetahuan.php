<title>Pengetahuan - SpkarCF</title>
<?php

if (!(isset($_SESSION['username']) && isset($_SESSION['password']))) {
    header('location:index.php');
    exit();
} else {
    ?>
<script type="text/javascript">
function Blank_TextField_Validator()
{
if (text_form.kode_kerusakan.value == "")
{
   alert("Pilih dulu kerusakan !");
   text_form.kode_kerusakan.focus();
   return (false);
}
if (text_form.kode_gejala.value == "")
{
   alert("Pilih dulu gejala !") ;
   text_form.kode_gejala.focus();
   return (false);
}
if (text_form.mb.value == "")
{
   alert("Isi dulu MB !");
   text_form.mb.focus();
   return (false);
}
if (text_form.md.value == "")
{
   alert("Isi dulu MD !");
   text_form.md.focus();
   return (false);
}
return (true);
}
// Fungsi Blank_TextField_Validator_Cari() dihapus karena menggunakan filter real-time
</script>
<?php
include "config/fungsi_alert.php";
$aksi="modul/pengetahuan/aksi_pengetahuan.php";
switch ($_GET['act'] ?? '') {
	// Tampil pengetahuan
  default:
    $offset = $_GET['offset'] ?? 0;
	//jumlah data yang ditampilkan perpage
	$limit = 15;
	if (empty ($offset)) {
		$offset = 0;
	}
  
  // >>> FIX BARIS 54: Menggunakan p.kode_pengetahuan sebagai ID yang benar
  $tampil=mysqli_query($conn,"SELECT p.kode_pengetahuan AS id_pengetahuan, k.nama_kerusakan, g.nama_gejala, p.mb, p.md
                              FROM basis_pengetahuan p 
                              INNER JOIN kerusakan k ON p.kode_kerusakan=k.kode_kerusakan 
                              INNER JOIN gejala g ON p.kode_gejala=g.kode_gejala
                              ORDER BY id_pengetahuan");
  $baris=mysqli_num_rows($tampil);
  
// Form diubah: onsubmit='return false;' dan tombol 'Cari' dihapus
echo "<form method=POST action='?module=pengetahuan' name=text_form onsubmit='return false;'>
          <br><table class='table table-bordered'>
		  <tr><td><input class='btn bg-olive margin' type=button name=tambah value='Tambah Pengetahuan' onclick=\"window.location.href='?module=pengetahuan&act=tambahpengetahuan';\">
          <input type=text name='keyword' id='keyword_search' style='margin-left: 10px;' placeholder='Ketik kerusakan atau gejala...' class='form-control' value='' /> 
          </td></tr></table></form>";

// CONTAINER UNTUK PESAN SUKSES/GAGAL
echo "<div id='search_message_container'></div>";

if($baris>0){
    // Tabel diberi ID untuk JavaScript Filtering
echo "<table class='table table-bordered' style='overflow-x=auto' cellpadding='0' cellspacing='0' id='pengetahuanTable'>
          <thead>
            <tr>
              <th width='5%' style='text-align: center;'>No</th>
              <th width='10%' style='text-align: center;'>Kerusakan</th>
              <th width='45%' style='text-align: center;'>Gejala</th>
              <th width='10%' style='text-align: center;'>MB</th>
              <th width='10%' style='text-align: center;'>MD</th>
              <th width='25%' style='text-align: center;'>Aksi</th>
            </tr>
          </thead>
          <tbody>";
          
    // >>> FIX: Menggunakan p.kode_pengetahuan sebagai ID yang benar untuk pagination
    $hasil=mysqli_query($conn,"SELECT p.kode_pengetahuan AS id_pengetahuan, k.nama_kerusakan, g.nama_gejala, p.mb, p.md
                              FROM basis_pengetahuan p 
                              INNER JOIN kerusakan k ON p.kode_kerusakan=k.kode_kerusakan 
                              INNER JOIN gejala g ON p.kode_gejala=g.kode_gejala
                              ORDER BY id_pengetahuan limit $offset,$limit");
    $no=1+$offset;
    $counter=1;

	while ($r=mysqli_fetch_array($hasil)){
    if($counter % 2 == 0) $warna="dark"; else $warna="light";
    
    // Data filter menggunakan gabungan Nama Kerusakan dan Nama Gejala
    $data_filter = htmlspecialchars($r['nama_kerusakan'] . " " . $r['nama_gejala'], ENT_QUOTES); 

	echo "<tr class='".$warna."' data-filter='".$data_filter."'>
          <td align=center>$no</td>
          <td>".htmlspecialchars($r['nama_kerusakan'], ENT_QUOTES)."</td>
          <td>".htmlspecialchars($r['nama_gejala'], ENT_QUOTES)."</td>
          <td>".htmlspecialchars($r['mb'], ENT_QUOTES)."</td>
          <td>".htmlspecialchars($r['md'], ENT_QUOTES)."</td>
          <td align=center>
          <a type='button' class='btn btn-success margin' href='index.php?module=pengetahuan&act=editpengetahuan&id=".rawurlencode($r['id_pengetahuan'])."&offset=$offset'><i class='fa fa-pencil-square-o' aria-hidden='true'></i> Ubah </a> &nbsp;
          <a type='button' class='btn btn-danger margin' href=\"JavaScript: confirmIt('Anda yakin akan menghapusnya ?','$aksi?module=pengetahuan&act=hapus&id=".rawurlencode($r['id_pengetahuan'])."&offset=$offset','','','','u','n','Self','Self')\"><i class='fa fa-trash-o' aria-hidden='true'></i> Hapus</a>
          </td></tr>";
    $no++;
    $counter++;
	}
	echo "</tbody></table>";
  
    // KODE PAGINATION PHP (DI PERTAHANKAN)
    echo "<div class=paging>";

	if ($offset!=0) {
		$prevoffset=$offset-$limit;
		echo "<span class=prevnext> <a href=index.php?module=pengetahuan&offset=$prevoffset>Back</a></span>";
	}else{
		echo "<span class=disabled>Back</span>"; //cetak halaman tanpa link
	}
	//hitung jumlah halaman
	$halaman=intval($baris/$limit);//Pembulatan 

	if ($baris%$limit){
		$halaman++;
	}
	for($i=1;$i<=$halaman;$i++){
		$newoffset=$limit*($i-1);
		if($offset!=$newoffset){
			echo "<a href=index.php?module=pengetahuan&offset=$newoffset>$i</a>";
			//cetak halaman
		}else{
			echo "<span class=current>".$i."</span>"; //cetak halaman tanpa link
		}
	}

	//cek halaman akhir
	if(!(($offset/$limit)+1==$halaman) && $halaman!=1){

		//jika bukan halaman terakhir maka berikan next
		$newoffset=$offset+$limit;
		echo "<span class=prevnext><a href=index.php?module=pengetahuan&offset=$newoffset>Next</a>";
	}else{
		echo "<span class=disabled>Next</span>"; //cetak halaman tanpa link
	}

	echo "</div>";
    }else{
      echo "<br><b>Data Basis Pengetahuan Kosong !</b>";
    }
  
    // SKRIP JAVASCRIPT/JQUERY UNTUK FILTER REAL-TIME DAN ALERT DINAMIS
    ?>
    <style>
    /* CSS untuk highlight baris */
    .highlight {
        background-color: #ffff99 !important;
    }
    </style>
    <script>
    $(function () {
        // Fungsi untuk filtering Basis Pengetahuan
        function filterTablePengetahuan() {
            var $rows = $('#pengetahuanTable tbody tr');
            var filterValue = $('#keyword_search').val().toLowerCase().trim();
            var visibleRowCount = 0;
            var messageContainer = $('#search_message_container');
            
            $rows.each(function() {
                // Ambil data dari atribut data-filter (Gabungan Kerusakan dan Gejala)
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
                    $('#pengetahuanTable').hide();
                    
                    // Tampilkan pesan GAGAL (alert-danger)
                    messageContainer.html(
                        "<div class='alert alert-danger alert-dismissible'>" +
                        "<h4><i class='icon fa fa-ban'></i> Gagal!</h4>" +
                        "Maaf, data Basis Pengetahuan yang anda cari tidak ditemukan pada halaman ini.</div>"
                    ).show();

                } else {
                    $('#pengetahuanTable').show();
                    
                    // Tampilkan pesan SUKSES (alert-success)
                    messageContainer.html(
                        "<div class='alert alert-success alert-dismissible'>" +
                        "<h4><i class='icon fa fa-check'></i> Sukses!</h4>" +
                        "Data Basis Pengetahuan yang anda cari di temukan pada halaman ini.</div>"
                    ).show();
                }
            } else {
                // Mode Default (Filter kosong)
                $('#pengetahuanTable').show();
                messageContainer.empty().hide(); // Hapus pesan
                $('.paging').show(); // Tampilkan paging
            }
        }

        // Event handler untuk filtering: jalankan filter saat ada input (real-time)
        $('#keyword_search').on('keyup', function() {
            filterTablePengetahuan();
        });

        // Jalankan filter saat halaman dimuat
        filterTablePengetahuan();
    });
    </script>
    <?php
    break;

  case "tambahpengetahuan":
    echo "<form name=text_form method=POST action='$aksi?module=pengetahuan&act=input' onsubmit='return Blank_TextField_Validator()'>
          <br><br><table class='table table-bordered'>
          <tr><td width=120>Kerusakan</td><td><select class='form-control' name='kode_kerusakan' id='kode_kerusakan'>
          <option value='0' selected>- Pilih Kerusakan -</option>";
          $hasil4 = mysqli_query($conn,"SELECT * FROM kerusakan ORDER BY nama_kerusakan");
          while($r4 = mysqli_fetch_array($hasil4)){
            echo "<option value='".htmlspecialchars($r4['kode_kerusakan'], ENT_QUOTES)."'>".htmlspecialchars($r4['nama_kerusakan'], ENT_QUOTES)."</option>";
          }
    echo    "</select></td></tr>
          <tr><td>Gejala</td><td><select class='form-control' name='kode_gejala' id='kode_gejala'>
          <option value='0' selected>- Pilih Gejala -</option>";
          $hasil4 = mysqli_query($conn,"SELECT * FROM gejala ORDER BY nama_gejala");
          while($r4 = mysqli_fetch_array($hasil4)){
            echo "<option value='".htmlspecialchars($r4['kode_gejala'], ENT_QUOTES)."'>".htmlspecialchars($r4['nama_gejala'], ENT_QUOTES)."</option>";
          }
    echo "</select></td></tr>
          <tr><td>MB</td><td><input autocomplete='off' placeholder='Masukkan MB' type=text class='form-control' name='mb' size=15></td></tr>
          <tr><td>MD</td><td><input autocomplete='off' placeholder='Masukkan MD' type=text class='form-control' name='md' size=15></td></tr>
          <tr><td></td><td><input class='btn btn-success' type=submit name=submit value='Simpan' >
          <input class='btn btn-danger' type=button name=batal value='Batal' onclick=\"window.location.href='?module=pengetahuan';\"></td></tr>
          </table></form>";
     break;
    
  case "editpengetahuan":
    $id = isset($_GET['id']) ? mysqli_real_escape_string($conn, $_GET['id']) : '';
    $curOffset = intval($_GET['offset'] ?? 0);
    // >>> FIX: Menggunakan kode_pengetahuan untuk WHERE
    $edit=mysqli_query($conn,"SELECT * FROM basis_pengetahuan WHERE kode_pengetahuan='".mysqli_real_escape_string($conn, $id)."'"); 
    $r=mysqli_fetch_array($edit);
    
    echo "<form name=text_form method=POST action='$aksi?module=pengetahuan&act=update' onsubmit='return Blank_TextField_Validator()'>
          <input type=hidden name=id value='".htmlspecialchars($r['kode_pengetahuan'], ENT_QUOTES)."'>
          <input type=hidden name=offset value='". $curOffset ."'>
          <br><br><table class='table table-bordered'>
          <tr><td width=120>Kerusakan</td><td><select class='form-control' name='kode_kerusakan' id='kode_kerusakan'>";
          $hasil4 = mysqli_query($conn,"SELECT * FROM kerusakan ORDER BY nama_kerusakan");
          while($r4 = mysqli_fetch_array($hasil4)){
            echo "<option value='".htmlspecialchars($r4['kode_kerusakan'], ENT_QUOTES)."'";
            if($r['kode_kerusakan'] == $r4['kode_kerusakan']) echo " selected";
            echo ">".htmlspecialchars($r4['nama_kerusakan'], ENT_QUOTES)."</option>";
        }
        echo	"</select></td></tr>
        <tr><td>Gejala</td><td><select class='form-control' name='kode_gejala' id='kode_gejala'>";
        $hasil4 = mysqli_query($conn,"SELECT * FROM gejala ORDER BY nama_gejala");
        while($r4 = mysqli_fetch_array($hasil4)){
            echo "<option value='".htmlspecialchars($r4['kode_gejala'], ENT_QUOTES)."'";
            if($r['kode_gejala'] == $r4['kode_gejala']) echo " selected";
            echo ">".htmlspecialchars($r4['nama_gejala'], ENT_QUOTES)."</option>";
        }
        echo "</select></td></tr>
        <tr><td>MB</td><td><input autocomplete='off' placeholder='Masukkan MB' type=text class='form-control' name='mb' size=15 value='".htmlspecialchars($r['mb'], ENT_QUOTES)."'></td></tr>
        <tr><td>MD</td><td><input autocomplete='off' placeholder='Masukkan MD' type=text class='form-control' name='md' size=15 value='".htmlspecialchars($r['md'], ENT_QUOTES)."'></td></tr>
        <tr><td></td><td>
            <input class='btn btn-success' type=submit name=submit value='Simpan'>
            <input class='btn btn-danger' type=button name=batal value='Batal' onclick=\"window.location.href='?module=pengetahuan&offset=$curOffset';\">
        </td></tr>
        </table></form>";
    break;  
}
?>
<?php } ?>