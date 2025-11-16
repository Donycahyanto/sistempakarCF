<title>Post - SpkarCF</title>
<?php

// Awal dari blok IF otentikasi
if (!(isset($_SESSION['username']) && isset($_SESSION['password']))) {
    header('location:index.php');
    exit();
} else {
    // Awal dari blok ELSE (semua konten di dalam ini)
    ?>
<script type="text/javascript">
    function Blank_TextField_Validator()
    {
      if (text_form.nama_post.value == "")
      {
        alert("Nama Post tidak boleh kosong !");
        text_form.nama_post.focus();
        return (false);
      }
      return (true);
    }
    // Fungsi Blank_TextField_Validator_Cari() Dihapus karena menggunakan filter real-time
</script>
<?php
include "config/fungsi_alert.php";
$aksi = "modul/post/aksi_post.php";

// pastikan $keyword tidak digunakan untuk pencarian berbasis POST
$keyword = '';

switch ($_GET['act'] ?? '') {
    // Tampil post
    default:
        $offset = $_GET['offset'] ?? 0;
        //jumlah data yang ditampilkan perpage
        $limit = 15;
        if (empty($offset)) {
            $offset = 0;
        }
        
        $tampil = mysqli_query($conn,"SELECT * FROM post ORDER BY kode_post");
        $baris = mysqli_num_rows($tampil);
        
        // Form diubah: onsubmit='return false;' dan tombol 'Cari' dihapus
        echo "<form method=POST action='?module=post' name=text_form onsubmit='return false;'>
              <br><table class='table table-bordered'>
              <tr><td><input class='btn bg-olive margin' type=button name=tambah value='Tambah Post' onclick=\"window.location.href='?module=post&act=tambahpost';\">
              <input type=text name='keyword' id='keyword_search' style='margin-left: 10px;' placeholder='Ketik nama, detail, atau saran post...' class='form-control' value='' /> 
              </td></tr></table></form>";

        // CONTAINER UNTUK PESAN SUKSES/GAGAL
        echo "<div id='search_message_container'></div>";

        if($baris > 0){
        // Tabel diberi ID untuk JavaScript Filtering
        // Disesuaikan dengan permintaan user: Menghilangkan Kode Post, Menambahkan Detail dan Saran
        echo " <table class='table table-bordered' style='overflow-x:auto;' cellpadding='0' cellspacing='0' id='postTable'>
            <thead>
                <tr>
                    <th>No</th>
                    <th width='12%' style='text-align: center;'>Nama Post</th> 
                    <th style='text-align: center;'>Detail Post</th>
                    <th style='text-align: center;'>Saran Post</th>
                    <th width='18%' style='text-align: center;'>Aksi</th>
                </tr>
            </thead>
            <tbody>";

        $hasil = mysqli_query($conn,"SELECT * FROM post ORDER BY kode_post LIMIT $offset,$limit");
        $no=1+$offset;
        $counter=1;
        while ($r=mysqli_fetch_array($hasil)){
            if($counter % 2 == 0) $warna="dark"; else $warna="light";
            
            // Menggabungkan Nama Post, Detail Post, dan Saran Post untuk filter
            $data_filter = htmlspecialchars($r['nama_post'] . " " . $r['det_post'] . " " . $r['srn_post'], ENT_QUOTES); 

            echo "<tr class='".$warna."' data-filter='".$data_filter."'>
                  <td align=center>$no</td>
                  <td>".htmlspecialchars($r['nama_post'], ENT_QUOTES)."</td>
                  <td>".nl2br(strip_tags(htmlspecialchars($r['det_post'], ENT_QUOTES)))."</td>
                  <td>".nl2br(strip_tags(htmlspecialchars($r['srn_post'], ENT_QUOTES)))."</td>
                  <td align=center>
                  <a type='button' class='btn btn-success margin' href='index.php?module=post&act=editpost&id=".rawurlencode($r['kode_post'])."&offset=$offset'><i class='fa fa-pencil-square-o' aria-hidden='true'></i> Ubah </a> &nbsp;
                  <a type='button' class='btn btn-danger margin' href=\"JavaScript: confirmIt('Anda yakin akan menghapusnya ?','$aksi?module=post&act=hapus&id=".rawurlencode($r['kode_post'])."&offset=$offset','','','','u','n','Self','Self')\"><i class='fa fa-trash-o' aria-hidden='true'></i> Hapus</a>
                  </td></tr>";
            $no++;
            $counter++;
        }
        echo "</tbody></table>";
        
        // KODE PAGINATION PHP DIBERI CLASS 'paging'
        echo "<div class=paging>";

        if ($offset!=0) {
            $prevoffset=$offset-$limit;
            echo "<span class=prevnext> <a href=index.php?module=post&offset=$prevoffset>Back</a></span>";
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
                echo "<a href=index.php?module=post&offset=$newoffset>$i</a>";
                //cetak halaman
            }else{
                echo "<span class=current>".$i."</span>"; //cetak halaman tanpa link
            }
        }

        //cek halaman akhir
        if(!(($offset/$limit)+1==$halaman) && $halaman!=1){

            //jika bukan halaman terakhir maka berikan next
            $newoffset=$offset+$limit;
            echo "<span class=prevnext><a href=index.php?module=post&offset=$newoffset>Next</a>";
        }else{
            echo "<span class=disabled>Next</span>"; //cetak halaman tanpa link
        }

        echo "</div>";
        } else {
            echo "<br><b>Data Post Kosong !</b>";
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
        // Fungsi untuk filtering Post
        function filterTablePost() {
            var $rows = $('#postTable tbody tr');
            var filterValue = $('#keyword_search').val().toLowerCase().trim();
            var visibleRowCount = 0;
            var messageContainer = $('#search_message_container');
            
            $rows.each(function() {
                // Ambil semua data (Nama, Detail, Saran) dari atribut data-filter
                var dataText = $(this).data('filter').toLowerCase();
                
                // Cek apakah teks dimulai dengan nilai filter (filter 'starts with')
                if (dataText.indexOf(filterValue) === 0) { 
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
                    $('#postTable').hide();
                    
                    // Tampilkan pesan GAGAL (alert-danger)
                    messageContainer.html(
                        "<div class='alert alert-danger alert-dismissible'>" +
                        "<h4><i class='icon fa fa-ban'></i> Gagal!</h4>" +
                        "Maaf, data Post yang anda cari tidak ditemukan pada halaman ini.</div>"
                    ).show();

                } else {
                    $('#postTable').show();
                    
                    // Tampilkan pesan SUKSES (alert-success)
                    messageContainer.html(
                        "<div class='alert alert-success alert-dismissible'>" +
                        "<h4><i class='icon fa fa-check'></i> Sukses!</h4>" +
                        "Data Post yang anda cari di temukan pada halaman ini.</div>"
                    ).show();
                }
            } else {
                // Mode Default (Filter kosong)
                $('#postTable').show();
                messageContainer.empty().hide(); // Hapus pesan
                $('.paging').show(); // Tampilkan paging
            }
        }

        // Event handler untuk filtering: jalankan filter saat ada input (real-time)
        $('#keyword_search').on('keyup', function() {
            filterTablePost();
        });

        // Jalankan filter saat halaman dimuat
        filterTablePost();
    });
    </script>
    <?php
    break;

  case "tambahpost":
    echo "<form name=text_form method=POST action='$aksi?module=post&act=input' enctype='multipart/form-data' onsubmit='return Blank_TextField_Validator()'>
          <br><br><table class='table table-bordered'>
          <tr><td width=120>Nama Post</td><td><input autocomplete='off' placeholder='Masukkan nama post...' type=text class='form-control' name='nama_post' size=30></td></tr>
          <tr><td width=120>Detail Post</td><td><textarea id='editor1' rows='4' cols='50' type=text class='form-control' name='det_post'></textarea></td></tr>
          <tr><td width=120>Saran Post</td><td><textarea id='editor2' rows='4' cols='50' type=text class='form-control' name='srn_post'></textarea></td></tr>
          <tr><td width=120>Gambar Post</td><td>Upload Gambar (Ukuran Maks = 1 MB) : <input id='upload' type='file' class='form-control' name='gambar' required /></td></tr>
          <tr><td></td><td><img id='preview' src='' width=200></td></tr>
          <tr><td></td><td><input class='btn btn-success' type=submit name=submit value='Simpan' >
          <input class='btn btn-danger' type=button name=batal value='Batal' onclick=\"window.location.href='?module=post';\"></td></tr>
          </table></form>";
     break;
    
  case "editpost":
    $id = isset($_GET['id']) ? mysqli_real_escape_string($conn, $_GET['id']) : '';
    $curOffset = intval($_GET['offset'] ?? 0);
    $edit=mysqli_query($conn,"SELECT * FROM post WHERE kode_post='".mysqli_real_escape_string($conn, $id)."'");
    $r=mysqli_fetch_array($edit);
    
    $gambar = $r['gambar'] ? "../images/".$r['gambar'] : '';
    
    echo "<form name=text_form method=POST action='$aksi?module=post&act=update' enctype='multipart/form-data' onsubmit='return Blank_TextField_Validator()'>
          <input type=hidden name=id value='".htmlspecialchars($r['kode_post'], ENT_QUOTES)."'>
          <input type=hidden name=offset value='". $curOffset ."'>
          <br><br><table class='table table-bordered'>
          <tr><td width=120>Nama Post</td><td><input autocomplete='off' type=text class='form-control' name='nama_post' size=30 value=\"".htmlspecialchars($r['nama_post'], ENT_QUOTES)."\"></td></tr>
          <tr><td width=120>Detail Post</td><td><textarea id='editor1' rows='4' cols='50' class='form-control' name='det_post'>".htmlspecialchars($r['det_post'], ENT_QUOTES)."</textarea></td></tr>
          <tr><td width=120>Saran Post</td><td><textarea id='editor2' rows='4' cols='50' class='form-control' name='srn_post'>".htmlspecialchars($r['srn_post'], ENT_QUOTES)."</textarea></td></tr>
          <tr><td width=120>Gambar Post</td><td>Upload Gambar (Ukuran Maks = 1 MB) : <input id='upload' type='file' class='form-control' name='gambar' /></td></tr>
          <tr><td></td><td><img id='preview' src='".htmlspecialchars($gambar, ENT_QUOTES)."' width=200></td></tr>
          <tr><td></td><td><input class='btn btn-success' type='submit' name='submit' value='Simpan' >
          <input class='btn btn-danger' type='button' name='batal' value='Batal' onclick=\"window.location.href='?module=post';\"></td></tr>
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