<title>Diagnosa - Chirexs 1.0</title>
<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include('config/koneksi.php');
$act = isset($_GET['act']) ? $_GET['act'] : '';
switch ($act) {

  default:
    if (isset($_POST['submit'])) {
      // Validasi jumlah gejala yang dipilih
      $selectedSymptoms = 0;
      foreach ($_POST['kondisi'] as $kondisi) {
        if (strlen($kondisi) > 1 && $kondisi != '0') {
          $selectedSymptoms++;
        }
      }
      
      if ($selectedSymptoms > 9) {
        echo "
        <script>
          alert('Terlalu banyak gejala dipilih!\\\\nAnda telah memilih ' + $selectedSymptoms + ' gejala.\\\\nMaksimal yang diperbolehkan adalah 9 gejala.\\\\nSilakan kurangi pilihan gejala Anda.');
          window.history.back();
        </script>";
        break;
      }
      
      if ($selectedSymptoms < 4) {
        echo "
        <script>
          alert('Silakan pilih minimal 4 gejala untuk melakukan diagnosa.\\\\nAnda baru memilih ' + $selectedSymptoms + ' gejala.');
          window.history.back();
        </script>";
        break;
      }

      $arcolor = array('#ffffff', '#cc66ff', '#019AFF', '#00CBFD', '#00FEFE', '#A4F804', '#FFFC00', '#FDCD01', '#FD9A01', '#FB6700');
      date_default_timezone_set("Asia/Jakarta");
      $inptanggal = date('Y-m-d H:i:s');

      $arbobot = array('0', '1', '0.8', '0.6', '0.4', '-0.2', '-0.4', '-0.6', '-0.8', '-1');
      $argejala = array();

      for ($i = 0; $i < count($_POST['kondisi']); $i++) {
        $arkondisi = explode("_", $_POST['kondisi'][$i]);
        if (strlen($_POST['kondisi'][$i]) > 1 && $_POST['kondisi'][$i] != '0') {
          $argejala += array($arkondisi[0] => $arkondisi[1]);
        }
      }

      $sqlkondisi = mysqli_query($conn, "SELECT * FROM kondisi order by id+0");
      while ($rkondisi = mysqli_fetch_array($sqlkondisi)) {
        $arkondisitext[$rkondisi['id']] = $rkondisi['kondisi'];
      }

      $sqlpkt = mysqli_query($conn, "SELECT * FROM kerusakan order by kode_kerusakan+0");
      while ($rpkt = mysqli_fetch_array($sqlpkt)) {
        $arpkt[$rpkt['kode_kerusakan']] = $rpkt['nama_kerusakan'];
        $ardpkt[$rpkt['kode_kerusakan']] = $rpkt['det_kerusakan'];
        $arspkt[$rpkt['kode_kerusakan']] = $rpkt['srn_kerusakan'];
        $argpkt[$rpkt['kode_kerusakan']] = $rpkt['gambar'];
      }

      // -------- perhitungan certainty factor (CF) ---------
      // --------------------- START ------------------------
      $sqlkerusakan = mysqli_query($conn, "SELECT * FROM kerusakan order by kode_kerusakan");
      $arkerusakan = array();
      while ($rkerusakan = mysqli_fetch_array($sqlkerusakan)) {
        $cftotal_temp = 0;
        $cf = 0;
        $sqlgejala = mysqli_query($conn, "SELECT * FROM basis_pengetahuan where kode_kerusakan=$rkerusakan[kode_kerusakan]");
        $cflama = 0;
        while ($rgejala = mysqli_fetch_array($sqlgejala)) {
          $arkondisi = explode("_", $_POST['kondisi'][0]);
          $gejala = $arkondisi[0];

          for ($i = 0; $i < count($_POST['kondisi']); $i++) {
            $arkondisi = explode("_", $_POST['kondisi'][$i]);
            $gejala = $arkondisi[0];
            if ($rgejala['kode_gejala'] == $gejala) {
              $cf = ($rgejala['mb'] - $rgejala['md']) * $arbobot[$arkondisi[1]];
              if (($cf >= 0) && ($cf * $cflama >= 0)) {
                $cflama = $cflama + ($cf * (1 - $cflama));
              }
              if ($cf * $cflama < 0) {
                $cflama = ($cflama + $cf) / (1 - min(abs($cflama), abs($cf)));
              }
              if (($cf < 0) && ($cf * $cflama >= 0)) {
                $cflama = $cflama + ($cf * (1 + $cflama));
              }
            }
          }
        }
        if ($cflama > 0) {
          // gunakan kode dari $rkerusakan sebagai key
          $arkerusakan[$rkerusakan['kode_kerusakan']] = number_format($cflama, 4);
        }
      }
      
      arsort($arkerusakan);

      $inpgejala = serialize($argejala);
      $inpkerusakan = serialize($arkerusakan);

      // Initialize variables to prevent undefined variable warnings
      $idpkt1 = array();
      $vlpkt1 = array();
      $np1 = 0;
      
      // Only process if there are diagnosis results
      if (!empty($arkerusakan)) {
        foreach ($arkerusakan as $key1 => $value1) {
          $np1++;
          $idpkt1[$np1] = $key1;
          $vlpkt1[$np1] = $value1;
        }

        // Check if we have at least one result before inserting
        if ($np1 > 0 && isset($idpkt1[1]) && isset($vlpkt1[1])) {
          mysqli_query($conn, "INSERT INTO hasil(
                      tanggal,
                      gejala,
                      kerusakan,
                      hasil_id,
                      hasil_nilai
                      ) 
                VALUES(
                    '$inptanggal',
                    '$inpgejala',
                    '$inpkerusakan',
                    '$idpkt1[1]',
                    '$vlpkt1[1]'
                    )");
        }
      }

      // --------------------- END -------------------------

      echo "<div class='content'>
      <h2 class='text text-primary'>Hasil Diagnosis &nbsp;&nbsp;<button id='print' onClick='window.print();' data-toggle='tooltip' data-placement='right' title='Klik tombol ini untuk mencetak hasil diagnosa'><i class='fa fa-print'></i> Cetak</button> </h2>
              <hr>
              <table class='table table-bordered table-striped diagnosa'> 
          <th width=8%>No</th>
          <th width=10%>Kode</th>
          <th>Gejala yang dialami (keluhan)</th>
          <th width=20%>Pilihan</th>
          </tr>";
      $ig = 0;
      foreach ($argejala as $key => $value) {
        $kondisi = $value;
        $ig++;
        $gejala = $key;
        $sql4 = mysqli_query($conn, "SELECT * FROM gejala where kode_gejala = '$key'");
        $r4 = mysqli_fetch_array($sql4);
        echo '<tr><td>' . $ig . '</td>';
        echo '<td>G' . str_pad($r4['kode_gejala'], 3, '0', STR_PAD_LEFT) . '</td>';
        echo '<td><span class="hasil text text-primary">' . $r4['nama_gejala'] . "</span></td>";
        echo '<td><span class="kondisipilih" style="color:' . $arcolor[$kondisi] . '">' . $arkondisitext[$kondisi] . "</span></td></tr>";
      }
      
      // Check if there are diagnosis results to display
      if (!empty($arkerusakan)) {
        $np = 0;
        $idpkt = array();
        $nmpkt = array();
        $vlpkt = array();
        
        // iterasi hasil diagnosa yang tersimpan di $arkerusakan
        foreach ($arkerusakan as $key => $value) {
          $np++;
          $idpkt[$np] = $key;
          $nmpkt[$np] = $arpkt[$key];
          $vlpkt[$np] = $value;
        }
        
        if ($argpkt[$idpkt[1]]) {
          $gambar = 'gambar/kerusakan/' . $argpkt[$idpkt[1]];
        } else {
          $gambar = 'gambar/noimage.png';
        }
        
        echo "</table><div class='well well-small'><img class='card-img-top img-bordered-sm' style='float:right; margin-left:15px;' src='" . $gambar . "' height=200><h3>Hasil Diagnosa</h3>";
        echo "<div class='callout callout-default'>Jenis kerusakan yang diderita adalah <b><h3 class='text text-success'>" . $nmpkt[1] . "</b> / " . round($vlpkt[1], 2) . " % (" . $vlpkt[1] . ")<br></h3>";
        echo "</div></div><div class='box box-info box-solid'><div class='box-header with-border'><h3 class='box-title'>Detail</h3></div><div class='box-body'><h4>";
        echo $ardpkt[$idpkt[1]];
        echo "</h4></div></div>
            <div class='box box-warning box-solid'><div class='box-header with-border'><h3 class='box-title'>Saran</h3></div><div class='box-body'><h4>";
        echo $arspkt[$idpkt[1]];
        echo "</h4></div></div>
            <div class='box box-danger box-solid'><div class='box-header with-border'><h3 class='box-title'>Kemungkinan lain:</h3></div><div class='box-body'><h4>";
        for ($ipl = 2; $ipl <= count($idpkt); $ipl++) {
          if (isset($nmpkt[$ipl]) && isset($vlpkt[$ipl])) {
            echo " <h4><i class='fa fa-caret-square-o-right'></i> " . $nmpkt[$ipl] . "</b> / " . round($vlpkt[$ipl], 2) . " % (" . $vlpkt[$ipl] . ")<br></h4>";
          }
        }
        echo "</div></div>";
      } else {
        echo "</table><div class='alert alert-warning'><h3>Tidak ada hasil diagnosa</h3><p>Gejala yang dipilih tidak cukup untuk menentukan jenis kerusakan. Silakan coba lagi dengan memilih lebih banyak gejala.</p></div>";
      }
      
      echo "</div>";
    } else {
      echo "
     <h2 class='text text-primary'>Diagnosa Kerusakan</h2>  <hr>
     <div class='alert alert-success alert-dismissible'>
                <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>
                <h4><i class='icon fa fa-exclamation-triangle'></i>Perhatian !</h4>
                Silahkan memilih gejala sesuai dengan kondisi komputer anda, anda dapat memilih kepastian kondisi komputer dari pasti tidak sampai pasti. <strong>Minimal 4 gejala dan maksimal 9 gejala yang dapat dipilih</strong>. Jika sudah tekan tombol proses (<i class='fa fa-search-plus'></i>) di bawah untuk melihat hasil.
              </div>
        <form name='text_form' method='POST' action='diagnosa' onsubmit='return validateSymptoms()'>
           <table class='table table-bordered table-striped konsultasi'><tbody class='pilihkondisi'>
           <tr><th>No</th><th>Kode</th><th>Gejala</th><th width='20%'>Pilih Kondisi</th></tr>";

      $sql3 = mysqli_query($conn, "SELECT * FROM gejala order by kode_gejala");
      $i = 0;
      while ($r3 = mysqli_fetch_array($sql3)) {
        $i++;
        echo "<tr><td class=opsi>$i</td>";
        echo "<td class=opsi>G" . str_pad($r3['kode_gejala'], 3, '0', STR_PAD_LEFT) . "</td>";
        echo "<td class=gejala>{$r3['nama_gejala']}</td>";
        echo '<td class="opsi"><select name="kondisi[]" id="sl' . $i . '" class="opsikondisi"/><option data-id="0" value="0">Pilih jika sesuai</option>';
        $s = "select * from kondisi order by id";
        $q = mysqli_query($conn, $s) or die($s);
        while ($rw = mysqli_fetch_array($q)) {
          ?>
          <option data-id="<?php echo $rw['id']; ?>" value="<?php echo $r3['kode_gejala'] . '_' . $rw['id']; ?>"><?php echo $rw['kondisi']; ?></option>
          <?php
        }
        echo '</select></td>';
        ?>
        <script type="text/javascript">
          $(document).ready(function () {
            var arcolor = new Array('#ffffff', '#cc66ff', '#019AFF', '#00CBFD', '#00FEFE', '#A4F804', '#FFFC00', '#FDCD01', '#FD9A01', '#FB6700');
            setColor();
            $('.pilihkondisi').on('change', 'tr td select#sl<?php echo $i; ?>', function () {
              setColor();
            });
            function setColor()
            {
              var selectedItem = $('tr td select#sl<?php echo $i; ?> :selected');
              var color = arcolor[selectedItem.data("id")];
              $('tr td select#sl<?php echo $i; ?>.opsikondisi').css('background-color', color);
            }
          });
        </script>
        <?php
        echo "</tr>";
      }
      echo "
		  <input class='float' type=submit data-toggle='tooltip' data-placement='top' title='Klik disini untuk melihat hasil diagnosa' name=submit value='&#xf00e;' style='font-family:Arial, FontAwesome'>
          </tbody></table></form>
          
          <script>
          function validateSymptoms() {
            let selectedCount = 0;
            $('select[name=\"kondisi[]\"]').each(function() {
              if ($(this).val() !== '0') {
                selectedCount++;
              }
            });
            
            if (selectedCount < 4) {
              alert('Silakan pilih minimal 4 gejala untuk melakukan diagnosa.\\ Anda baru memilih ' + selectedCount + ' gejala.');
              return false;
            }
            
            if (selectedCount > 9) {
              alert('Anda telah memilih ' + selectedCount + ' gejala.\\ Maksimal yang diperbolehkan adalah 9 gejala.\\ Silakan kurangi pilihan gejala Anda.');
              return false;
            }
            
            return true;
          }
          </script>";
    }
    break;
}
?>