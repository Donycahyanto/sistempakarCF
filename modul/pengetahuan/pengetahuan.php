<title>Pengetahuan - Chirexs 1.0</title>
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
   alert("Pilih dulu gejala !");
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
// Fungsi Blank_TextField_Validator_Cari() telah dihapus
</script>
<?php
include "config/fungsi_alert.php";
$aksi="modul/pengetahuan/aksi_pengetahuan.php";
switch ($_GET['act'] ?? '') {
	// Tampil pengetahuan
  default:
    $offset = max(0, intval($_GET['offset'] ?? 0)); // Ensure non-negative offset
    //jumlah data yang ditampilkan perpage
    $limit = 15;
    
    $tampil = mysqli_query($conn,"SELECT * FROM basis_pengetahuan ORDER BY kode_pengetahuan");
    $baris = mysqli_num_rows($tampil);

    // Calculate pagination values
    $halaman = ceil($baris/$limit); // Use ceil instead of intval
    if ($offset >= $baris) {
        $offset = max(0, (($halaman - 1) * $limit));
    }

    // Main query with validated offset
    $hasil = mysqli_query($conn,"SELECT * FROM basis_pengetahuan ORDER BY kode_pengetahuan LIMIT $offset,$limit");
    
    // Form diubah: onsubmit='return false;' dan tombol 'Cari' dihapus
	echo "<form method=POST action='?module=pengetahuan' name=text_form onsubmit='return false;'>
      <br><br><table class='table table-bordered'>
      <tr><td><input class='btn bg-olive margin' type=button name=tambah value='Tambah Basis Pengetahuan' onclick=\"window.location.href='?module=pengetahuan&act=tambahpengetahuan';\">".
      // Input pencarian diberi ID dan nilai value dihapus
      "<input type=text name='keyword' id='keyword_search' style='margin-left: 10px;' placeholder='Ketik dan tekan cari...' class='form-control' value='' />".
      // Tombol 'Cari' (<input type=submit value='   Cari   ' name=Go>) dihapus
      "</td> </tr>
      </table></form>";
		  	
    
	if($baris>0){
    // Tabel diberi ID untuk JavaScript Filtering
	echo" <table class='table table-bordered' style='overflow-x=auto' cellpadding='0' cellspacing='0' id='pengetahuanTable'>
          <thead>
            <tr>
              <th>No</th>
              <th>Kerusakan</th>
              <th>Gejala</th>
              <th>MB</th>
              <th>MD</th>
              <th width='21%'>Aksi</th>
            </tr>
          </thead>
		  <tbody>
		  "; 
	// Query utama digunakan untuk tampilan halaman
	$hasil = mysqli_query($conn,"SELECT * FROM basis_pengetahuan ORDER BY kode_pengetahuan limit $offset,$limit");
	$no = 1 + $offset;
	$counter = 1;
    while ($r=mysqli_fetch_array($hasil)){
	if ($counter % 2 == 0) $warna = "dark";
	else $warna = "light";
	
	// Ambil data gejala
	$sql = mysqli_query($conn,"SELECT nama_gejala FROM gejala where kode_gejala = '$r[kode_gejala]'");
    $rgejala = mysqli_fetch_array($sql); 
    
    // Ambil data kerusakan
    $sql2 = mysqli_query($conn,"SELECT nama_kerusakan FROM kerusakan where kode_kerusakan = '$r[kode_kerusakan]'");
    $rkerusakan = mysqli_fetch_array($sql2); 

    // Pengecekan data
    $nama_kerusakan = isset($rkerusakan['nama_kerusakan']) ? $rkerusakan['nama_kerusakan'] : 'Data Kerusakan Tidak Ditemukan';
    $nama_gejala = isset($rgejala['nama_gejala']) ? $rgejala['nama_gejala'] : 'Data Gejala Tidak Ditemukan';

    // Data filter digabungkan (Kerusakan dan Gejala) untuk filtering
    $data_filter = htmlspecialchars($nama_kerusakan . " " . $nama_gejala, ENT_QUOTES);
    $editUrl   = "?module=pengetahuan&act=editpengetahuan&id=".urlencode($r['kode_pengetahuan'])."&offset=".intval($offset);
    $deleteUrl = htmlspecialchars($aksi.'?module=pengetahuan&act=hapus&id='.urlencode($r['kode_pengetahuan']).'&offset='.intval($offset), ENT_QUOTES);

    echo "<tr class='".$warna."' data-nama='".$data_filter."'>
        <td align=center>$no</td>
        <td>".htmlspecialchars($nama_kerusakan, ENT_QUOTES)."</td>
        <td>".htmlspecialchars($nama_gejala, ENT_QUOTES)."</td>
        <td align=center>".htmlspecialchars($r['mb'], ENT_QUOTES)."</td>
        <td align=center>".htmlspecialchars($r['md'], ENT_QUOTES)."</td>
        <td align=center>
        <a class='btn btn-success margin' href='". $editUrl ."'><i class='fa fa-pencil-square-o' aria-hidden='true'></i> Ubah </a> &nbsp;
        <a class='btn btn-danger margin' href=\"JavaScript: confirmIt('Anda yakin akan menghapusnya ?','".$deleteUrl."','','','','u','n','Self','Self')\" onMouseOver=\"self.status=''; return true\" onMouseOut=\"self.status=''; return true\">
        <i class='fa fa-trash-o' aria-hidden='true'></i> Hapus</a>
        </td></tr>";
      $no++;
	  $counter++;
    }
    echo "</tbody></table>";
	echo "<div class=paging>";

	if ($offset > 0) {
        $prevoffset = max(0, $offset - $limit); 
        echo "<span class=prevnext><a href=index.php?module=pengetahuan&offset=$prevoffset>Back</a></span>";
    } else {
        echo "<span class=disabled>Back</span>";
    }
	//hitung jumlah halaman
	$halaman = intval($baris/$limit);//Pembulatan

	if ($baris%$limit){
		$halaman++;
	}
	for($i=1;$i<=$halaman;$i++){
		$newoffset = $limit * ($i-1);
		if($offset!=$newoffset){
			echo "<a href=index.php?module=pengetahuan&offset=$newoffset>$i</a>";
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
		echo "<span class=prevnext><a href=index.php?module=pengetahuan&offset=$newoffset>Next</a>";
	}
	else {
		echo "<span class=disabled>Next</span>";//cetak halaman tanpa link
	}
	
	echo "</div>";
	}else{
	echo "<br><b>Data Kosong !</b>";
	}

    break;
  
  case "tambahpengetahuan":
	echo "	<div class='alert alert-success alert-dismissible'>
                <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>
                <h4><i class='icon fa fa-exclamation-triangle'></i>Petunjuk Pengisian Pakar !</h4>
                Silahkan pilih gejala yang sesuai dengan kerusakan yang ada, dan berikan <b>nilai kepastian (MB & MB)</b> dengan cakupan sebagai berikut:<br><br>
				<b>1.0</b> (Pasti Ya)&nbsp;&nbsp;|&nbsp;&nbsp;<b>0.8</b> (Hampir Pasti)&nbsp;&nbsp;|<br>
				<b>0.6</b> (Kemungkinan Besar)&nbsp;&nbsp;|&nbsp;&nbsp;<b>0.4</b> (Mungkin)&nbsp;&nbsp;|<br>
				<b>0.2</b> (Hampir Mungkin)&nbsp;&nbsp;|&nbsp;&nbsp;<b>0.0</b> (Tidak Tahu atau Tidak Yakin)&nbsp;&nbsp;|<br><br>
				<b>CF(Pakar) = MB – MD</b><br>
				MB : Ukuran kenaikan kepercayaan (measure of increased belief) MD : Ukuran kenaikan ketidakpercayaan (measure of increased disbelief) <br> <br>
				<b>Contoh:</b><br>
				Jika kepercayaan <b>(MB)</b> anda terhadap gejala Mencret keputih-putihan untuk kerusakan Berak Kapur adalah <b>0.8 (Hampir Pasti)</b><br>
				Dan ketidakpercayaan <b>(MD)</b> anda terhadap gejala Mencret keputih-putihan untuk kerusakan Berak Kapur adalah <b>0.2 (Hampir Mungkin)</b><br><br>
				<b>Maka:</b> CF(Pakar) = MB – MD (0.8 - 0.2) = <b>0.6</b> <br>
				Dimana nilai kepastian anda terhadap gejala Mencret keputih-putihan untuk kerusakan Berak Kapur adalah <b>0.6 (Kemungkinan Besar)</b>
              </div>
          <form name=text_form method=POST action='$aksi?module=pengetahuan&act=input' onsubmit='return Blank_TextField_Validator()'>
<input type='hidden' name='offset' value='".intval($offset)."'>
          <br><br><table class='table table-bordered'>
		  <tr><td width=120>Kerusakan</td><td><select class='form-control' name='kode_kerusakan'  id='kode_kerusakan'><option value=''>- Pilih Kerusakan -</option>";
		$hasil4 = mysqli_query($conn,"SELECT * FROM kerusakan order by nama_kerusakan");
		while($r4=mysqli_fetch_array($hasil4)){
			echo "<option value='$r4[kode_kerusakan]'>$r4[nama_kerusakan]</option>";
		}
		echo	"</select></td></tr>
		<tr><td>Gejala</td><td><select class='form-control' name='kode_gejala' id='kode_gejala'><option value=''>- Pilih Gejala -</option>";
		$hasil4 = mysqli_query($conn,"SELECT * FROM gejala order by nama_gejala");
		while($r4=mysqli_fetch_array($hasil4)){
			echo "<option value='$r4[kode_gejala]'>$r4[nama_gejala]</option>";
		}
		echo	"</select></td></tr>
		<tr><td>MB</td><td><input autocomplete='off' placeholder='Masukkan MB' type=text class='form-control' name='mb' size=15 ></td></tr>
		<tr><td>MD</td><td><input autocomplete='off' placeholder='Masukkan MD' type=text class='form-control' name='md' size=15 ></td></tr>
		  <tr><td></td><td><input class='btn btn-success' type=submit name=submit value='Simpan' >
		  <input class='btn btn-danger' type=button name=batal value='Batal' onclick=\"window.location.href='?module=pengetahuan';\"></td></tr>
          </table></form>";
     break;
    
  case "editpengetahuan":
    $edit = mysqli_query($conn, "SELECT * FROM basis_pengetahuan WHERE kode_pengetahuan='".mysqli_real_escape_string($conn, $_GET['id'])."'");
    $r = mysqli_fetch_array($edit);
    
    echo "<br><br>
    <form name=text_form method=POST action='$aksi?module=pengetahuan&act=update' onsubmit='return Blank_TextField_Validator()'>
          <input type=hidden name=id value='$r[kode_pengetahuan]'>
          <input type=hidden name=offset value='". intval($_GET['offset'] ?? 0) ."'>
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
            <input class='btn btn-danger' type=button name=batal value='Batal' onclick=\"window.location.href='?module=pengetahuan';\">
        </td></tr>
        </table></form>";
    break;  
}
?>
<style>
.highlight {
    background-color: #ffff99 !important;
}
</style>

<script>
$(function () {
    // Fungsi untuk filtering Basis Pengetahuan
    function filterTablePengetahuan() {
        var $rows = $('#pengetahuanTable tbody tr');
        // Ambil nilai dari input keyword_search
        var filterValue = $('#keyword_search').val().toLowerCase();
        
        $rows.each(function() {
            // Ambil nama Kerusakan dan Gejala dari atribut data-nama pada baris
            // data-nama berisi gabungan nama Kerusakan dan Gejala
            var dataText = $(this).data('nama').toLowerCase();
            
            // Cek apakah teks dimulai dengan nilai filter (filter 'starts with')
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

    // Event handler untuk filtering: jalankan filter saat ada input (real-time)
    $('#keyword_search').on('keyup', function() {
        filterTablePengetahuan();
    });
});
</script>
<?php } ?>