<title>Gejala - SpakarCF</title>
<?php

if (!(isset($_SESSION['username']) && isset($_SESSION['password']))) {
    header('location:index.php');
    exit();
} else {
    ?>
<script type="text/javascript">
function Blank_TextField_Validator()
{
if (text_form.nama_gejala.value == "")
{
   alert("Nama Gejala tidak boleh kosong !");
   text_form.nama_gejala.focus();
   return (false);
}
return (true);
}
// Fungsi Blank_TextField_Validator_Cari() dihapus karena menggunakan filter real-time
</script>
<?php
include "config/fungsi_alert.php";
$aksi="modul/gejala/aksi_gejala.php";

// pastikan $offset selalu terdefinisi (hindari Notice)
$offset = intval($_GET['offset'] ?? 0);

switch ($_GET['act'] ?? '') {
	// Tampil gejala
  default:
  $offset = $_GET['offset'] ?? 0;
	//jumlah data yang ditampilkan perpage
	$limit = 15;
	if (empty ($offset)) {
		$offset = 0;
	}
  $tampil=mysqli_query($conn,"SELECT * FROM gejala ORDER BY kode_gejala");
  $baris=mysqli_num_rows($tampil);
  
// Form diubah: onsubmit='return false;' dan tombol 'Cari' dihapus
echo "<form method=POST action='?module=gejala' name=text_form onsubmit='return false;'>
          <br><table class='table table-bordered'>
		  <tr><td><input class='btn bg-olive margin' type=button name=tambah value='Tambah Gejala' onclick=\"window.location.href='?module=gejala&act=tambahgejala';\">
          <input type=text name='keyword' id='keyword_search' style='margin-left: 10px;' placeholder='Ketik nama gejala...' class='form-control' value='' /> 
          </td></tr></table></form>";

// CONTAINER UNTUK PESAN SUKSES/GAGAL
echo "<div id='search_message_container'></div>";

if($baris>0){
    // Tabel diberi ID untuk JavaScript Filtering
echo "<table class='table table-bordered' style='overflow-x=auto' cellpadding='0' cellspacing='0' id='gejalaTable'>
          <thead>
            <tr>
              <th width='5%' style='text-align: center;'>No</th>
              <th width='55%'>Nama Gejala</th> <th width='40%' style='text-align: center;'>Aksi</th>
            </tr>
          </thead>
          <tbody>";
          
    // QUERY TETAP MEMAKAI LIMIT UNTUK PAGINATION PHP
    $hasil=mysqli_query($conn,"SELECT * FROM gejala ORDER BY kode_gejala limit $offset,$limit");
    $no=1+$offset;
    $counter=1;

	while ($r=mysqli_fetch_array($hasil)){
    if($counter % 2 == 0) $warna="dark"; else $warna="light";
    
    // Data filter HANYA menggunakan Nama Gejala
    $data_filter = htmlspecialchars($r['nama_gejala'], ENT_QUOTES); 

	echo "<tr class='".$warna."' data-filter='".$data_filter."'>
          <td align=center>$no</td>
          <td>".htmlspecialchars($r['nama_gejala'], ENT_QUOTES)."</td>
          <td align=center>
          <a type='button' class='btn btn-success margin' href='index.php?module=gejala&act=editgejala&id=".rawurlencode($r['kode_gejala'])."&offset=$offset'><i class='fa fa-pencil-square-o' aria-hidden='true'></i> Ubah </a> &nbsp;
          <a type='button' class='btn btn-danger margin' href=\"JavaScript: confirmIt('Anda yakin akan menghapusnya ?','$aksi?module=gejala&act=hapus&id=".rawurlencode($r['kode_gejala'])."&offset=$offset','','','','u','n','Self','Self')\"><i class='fa fa-trash-o' aria-hidden='true'></i> Hapus</a>
          </td></tr>";
    $no++;
    $counter++;
	}
	echo "</tbody></table>";
  
    // KODE PAGINATION PHP (DI PERTAHANKAN)
    echo "<div class=paging>";

	if ($offset!=0) {
		$prevoffset=$offset-$limit;
		echo "<span class=prevnext> <a href=index.php?module=gejala&offset=$prevoffset>Back</a></span>";
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
			echo "<a href=index.php?module=gejala&offset=$newoffset>$i</a>";
			//cetak halaman
		}else{
			echo "<span class=current>".$i."</span>"; //cetak halaman tanpa link
		}
	}

	//cek halaman akhir
	if(!(($offset/$limit)+1==$halaman) && $halaman!=1){

		//jika bukan halaman terakhir maka berikan next
		$newoffset=$offset+$limit;
		echo "<span class=prevnext><a href=index.php?module=gejala&offset=$newoffset>Next</a>";
	}else{
		echo "<span class=disabled>Next</span>"; //cetak halaman tanpa link
	}

	echo "</div>";
    }else{
      echo "<br><b>Data Gejala Kosong !</b>";
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
        // Fungsi untuk filtering Gejala
        function filterTableGejala() {
            var $rows = $('#gejalaTable tbody tr');
            var filterValue = $('#keyword_search').val().toLowerCase().trim();
            var visibleRowCount = 0;
            var messageContainer = $('#search_message_container');
            
            $rows.each(function() {
                // Ambil data dari atribut data-filter (Yaitu HANYA Nama Gejala)
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
                    $('#gejalaTable').hide();
                    
                    // Tampilkan pesan GAGAL (alert-danger)
                    messageContainer.html(
                        "<div class='alert alert-danger alert-dismissible'>" +
                        "<h4><i class='icon fa fa-ban'></i> Gagal!</h4>" +
                        "Maaf, Gejala yang anda cari tidak ditemukan pada halaman ini.</div>"
                    ).show();

                } else {
                    $('#gejalaTable').show();
                    
                    // Tampilkan pesan SUKSES (alert-success)
                    messageContainer.html(
                        "<div class='alert alert-success alert-dismissible'>" +
                        "<h4><i class='icon fa fa-check'></i> Sukses!</h4>" +
                        "Gejala yang anda cari di temukan pada halaman ini.</div>"
                    ).show();
                }
            } else {
                // Mode Default (Filter kosong)
                $('#gejalaTable').show();
                messageContainer.empty().hide(); // Hapus pesan
                $('.paging').show(); // Tampilkan paging
            }
        }

        // Event handler untuk filtering: jalankan filter saat ada input (real-time)
        $('#keyword_search').on('keyup', function() {
            filterTableGejala();
        });

        // Jalankan filter saat halaman dimuat
        filterTableGejala();
    });
    </script>
    <?php
    break;

  case "tambahgejala":
    echo "<form name=text_form method=POST action='$aksi?module=gejala&act=input' onsubmit='return Blank_TextField_Validator()'>
          <br><br><table class='table table-bordered'>
          <tr><td width=120>Nama Gejala</td><td><input autocomplete='off' placeholder='Masukkan gejala baru...' class='form-control' name='nama_gejala' size=30></td></tr>
		  <tr><td></td><td><input class='btn btn-success' type=submit name=submit value='Simpan' >
		  <input class='btn btn-danger' type=button name=batal value='Batal' onclick=\"window.location.href='?module=gejala';\"></td></tr>
          </table></form>";
     break;
    
  case "editgejala":
    $edit=mysqli_query($conn,"SELECT * FROM gejala WHERE kode_gejala='".mysqli_real_escape_string($conn, $_GET['id'])."'");
    $r=mysqli_fetch_array($edit);
    $curOffset = intval($_GET['offset'] ?? 0);
    
    echo "<form name=text_form method=POST action='$aksi?module=gejala&act=update' onsubmit='return Blank_TextField_Validator()'>
          <input type=hidden name=id value='".htmlspecialchars($r['kode_gejala'], ENT_QUOTES)."'>
          <input type=hidden name=offset value='". $curOffset ."'>
          <br><br><table class='table table-bordered'>
          <tr><td width=120>Nama Gejala</td><td><input autocomplete='off' type=text class='form-control' name='nama_gejala' size=30 value=\"".htmlspecialchars($r['nama_gejala'], ENT_QUOTES)."\"></td></tr>
          <tr><td></td><td><input class='btn btn-success' type=submit name=submit value='Simpan' >
          <input class='btn btn-danger' type=button name=batal value='Batal' onclick=\"window.location.href='?module=gejala&offset=$curOffset';\"></td></tr>
          </table></form>";
     break;
}

?>
<?php } ?>