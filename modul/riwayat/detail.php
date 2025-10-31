<?php
// ...existing code...
?>
<title>Detail Riwayat - Chirexs 1.0</title>
<?php

// pastikan koneksi $conn tersedia dari index/loader
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id <= 0) {
    echo "<p>Id tidak valid.</p>";
    return;
}

// warna dan timezone
$arcolor = array('#ffffff', '#cc66ff', '#019AFF', '#00CBFD', '#00FEFE', '#A4F804', '#FFFC00', '#FDCD01', '#FD9A01', '#FB6700');
date_default_timezone_set("Asia/Jakarta");

// inisialisasi
$argejala = [];

// ambil kondisi dari input POST (jika ada)
if (!empty($_POST['kondisi']) && is_array($_POST['kondisi'])) {
    foreach ($_POST['kondisi'] as $k) {
        $arkondisi = explode("_", $k);
        if (count($arkondisi) >= 2 && strlen($k) > 1) {
            // assign langsung untuk menghindari operator array union
            $argejala[ $arkondisi[0] ] = $arkondisi[1];
        }
    }
}

// preload semua kondisi
$arkondisitext = [];
$sqlkondisi = mysqli_query($conn, "SELECT * FROM kondisi ORDER BY id+0");
while ($rkondisi = mysqli_fetch_assoc($sqlkondisi)) {
    $arkondisitext[$rkondisi['id']] = $rkondisi['kondisi'];
}

// preload semua kerusakan
$arpkt = $ardpkt = $arspkt = $argpkt = [];
$sqlpkt = mysqli_query($conn, "SELECT * FROM kerusakan ORDER BY kode_kerusakan+0");
while ($rpkt = mysqli_fetch_assoc($sqlpkt)) {
    $k = $rpkt['kode_kerusakan'];
    $arpkt[$k]   = $rpkt['nama_kerusakan'];
    $ardpkt[$k]  = $rpkt['det_kerusakan'];
    $arspkt[$k]  = $rpkt['srn_kerusakan'];
    $argpkt[$k]  = $rpkt['gambar'];
}

// preload semua gejala
$gejalaMap = [];
$sqlgejala = mysqli_query($conn, "SELECT * FROM gejala");
while ($rg = mysqli_fetch_assoc($sqlgejala)) {
    $gejalaMap[$rg['kode_gejala']] = $rg;
}

// ambil hasil dari DB (safely)
$stmt = mysqli_prepare($conn, "SELECT kerusakan, gejala FROM hasil WHERE id_hasil = ?");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$res = mysqli_stmt_get_result($stmt);
$arkerusakan = $argejala_from_db = [];
if ($res && $row = mysqli_fetch_assoc($res)) {
    // gunakan allowed_classes=false untuk mengurangi risiko
    $arkerusakan = @unserialize($row['kerusakan'], ['allowed_classes' => false]) ?: [];
    $argejala_from_db = @unserialize($row['gejala'], ['allowed_classes' => false]) ?: [];
}

// prefer POST kondisi jika ada, otherwise use saved gejala from DB
if (empty($argejala) && !empty($argejala_from_db) && is_array($argejala_from_db)) {
    $argejala = $argejala_from_db;
}

// ubah struktur kerusakan ke array terurut
$idpkt = $nmpkt = $vlpkt = [];
$np = 0;
foreach ($arkerusakan as $key => $value) {
    $np++;
    $idpkt[$np]  = $key;
    $nmpkt[$np]  = $arpkt[$key] ?? 'Unknown';
    $vlpkt[$np]  = $value;
}

// gambar utama
$gambar = 'gambar/noimage.png';
if (!empty($idpkt[1]) && !empty($argpkt[$idpkt[1]])) {
    $gambar = 'gambar/kerusakan/' . $argpkt[$idpkt[1]];
}

// tampilan hasil (escape output)
echo "<div class='content'>
    <h2 class='text text-primary'>Hasil Diagnosis &nbsp;&nbsp;<button id='print' onClick='window.print();' data-toggle='tooltip' data-placement='right' title='Klik tombol ini untuk mencetak hasil diagnosa'><i class='fa fa-print'></i> Cetak</button></h2>
    <hr>
    <table class='table table-bordered table-striped diagnosa'>
      <thead>
        <tr>
          <th width='8%'>No</th>
          <th width='10%'>Kode</th>
          <th>Gejala yang dialami (keluhan)</th>
          <th width='20%'>Pilihan</th>
        </tr>
      </thead>
      <tbody>";

$ig = 0;
foreach ($argejala as $key => $value) {
    $kondisi = (int)$value;
    $ig++;
    $gejalaKode = $key;
    $r4 = $gejalaMap[$key] ?? null;
    if (!$r4) continue;
    $kode_padded = 'G' . str_pad((string)$r4['kode_gejala'], 3, '0', STR_PAD_LEFT);
    echo '<tr>';
    echo '<td>' . $ig . '</td>';
    echo '<td>' . htmlspecialchars($kode_padded, ENT_QUOTES) . '</td>';
    echo '<td><span class="hasil text text-primary">' . htmlspecialchars($r4['nama_gejala'] ?? '-', ENT_QUOTES) . '</span></td>';
    $kondisiText = $arkondisitext[$kondisi] ?? '-';
    $color = $arcolor[$kondisi] ?? '#000';
    echo '<td><span class="kondisipilih" style="color:' . htmlspecialchars($color, ENT_QUOTES) . '">' . htmlspecialchars($kondisiText, ENT_QUOTES) . '</span></td>';
    echo '</tr>';
}

echo "</tbody></table>";

echo "<div class='well well-small'>
    <img class='card-img-top img-bordered-sm' style='float:right; margin-left:15px;' src='" . htmlspecialchars($gambar, ENT_QUOTES) . "' height='200'>
    <h3>Hasil Diagnosa</h3>";

$namaUtama = $nmpkt[1] ?? '-';
$nilaiUtama = isset($vlpkt[1]) ? round($vlpkt[1], 2) : 0;
echo "<div class='callout callout-default'>Jenis kerusakan yang diderita adalah <b><h3 class='text text-success'>" . htmlspecialchars($namaUtama, ENT_QUOTES) . "</h3></b> / " . htmlspecialchars($nilaiUtama, ENT_QUOTES) . " % (" . htmlspecialchars($vlpkt[1] ?? 0, ENT_QUOTES) . ")</div>";
echo "</div>";

echo "<div class='box box-info box-solid'><div class='box-header with-border'><h3 class='box-title'>Detail</h3></div><div class='box-body'><h4>";
echo nl2br(htmlspecialchars($ardpkt[$idpkt[1]] ?? '-', ENT_QUOTES));
echo "</h4></div></div>";

echo "<div class='box box-warning box-solid'><div class='box-header with-border'><h3 class='box-title'>Saran</h3></div><div class='box-body'><h4>";
echo nl2br(htmlspecialchars($arspkt[$idpkt[1]] ?? '-', ENT_QUOTES));
echo "</h4></div></div>";

echo "<div class='box box-danger box-solid'><div class='box-header with-border'><h3 class='box-title'>Kemungkinan lain:</h3></div><div class='box-body'>";
for ($ipl = 2; $ipl <= count($idpkt); $ipl++) {
    if (empty($idpkt[$ipl])) continue;
    $namaLain = htmlspecialchars($nmpkt[$ipl] ?? '-', ENT_QUOTES);
    $nilaiLain = isset($vlpkt[$ipl]) ? round($vlpkt[$ipl], 2) : 0;
    echo "<h4><i class='fa fa-caret-square-o-right'></i> " . $namaLain . " / " . $nilaiLain . " % (" . htmlspecialchars($vlpkt[$ipl] ?? 0, ENT_QUOTES) . ")</h4>";
}
echo "</div></div></div>";
// ...existing code...
?>