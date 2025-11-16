<title>Kerusakan - SpkarCF</title>
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
    // Fungsi Blank_TextField_Validator_Cari() dihapus karena menggunakan filter real-time
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
      $baris = mysqli_num_rows($tampil);

      // FORM: onsubmit='return false;' dan tombol Cari dihapus
      echo "<form method=POST action='?module=kerusakan' name=text_form onsubmit='return false;'>
          <br><table class='table table-bordered'>
		  <tr><td><input class='btn bg-olive margin' type=button name=tambah value='Tambah Kerusakan' onclick=\"window.location.href='?module=kerusakan&act=tambahkerusakan';\">
          <input type=text name='keyword' id='keyword_search' style='margin-left: 10px;' placeholder='Ketik nama kerusakan...' class='form-control' value='' /> 
          </td></tr></table></form>";

      // CONTAINER UNTUK PESAN SUKSES/GAGAL
      echo "<div id='search_message_container'></div>";

      if ($baris > 0) {
        // Tabel diberi ID untuk JavaScript Filtering
        echo "<table class='table table-bordered' style='overflow-x=auto' cellpadding='0' cellspacing='0' id='kerusakanTable'>
          <thead>
            <tr>
              <th style='text-align: center;'>No</th>
              <th style='text-align: center;'>Nama Kerusakan</th> 
              <th style='text-align: center;'>Detail Kerusakan</th>
              <th style='text-align: center;'>Aksi</th>
            </tr>
          </thead>
          <tbody>";
        
        // QUERY TETAP MEMAKAI LIMIT UNTUK PAGINATION PHP
        $hasil = mysqli_query($conn, "SELECT * FROM kerusakan ORDER BY kode_kerusakan limit $offset,$limit");
        $no = 1 + $offset;
        $counter = 1;

        while ($r = mysqli_fetch_array($hasil)) {
          if ($counter % 2 == 0)
            $warna = "dark";
          else
            $warna = "light";
          
          // Data filter HANYA menggunakan Nama Kerusakan (Sesuai permintaan sebelumnya)
          $data_filter = htmlspecialchars($r['nama_kerusakan'], ENT_QUOTES); 

          echo "<tr class='" . $warna . "' data-filter='".$data_filter."'>
             <td align=center>$no</td>
             <td>" . htmlspecialchars($r['nama_kerusakan'], ENT_QUOTES) . "</td>
             <td>" . htmlspecialchars(substr($r['det_kerusakan'], 0, 100), ENT_QUOTES) . "...</td>
             <td align=center>
             <a type='button' class='btn btn-success margin' href='index.php?module=kerusakan&act=editkerusakan&id=" . rawurlencode($r['kode_kerusakan']) . "'><i class='fa fa-pencil-square-o' aria-hidden='true'></i> Ubah </a> &nbsp;
              <a type='button' class='btn btn-danger margin' href=\"JavaScript: confirmIt('Anda yakin akan menghapusnya ?','$aksi?module=kerusakan&act=hapus&id=" . rawurlencode($r['kode_kerusakan']) . "','','','','u','n','Self','Self')\"><i class='fa fa-trash-o' aria-hidden='true'></i> Hapus</a>
             </td></tr>";
          $no++;
          $counter++;
        }
        echo "</tbody></table>";
        
        // KODE PAGINATION PHP (DI PERTAHANKAN)
        echo "<div class=paging>";

        if ($offset != 0) {
          $prevoffset = $offset - $limit;
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
        echo "<br><b>Data Kerusakan Kosong !</b>";
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
          // Fungsi untuk filtering Kerusakan
          function filterTableKerusakan() {
              var $rows = $('#kerusakanTable tbody tr');
              var filterValue = $('#keyword_search').val().toLowerCase().trim();
              var visibleRowCount = 0;
              var messageContainer = $('#search_message_container');
              
              $rows.each(function() {
                  // Ambil data dari atribut data-filter (Yaitu HANYA Nama Kerusakan)
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
                      $('#kerusakanTable').hide();
                      
                      // Tampilkan pesan GAGAL (alert-danger)
                      messageContainer.html(
                          "<div class='alert alert-danger alert-dismissible'>" +
                          "<h4><i class='icon fa fa-ban'></i> Gagal!</h4>" +
                          "Maaf, Kerusakan yang anda cari tidak ditemukan pada halaman ini.</div>"
                      ).show();

                  } else {
                      $('#kerusakanTable').show();
                      
                      // Tampilkan pesan SUKSES (alert-success)
                      messageContainer.html(
                          "<div class='alert alert-success alert-dismissible'>" +
                          "<h4><i class='icon fa fa-check'></i> Sukses!</h4>" +
                          "Kerusakan yang anda cari di temukan pada halaman ini.</div>"
                      ).show();
                  }
              } else {
                  // Mode Default (Filter kosong)
                  $('#kerusakanTable').show();
                  messageContainer.empty().hide(); // Hapus pesan
                  $('.paging').show(); // Tampilkan paging
              }
          }

          // Event handler untuk filtering: jalankan filter saat ada input (real-time)
          $('#keyword_search').on('keyup', function() {
              filterTableKerusakan();
          });

          // Jalankan filter saat halaman dimuat
          filterTableKerusakan();
      });
      </script>
      <?php
      break;

    case "tambahkerusakan":
      echo "<form name=text_form method=POST action='$aksi?module=kerusakan&act=input' onsubmit='return Blank_TextField_Validator()'>
          <br><br><table class='table table-bordered'>
          <tr><td width=120>Nama Kerusakan</td><td><input autocomplete='off' placeholder='Masukkan kerusakan baru...' type=text class='form-control' name='nama_kerusakan' size=30></td></tr>
          <tr><td width=120>Detail Kerusakan</td><td><textarea rows='4' cols='50' type=text class='form-control' name='det_kerusakan'></textarea></td></tr>
          <tr><td width=120>Saran Kerusakan</td><td><textarea rows='4' cols='50' type=text class='form-control' name='srn_kerusakan'></textarea></td></tr>
          <tr><td width=120>Gambar Post</td><td>Upload Gambar (Ukuran Maks = 1 MB) : <input id='upload' type='file' class='form-control' name='gambar' required /></td></tr>
          <tr><td></td><td><img id='preview' src='' width=200></td></tr>
          <tr><td></td><td><input class='btn btn-success' type=submit name=submit value='Simpan' >
          <input class='btn btn-danger' type=button name=batal value='Batal' onclick=\"window.location.href='?module=kerusakan';\"></td></tr>
          </table></form>";
      break;

    case "editkerusakan":
      $id = isset($_GET['id']) ? mysqli_real_escape_string($conn, $_GET['id']) : '';
      $edit = mysqli_query($conn, "SELECT * FROM kerusakan WHERE kode_kerusakan='$id'");
      $r = mysqli_fetch_array($edit);
      $gambar = 'gambar/' . $r['gambar'];

      echo "<form name=text_form method=POST action='$aksi?module=kerusakan&act=update' onsubmit='return Blank_TextField_Validator()' enctype='multipart/form-data'>
          <input type=hidden name=id value='" . htmlspecialchars($r['kode_kerusakan'], ENT_QUOTES) . "'>
          <br><br><table class='table table-bordered'>
		  <tr><td width=120>Nama Kerusakan</td><td><input autocomplete='off' type=text class='form-control' name='nama_kerusakan' size=30 value=\"" . htmlspecialchars($r['nama_kerusakan'], ENT_QUOTES) . "\"></td></tr>
		  <tr><td width=120>Detail Kerusakan</td><td><textarea rows='4' cols='50' type=text class='form-control' name='det_kerusakan'>" . htmlspecialchars($r['det_kerusakan'], ENT_QUOTES) . "</textarea></td></tr>
		  <tr><td width=120>Saran Kerusakan</td><td><textarea rows='4' cols='50' type=text class='form-control' name='srn_kerusakan'>" . htmlspecialchars($r['srn_kerusakan'], ENT_QUOTES) . "</textarea></td></tr>
          <tr><td width=120>Gambar Post</td><td>Upload Gambar (Ukuran Maks = 1 MB) : <input id='upload' type='file' class='form-control' name='gambar' /></td></tr>
          <tr><td></td><td><img id='preview' src='$gambar' width=200></td></tr>          
          <tr><td></td><td><input class='btn btn-success' type=submit name=submit value='Simpan' >
		  <input class='btn btn-danger' type=button name=batal value='Batal' onclick=\"window.location.href='?module=kerusakan';\"></td></tr>
          </table></form>";
      break;
  }
  ?>
  <script>
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
  </script>
<?php } ?>