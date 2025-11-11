<title>Riwayat - Chirexs 1.0</title>
<h2 class='text text-primary'>Riwayat Konsultasi</h2>
<hr>
<?php
include "config/fungsi_alert.php";

// Handle delete action
if (isset($_GET['act']) && $_GET['act'] == 'hapus' && isset($_GET['id'])) {
    $id_hasil = $_GET['id'];
    $query = mysqli_query($conn, "DELETE FROM hasil WHERE id_hasil = '$id_hasil'");
    
    if ($query) {
        echo "<script>alert('Data riwayat berhasil dihapus');</script>";
        echo "<script>window.location.href = 'index.php?module=riwayat';</script>";
        exit;
    } else {
        echo "<script>alert('Gagal menghapus data riwayat');</script>";
        echo "<script>window.location.href = 'index.php?module=riwayat';</script>";
        exit;
    }
}

switch ($_GET['act'] ?? '') {
// Tampil hasil
    default:
        $offset = $_GET['offset'] ?? 0;
//jumlah data yang ditampilkan perpage
        $limit = 15;
        if (empty($offset)) {
            $offset = 0;
        }

        $sqlgjl = mysqli_query($conn,"SELECT * FROM gejala order by kode_gejala+0");
        while ($rgjl = mysqli_fetch_array($sqlgjl)) {
            $argjl[$rgjl['kode_gejala']] = $rgjl['nama_gejala'];
        }

        $sqlpkt = mysqli_query($conn,"SELECT * FROM kerusakan order by kode_kerusakan+0");
        $arpkt = array(); // initialize to avoid undefined variable
        $ardpkt = array();
        $arspkt = array();
        while ($rpkt = mysqli_fetch_array($sqlpkt)) {
            $arpkt[$rpkt['kode_kerusakan']] = $rpkt['nama_kerusakan'];
            $ardpkt[$rpkt['kode_kerusakan']] = $rpkt['det_kerusakan'];
            $arspkt[$rpkt['kode_kerusakan']] = $rpkt['srn_kerusakan'];
        }

        $tampil = mysqli_query($conn,"SELECT * FROM hasil ORDER BY id_hasil");
        $baris = mysqli_num_rows($tampil);
        if ($baris > 0) {
            echo"<div class='row'><div class='col-md-8'><table class='table table-bordered table-striped riwayat' id='riwayatTable' style='overflow-x=auto' cellpadding='0' cellspacing='0'>
          <thead>
            <tr>
              <th>No</th>
              <th>Tanggal</th>
              <th>Kerusakan
                <input type='text' class='table-filter' data-column='2' placeholder='Cari kerusakan...' style='width: 100%; margin-top: 5px; padding: 6px; font-size: 12px; border: 1px solid #ccc; background-color: white; color: #333; border-radius: 3px;'>
              </th>
              <th nowrap>Nilai CF</th>
              <th width='25%' class='text-center'>Aksi</th>
            </tr>
          </thead>
		  <tbody>
		  ";
            $hasil = mysqli_query($conn,"SELECT * FROM hasil ORDER BY id_hasil limit $offset,$limit");
            $no = 1;
            $no = 1 + $offset;
            $counter = 1;
            while ($r = mysqli_fetch_array($hasil)) {
              if ($r['hasil_id'] > 0) {
                if ($counter % 2 == 0)
                    $warna = "dark";
                else
                    $warna = "light";
                $kerusakan_name = isset($arpkt[$r['hasil_id']]) ? $arpkt[$r['hasil_id']] : 'Unknown Kerusakan';
                echo "<tr data-kerusakan='" . htmlspecialchars($kerusakan_name) . "'>
             <td align=center>$no</td>
             <td>$r[tanggal]</td>
             <td>" . $kerusakan_name . "</td>
             <td><span class='label label-default'>" . $r['hasil_nilai'] . "</span></td>
             <td align=center>
                 <a type='button' class='btn btn-default btn-xs' target='_blank' href='riwayat-detail/" . $r['id_hasil'] . "'><i class='fa fa-eye' aria-hidden='true'></i> Detail </a> &nbsp;
                 <a type='button' class='btn btn-danger btn-xs' href='javascript:void(0)' onclick=\"confirmDelete('index.php?module=riwayat&act=hapus&id=$r[id_hasil]')\"><i class='fa fa-trash-o' aria-hidden='true'></i> Hapus</a>
             </td></tr>";
                $no++;
                $counter++;
            }
            }
            echo "</tbody></table></div>";
            ?>

            <div class="col-md-4">
              <div class="box box-success box-solid">
                <div class="box-header with-border">
                  <i class="fa fa-pie-chart"></i>

                  <h3 class="box-title">Grafik</h3>

                  <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                    </button>
                    <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                  </div>
                </div>
                <div class="box-body">
                  <div id="donut-chart" class="chart" style="width:100%;height:250px;"></div>
                  <hr>
                  <div id="legend-container"></div>
                </div>
                <!-- /.box-body-->
              </div>
            </div>

            <?php
            echo "</div><div class='col-md-12'><div class='row'><div class=paging>";

            if ($offset != 0) {
                $prevoffset = $offset - $limit;
                echo "<span class=prevnext> <a href=index.php?module=riwayat&offset=$prevoffset>Back</a></span>";
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
                    echo "<a href=index.php?module=riwayat&offset=$newoffset>$i</a>";
//cetak halaman
                } else {
                    echo "<span class=current>" . $i . "</span>"; //cetak halaman tanpa link
                }
            }

//cek halaman akhir
            if (!(($offset / $limit) + 1 == $halaman) && $halaman != 1) {

//jika bukan halaman terakhir maka berikan next
                $newoffset = $offset + $limit;
                echo "<span class=prevnext><a href=index.php?module=riwayat&offset=$newoffset>Next</a>";
            } else {
                echo "<span class=disabled>Next</span>"; //cetak halaman tanpa link
            }

            echo "</div></div></div>";
        } else {
            echo "<br><b>Data Kosong !</b>";
        }
}
?>

<style>
.table-filter {
    border: 1px solid #ccc !important;
    background-color: white !important;
    color: #333 !important;
    border-radius: 3px;
    padding: 6px;
    font-size: 12px;
    width: 100%;
    margin-top: 5px;
    box-sizing: border-box;
}
.table-filter:focus {
    border-color: #007bff !important;
    outline: none;
    box-shadow: 0 0 5px rgba(0, 123, 255, 0.3);
}
.highlight {
    background-color: #ffff99 !important;
}
</style>

<script>
    $(function () {

      <?php
      $hasilg = mysqli_query($conn,"SELECT hasil_id, count('hasil_id') jlh_id FROM hasil group by hasil_id ORDER BY jlh_id desc");
      $arr = array(); // initialize array for chart data
      while ($rg = mysqli_fetch_array($hasilg)) {
        if ($rg['hasil_id'] > 0) {
          $label = isset($arpkt[$rg['hasil_id']]) ? '&nbsp;' . $arpkt[$rg['hasil_id']] : '&nbsp;Unknown Kerusakan';
          $arr[] = array('label' => $label, 'data' => array(array('Kerusakan ' . $rg['hasil_id'], $rg['jlh_id'])));
        }
      }
      ?>
      var donutData = <?php echo json_encode($arr); ?>

      function legendFormatter(label, series) {
        return '<div class="text text-primary margin4">' + label + ' ' + Math.round(series.percent) + '%';
      };

      $.plot('#donut-chart', donutData, {
        series: {
          pie: {
            show: true,
            radius: 1,
            innerRadius: 0.3,
            label: {
              show: true,
              radius: 2/3,
              formatter: function (label, series) {
                return '<div class="badge bg-navy color-pallete">' + Math.round(series.percent) + '%</div>';
              },
              threshold: 0.01
            }

          }
        },
        legend: {
          show: true,
          container: $("#legend-container"),
          labelFormatter: legendFormatter,
        }
      })

      // Fungsi untuk filtering kerusakan - DIMODIFIKASI
      function filterTable() {
          var $rows = $('#riwayatTable tbody tr');
          var filterValue = $('#riwayatTable .table-filter').val().toLowerCase();
          
          $rows.each(function() {
              var kerusakanText = $(this).data('kerusakan').toLowerCase();
              
              // Cek apakah teks kerusakan dimulai dengan nilai filter
              if (kerusakanText.indexOf(filterValue) === 0) {
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

      // Event handler untuk filtering
      $('.table-filter').on('keyup', function() {
          filterTable();
      });

    })

    // Fungsi konfirmasi hapus
    function confirmDelete(url) {
        if (confirm('Apakah Anda yakin ingin menghapus riwayat ini?')) {
            window.location.href = url;
        }
    }

    function labelFormatter(label, series) {
      return '<div style="font-size:13px; text-align:center; padding:2px; color: #fff; font-weight: 600;">'
              + label
              + '<br>'
              + Math.round(series.percent) + '%</div>'
    }
</script>