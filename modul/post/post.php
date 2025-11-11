<title>Post - Chirexs 1.0</title>
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
    // Fungsi Blank_TextField_Validator_Cari() dihapus karena menggunakan filter real-time
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
              <br><br><table class='table table-bordered'>
              <tr><td><input class='btn bg-olive margin' type=button name=tambah value='Tambah Post' onclick=\"window.location.href='?module=post&act=tambahpost';\">".
              // Input pencarian diberi ID dan nilai value dihapus
              "<input type=text name='keyword' id='keyword_search' style='margin-left: 10px;' placeholder='Ketik dan tekan cari...' class='form-control' value='' />".
              // Tombol 'Cari' (<input type=submit value='   Cari   ' name=Go>) dihapus
              "</td> </tr>
              </table></form>";

        // Blok pencarian berbasis server (if ($_POST['Go'] ?? '') { ... }) telah dihapus

        if ($baris > 0) {
            // Tabel diberi ID untuk JavaScript Filtering
            echo " <table class='table table-bordered' style='overflow-x=auto' cellpadding='0' cellspacing='0' id='postTable'>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Post</th>
                    <th>Detail Post</th>
                    <th>Saran Post</th>
                    <th width='21%'>Aksi</th>
                </tr>
            </thead>
            <tbody>";
            
            $hasil = mysqli_query($conn,"SELECT * FROM post ORDER BY kode_post LIMIT $offset,$limit");
            $no = 1 + $offset;
            $counter = 1;
            while ($r = mysqli_fetch_array($hasil)) {
                if ($counter % 2 == 0)
                    $warna = "dark";
                else
                    $warna = "light";
                
                // data-nama DITAMBAHKAN PADA BARIS untuk filtering JavaScript
                $deleteUrl = htmlspecialchars($aksi . "?module=post&act=hapus&id=" . urlencode($r['kode_post']) . "&offset=" . $offset, ENT_QUOTES);

                echo "<tr class='" . $warna . "' data-nama='" . htmlspecialchars($r['nama_post'], ENT_QUOTES) . "'>
                    <td align=center>$no</td>
                    <td>" . htmlspecialchars($r['nama_post'], ENT_QUOTES) . "</td>
                    <td>" . substr(strip_tags($r['det_post']), 0, 50) . "...</td>
                    <td>" . substr(strip_tags($r['srn_post']), 0, 50) . "...</td>
                    <td align=center>
                        <a class='btn btn-success margin' href='?module=post&act=editpost&id=".urlencode($r['kode_post'])."&offset=".$offset."'><i class='fa fa-pencil-square-o' aria-hidden='true'></i> Ubah </a> &nbsp;
                        <a class='btn btn-danger margin' href=\"JavaScript: confirmIt('Anda yakin akan menghapusnya ?','" . $deleteUrl . "','','','','u','n','Self','Self')\">
                        <i class='fa fa-trash-o' aria-hidden='true'></i> Hapus</a>
                    </td></tr>";
                $no++;
                $counter++;
            }
            echo "</tbody></table>";

            // Pagination Code (Tidak diubah)
            echo "<div class=paging>";
            if ($offset != 0) {
                $prevoffset = $offset - $limit;
                echo "<span class=prevnext> <a href=index.php?module=post&offset=$prevoffset>Back</a></span>";
            } else {
                echo "<span class=disabled>Back</span>"; 
            }
            $halaman = intval($baris / $limit);
            if ($baris % $limit) $halaman++;
            for ($i = 1; $i <= $halaman; $i++) {
                $newoffset = $limit * ($i - 1);
                if ($offset != $newoffset) {
                    echo "<a href=index.php?module=post&offset=$newoffset>$i</a>";
                } else {
                    echo "<span class=current>" . $i . "</span>";
                }
            }
            if (!(($offset / $limit) + 1 == $halaman) && $halaman != 1) {
                $newoffset = $offset + $limit;
                echo "<span class=prevnext><a href=index.php?module=post&offset=$newoffset>Next</a>";
            } else {
                echo "<span class=disabled>Next</span>";
            }
            echo "</div>";
            // End Pagination Code
            
        } else {
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
            // Fungsi untuk filtering Post
            function filterTablePost() {
                var $rows = $('#postTable tbody tr');
                // Ambil nilai dari input keyword_search
                var filterValue = $('#keyword_search').val().toLowerCase();
                
                $rows.each(function() {
                    // Ambil nama post dari atribut data-nama pada baris
                    var namaPostText = $(this).data('nama').toLowerCase();
                    
                    // Cek apakah teks post dimulai dengan nilai filter (filter 'starts with')
                    if (namaPostText.indexOf(filterValue) === 0) {
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
                filterTablePost();
            });
        });
        </script>
        <?php
        break;

    case "tambahpost":
        echo "<form name=text_form method=POST action='$aksi?module=post&act=input' onsubmit='return Blank_TextField_Validator()' enctype='multipart/form-data'>
            <br><br><table class='table table-bordered'>
            <tr><td width=120>Nama Post</td><td><input autocomplete='off' type=text placeholder='Masukkan nama post baru...' class='form-control' name='nama_post' size=30></td></tr>
            <tr><td width=120>Detail Post</td><td><textarea id='editor1' rows='4' cols='50' class='form-control' name='det_post' type=text placeholder='Masukkan detail post baru...'></textarea></td></tr>
            <tr><td width=120>Saran Post</td><td><textarea id='editor2' rows='4' cols='50' class='form-control' name='srn_post' type=text placeholder='Masukkan saran post baru...'></textarea></td></tr>
            <tr><td width=120>Gambar Post</td><td>Upload Gambar (Ukuran Maks = 1 MB) : <input type='file' class='form-control' name='gambar' required /></td></tr>		  
            <tr><td></td><td><input class='btn btn-success' type=submit name=submit value='Simpan' >
            <input class='btn btn-danger' type=button name=batal value='Batal' onclick=\"window.location.href='?module=post';\"></td></tr>
            </table></form>";
        break;

    case "editpost":
        $edit = mysqli_query($conn, "SELECT * FROM post WHERE id_post='".mysqli_real_escape_string($conn, $_GET['id'])."'");
        $r = mysqli_fetch_array($edit);
        if ($r['gambar']) {
            $gambar = 'gambar/post/' . $r['gambar'];
        } else {
            $gambar = 'gambar/noimage.png';
        }

        echo "<form name=text_form method=POST action='$aksi?module=post&act=update' onsubmit='return Blank_TextField_Validator()' enctype='multipart/form-data'>
            <input type=hidden name=id value='".htmlspecialchars($r['id_post'], ENT_QUOTES)."'>
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