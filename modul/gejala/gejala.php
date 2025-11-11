<title>Gejala - Chirexs 1.0</title>
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
  
// Form diubah: onsubmit='return false;' dan tombol 'Cari' dihapus
echo "<form method=POST action='?module=gejala' name=text_form onsubmit='return false;'>
      <br><br><table class='table table-bordered'>
      <tr><td>
        <input class='btn bg-olive margin' type=button name=tambah value='Tambah Gejala' 
               onclick=\"window.location.href='?module=gejala&act=tambahgejala&offset=".$offset."';\">
        <input type=text name='keyword' id='keyword_search' style='margin-left: 10px;' 
               placeholder='Ketik dan tekan cari...' class='form-control' 
               value='' /> 
        </td></tr>
      </table></form>";

	$baris=mysqli_num_rows($tampil);
		  
    // Blok pencarian berbasis POST (if (isset($_POST['Go'])) { ... }) telah dihapus.
    // Hanya menyisakan blok tampilan tabel default.
	
	if($baris>0){
	// ID tabel ditambahkan
	echo" <table class='table table-bordered' style='overflow-x=auto' cellpadding='0' cellspacing='0' id='gejalaTable'>
          <thead>
            <tr>
              <th style='text-align: center;'>No</th>
              <th>Nama Gejala</th>
              <th style='text-align: center;' width='21%'>Aksi</th>
            </tr>
          </thead>
		  <tbody>
		  "; 
	$hasil = mysqli_query($conn,"SELECT * FROM gejala ORDER BY kode_gejala LIMIT $offset,$limit");
    $no = 1 + $offset;
    $counter = 1;
    while ($r = mysqli_fetch_assoc($hasil)) {
        $warna = ($counter % 2 == 0) ? "dark" : "light";

        // data-nama ditambahkan pada baris untuk filtering JavaScript
        $deleteUrl = htmlspecialchars($aksi . "?module=gejala&act=hapus&id=" . urlencode($r['kode_gejala']) . "&offset=" . $offset, ENT_QUOTES);

        echo "<tr class='".$warna."' data-nama='".htmlspecialchars($r['nama_gejala'], ENT_QUOTES)."'>
             <td align='center'>".$no."</td>
             <td>".htmlspecialchars($r['nama_gejala'], ENT_QUOTES)."</td>
             <td align='center'>
               <a class='btn btn-success margin' href='?module=gejala&act=editgejala&id=".urlencode($r['kode_gejala'])."&offset=".$offset."'><i class='fa fa-pencil-square-o' aria-hidden='true'></i> Ubah </a> &nbsp;
               <a class='btn btn-danger margin' href=\"JavaScript: confirmIt('Anda yakin akan menghapusnya ?','".$deleteUrl."','','','','u','n','Self','Self')\"><i class='fa fa-trash-o' aria-hidden='true'></i> Hapus</a>
             </td></tr>";
        $no++;
        $counter++;
    }
    echo "</tbody></table>";
    echo "<div class=paging>";

    if ($offset != 0) {
        $prevoffset = max(0, $offset - $limit);
        echo "<span class=prevnext> <a href='index.php?module=gejala&offset=$prevoffset'>Back</a></span>";
    } else {
        echo "<span class=disabled>Back</span>";
    }
    // hitung jumlah halaman
    $halaman = intval($baris / $limit);
    if ($baris % $limit) $halaman++;
    for ($i = 1; $i <= $halaman; $i++) {
        $newoffset = $limit * ($i - 1);
        if ($offset != $newoffset) {
            echo "<a href='index.php?module=gejala&offset=$newoffset'>$i</a>";
        } else {
            echo "<span class=current>" . $i . "</span>";
        }
    }

    // cek halaman akhir
    if (!(($offset / $limit) + 1 == $halaman) && $halaman != 1) {
        $newoffset = $offset + $limit;
        echo "<span class=prevnext><a href='index.php?module=gejala&offset=$newoffset'>Next</a></span>";
    } else {
        echo "<span class=disabled>Next</span>";
    }

    echo "</div>";
	}else{
	echo "<br><b>Data Kosong !</b>";
	}

    // Tambahkan CSS dan Skrip Filter Real-Time
    ?>
    <style>
    .highlight {
        background-color: #ffff99 !important;
    }
    </style>

    <script>
    $(function () {
        // Fungsi untuk filtering Gejala
        function filterTableGejala() {
            var $rows = $('#gejalaTable tbody tr');
            // Ambil nilai dari input keyword_search
            var filterValue = $('#keyword_search').val().toLowerCase();
            
            $rows.each(function() {
                // Ambil nama gejala dari atribut data-nama pada baris
                var namaGejalaText = $(this).data('nama').toLowerCase();
                
                // Cek apakah teks gejala dimulai dengan nilai filter (filter 'starts with')
                if (namaGejalaText.indexOf(filterValue) === 0) {
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

        // Event handler untuk filtering: jalankan filter saat ada input (real-time)
        $('#keyword_search').on('keyup', function() {
            filterTableGejala();
        });
    });
    </script>
    <?php
    break;
  
  case "tambahgejala":
    echo "<form name=text_form method=POST action='$aksi?module=gejala&act=input' onsubmit='return Blank_TextField_Validator()'>
          <input type='hidden' name='offset' value='".intval($offset)."'>
          <br><br><table class='table table-bordered'>
		  <tr><td width=120>Nama Gejala</td><td><input type=text autocomplete='off' placeholder='Masukkan gejala baru...' class='form-control' name='nama_gejala' size=30></td></tr>
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
          <input class='btn btn-danger' type=button value='Batal' onclick=\"window.location.href='?module=gejala&offset=".$curOffset."';\"></td></tr>
          </table></form>";
    break;  
}
?>
<?php } ?>