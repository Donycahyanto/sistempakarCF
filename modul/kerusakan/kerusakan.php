<title>Kerusakan - Chirexs 1.0</title>
<?php


if (!(isset($_SESSION['username']) && isset($_SESSION['password']))) {
  header('location:index.php');
  exit();
} else {
  ?>
  <script type="text/javascript">
    function Blank_TextField_Validator()
    {
      if (text_form.nama_kerusakan.value == "")
      {
        alert("Nama Kerusakan tidak boleh kosong !");
        text_form.nama_kerusakan.focus();
        return (false);
      }
      return (true);
    }
    // Fungsi Blank_TextField_Validator_Cari() telah dihapus karena menggunakan filter real-time
  </script>
  <?php

  include "config/fungsi_alert.php";
  $aksi = "modul/kerusakan/aksi_kerusakan.php";
  switch ($_GET['act'] ?? '') {
    // Tampil kerusakan
    default:
      $offset = $_GET['offset'] ?? 0;
      //jumlah data yang ditampilkan perpage
      $limit = 15;
      if (empty($offset)) {
        $offset = 0;
      }
      $tampil = mysqli_query($conn,"SELECT * FROM kerusakan ORDER BY kode_kerusakan");
      // Form diubah agar tidak reload halaman (onsubmit='return false;')
      echo "<form method=POST action='?module=kerusakan' name=text_form onsubmit='return false;'>
          <br><br><table class='table table-bordered'>
          <tr><td><input class='btn bg-olive margin' type=button name=tambah value='Tambah Kerusakan' onclick=\"window.location.href='kerusakan/tambahkerusakan';\">".
          // ID ditambahkan pada input pencarian (kotak filter)
          "<input type=text name='keyword' id='keyword_search' style='margin-left: 10px;' placeholder='Ketik dan tekan cari...' class='form-control' value='' />".
          // *** Tombol 'Cari' dihapus dari sini ***
          "</td> </tr>
          </table></form>";
      $baris = mysqli_num_rows($tampil);
      
      // Blok tampilan tabel utama
      if ($baris > 0) {
        // ID TABLE DITAMBAHKAN
        echo" <table class='table table-bordered' style='overflow-x=auto' cellpadding='0' cellspacing='0' id='kerusakanTable'>
          <thead>
            <tr>
              <th>No</th>
              <th width='20%'>Nama Kerusakan</th>
              <th>Detail Kerusakan</th>
              <th>Saran Kerusakan</th>
              <th>Aksi</th>
            </tr>
          </thead>
		  <tbody>
		  ";
        $hasil = mysqli_query($conn,"SELECT * FROM kerusakan ORDER BY kode_kerusakan limit $offset,$limit");
        $no = 1;
        $no = 1 + $offset;
        $counter = 1;
        while ($r = mysqli_fetch_array($hasil)) {
          if ($counter % 2 == 0)
            $warna = "dark";
          else
            $warna = "light";
          // data-nama DITAMBAHKAN PADA BARIS untuk filtering
          echo "<tr class='" . $warna . "' data-nama='" . htmlspecialchars($r['nama_kerusakan']) . "'>
			 <td align=center>$no</td>
			 <td>$r[nama_kerusakan]</td>
			 <td>$r[det_kerusakan]</td>
			 <td>$r[srn_kerusakan]</td>
			 <td align=center>
			 <a type='button' class='btn btn-block btn-success' href=kerusakan/editkerusakan/$r[kode_kerusakan]><i class='fa fa-pencil-square-o' aria-hidden='true'></i> Ubah </a> &nbsp;
	          <a type='button' class='btn btn-block btn-danger' href=\"JavaScript: confirmIt('Anda yakin akan menghapusnya ?','$aksi?module=kerusakan&act=hapus&id=$r[kode_kerusakan]','','','','u','n','Self','Self')\" onMouseOver=\"self.status=''; return true\" onMouseOut=\"self.status=''; return true\">
			  <i class='fa fa-trash-o' aria-hidden='true'></i> Hapus</a>
             </td></tr>";
          $no++;
          $counter++;
        }
        echo "</tbody></table>";
        echo "<div class=paging>";

        if ($offset != 0) {
          $prevoffset = $offset - 10;
          echo "<span class=prevnext> <a href=index.php?module=kerusakan&offset=$prevoffset>Back</a></span>";
        } else {
          echo "<span class=disabled>Back</span>"; //cetak halaman tanpa link
        }
        //hitung jumlah halaman
        $halaman = intval($baris / $limit); //Pembulatan

        if ($baris % $limit) {
          $halaman++;
        }
        for ($i = 1; $i <= $halaman; $i++) {
          $newoffset = $limit * ($i - 1);
          if ($offset != $newoffset) {
            echo "<a href=index.php?module=kerusakan&offset=$newoffset>$i</a>";
            //cetak halaman
          } else {
            echo "<span class=current>" . $i . "</span>"; //cetak halaman tanpa link
          }
        }

        //cek halaman akhir
        if (!(($offset / $limit) + 1 == $halaman) && $halaman != 1) {

          //jika bukan halaman terakhir maka berikan next
          $newoffset = $offset + $limit;
          echo "<span class=prevnext><a href=index.php?module=kerusakan&offset=$newoffset>Next</a>";
        } else {
          echo "<span class=disabled>Next</span>"; //cetak halaman tanpa link
        }

        echo "</div>";
      } else {
        echo "<br><b>Data Kosong !</b>";
      }
      break;

    case "tambahkerusakan":
      echo "<form name=text_form method=POST action='$aksi?module=kerusakan&act=input' onsubmit='return Blank_TextField_Validator()' enctype='multipart/form-data'>
          <br><br><table class='table table-bordered'>
		  <tr><td width=120>Nama Kerusakan</td><td><input autocomplete='off' type=text placeholder='Masukkan kerusakan baru...' class='form-control' name='nama_kerusakan' size=30></td></tr>
		  <tr><td width=120>Detail Kerusakan</td><td> <textarea rows='4' cols='50' class='form-control' name='det_kerusakan'type=text placeholder='Masukkan detail kerusakan baru...'></textarea></td></tr>
		  <tr><td width=120>Saran Kerusakan</td><td><textarea rows='4' cols='50' class='form-control' name='srn_kerusakan'type=text placeholder='Masukkan saran kerusakan baru...'></textarea></td></tr>
          <tr><td width=120>Gambar Post</td><td>Upload Gambar (Ukuran Maks = 1 MB) : <input type='file' class='form-control' name='gambar' required /></td></tr>		  
          <tr><td></td><td><input class='btn btn-success' type=submit name=submit value='Simpan' >
		  <input class='btn btn-danger' type=button name=batal value='Batal' onclick=\"window.location.href='?module=kerusakan';\"></td></tr>
          </table></form>";
      break;

    case "editkerusakan":
      $edit = mysqli_query($conn,"SELECT * FROM kerusakan WHERE kode_kerusakan='$_GET[id]'");
      $r = mysqli_fetch_array($edit);
      if ($r['gambar']) {
        $gambar = 'gambar/kerusakan/' . $r['gambar'];
      } else {
        $gambar = 'gambar/noimage.png';
      }

      echo "<form name=text_form method=POST action='$aksi?module=kerusakan&act=update' onsubmit='return Blank_TextField_Validator()' enctype='multipart/form-data'>
          <input type=hidden name=id value='$r[kode_kerusakan]'>
          <br><br><table class='table table-bordered'>
		  <tr><td width=120>Nama Kerusakan</td><td><input autocomplete='off' type=text class='form-control' name='nama_kerusakan' size=30 value=\"$r[nama_kerusakan]\"></td></tr>
		  <tr><td width=120>Detail Kerusakan</td><td><textarea rows='4' cols='50' type=text class='form-control' name='det_kerusakan'>$r[det_kerusakan]</textarea></td></tr>
		  <tr><td width=120>Saran Kerusakan</td><td><textarea rows='4' cols='50' type=text class='form-control' name='srn_kerusakan'>$r[srn_kerusakan]</textarea></td></tr>
          <tr><td width=120>Gambar Post</td><td>Upload Gambar (Ukuran Maks = 1 MB) : <input id='upload' type='file' class='form-control' name='gambar' required /></td></tr>
          <tr><td></td><td><img id='preview' src='$gambar' width=200></td></tr>          
          <tr><td></td><td><input class='btn btn-success' type=submit name=submit value='Simpan' >
		  <input class='btn btn-danger' type=button name=batal value='Batal' onclick=\"window.location.href='?module=kerusakan';\"></td></tr>
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
    // Fungsi untuk filtering Kerusakan
    function filterTableKerusakan() {
        var $rows = $('#kerusakanTable tbody tr');
        // Ambil nilai dari input keyword_search
        var filterValue = $('#keyword_search').val().toLowerCase();
        
        $rows.each(function() {
            // Ambil nama kerusakan dari atribut data-nama pada baris
            var namaKerusakanText = $(this).data('nama').toLowerCase();
            
            // Cek apakah teks kerusakan dimulai dengan nilai filter (filter 'starts with')
            if (namaKerusakanText.indexOf(filterValue) === 0) {
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
    // Filter sekarang sepenuhnya bergantung pada event keyup (ketika mengetik)
    $('#keyword_search').on('keyup', function() {
        filterTableKerusakan();
    });
    
    // Kode untuk tombol 'Cari' ('#search_button') telah dihapus di PHP dan di sini
    
    function readURL(input) {

      if (input.files &&
              input.files[0]) {
        var reader = new FileReader();
        reader.onload = function (e) {
          $('#preview').attr('src', e.target.result);
        }

        reader.readAsDataURL(input.files[0]);
      }
    }

    $("#upload").change(function () {
      readURL(this);
    });
});
</script>
<?php
} // Penutup blok else utama
?>