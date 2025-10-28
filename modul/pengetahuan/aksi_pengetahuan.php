<?php
session_start();
if (!(isset($_SESSION['username']) && isset($_SESSION['password']))) {
    header('location:../../index.php');
    exit();
}

include "../../config/koneksi.php";

$module = $_GET['module'] ?? '';
$act = $_GET['act'] ?? '';

// helper to normalize MB/MD to float
function formatCertaintyValue($value) {
    $value = str_replace(',', '.', trim((string)$value));
    $f = (float)$value;
    return $f;
}

// default page size (sama seperti listing)
$limit = 15;

// Hapus pengetahuan
if ($module === 'pengetahuan' && $act === 'hapus') {
    $id = mysqli_real_escape_string($conn, $_GET['id'] ?? '');
    $offset = intval($_GET['offset'] ?? 0);

    $stmt = mysqli_prepare($conn, "DELETE FROM basis_pengetahuan WHERE kode_pengetahuan = ?");
    mysqli_stmt_bind_param($stmt, "s", $id);
    mysqli_stmt_execute($stmt);

    // pastikan offset tidak melebihi jumlah baris setelah delete
    $total = intval(mysqli_fetch_row(mysqli_query($conn, "SELECT COUNT(*) FROM basis_pengetahuan"))[0] ?? 0);
    if ($total <= 0) $offset = 0;
    elseif ($offset >= $total) {
        $offset = max(0, floor(($total - 1) / $limit) * $limit);
    }

    header('location:../../index.php?module=' . $module . '&offset=' . $offset);
    exit();
}

// Input pengetahuan
elseif ($module === 'pengetahuan' && $act === 'input') {
    $kode_penyakit = mysqli_real_escape_string($conn, $_POST['kode_penyakit'] ?? '');
    $kode_gejala   = mysqli_real_escape_string($conn, $_POST['kode_gejala'] ?? '');
    $mb = formatCertaintyValue($_POST['mb'] ?? '0');
    $md = formatCertaintyValue($_POST['md'] ?? '0');

    $stmt = mysqli_prepare($conn, "INSERT INTO basis_pengetahuan(kode_penyakit, kode_gejala, mb, md) VALUES (?, ?, ?, ?)");
    mysqli_stmt_bind_param($stmt, "ssdd", $kode_penyakit, $kode_gejala, $mb, $md);
    mysqli_stmt_execute($stmt);

    $insert_id = mysqli_insert_id($conn);

    // hitung posisi item baru dalam urutan ORDER BY kode_pengetahuan
    $posStmt = mysqli_prepare($conn, "SELECT COUNT(*) AS pos FROM basis_pengetahuan WHERE kode_pengetahuan <= ?");
    mysqli_stmt_bind_param($posStmt, "s", $insert_id);
    mysqli_stmt_execute($posStmt);
    $posRes = mysqli_stmt_get_result($posStmt);
    $posRow = mysqli_fetch_assoc($posRes);
    $rank = intval($posRow['pos'] ?? 0);
    $newoffset = 0;
    if ($rank > 0) {
        $newoffset = floor(($rank - 1) / $limit) * $limit;
    }

    header('location:../../index.php?module=' . $module . '&offset=' . intval($newoffset));
    exit();
}

// Update pengetahuan
elseif ($module === 'pengetahuan' && $act === 'update') {
    $id = mysqli_real_escape_string($conn, $_POST['id'] ?? '');
    $kode_penyakit = mysqli_real_escape_string($conn, $_POST['kode_penyakit'] ?? '');
    $kode_gejala   = mysqli_real_escape_string($conn, $_POST['kode_gejala'] ?? '');
    $mb = formatCertaintyValue($_POST['mb'] ?? '0');
    $md = formatCertaintyValue($_POST['md'] ?? '0');
    $offset = intval($_POST['offset'] ?? 0);

    $stmt = mysqli_prepare($conn, "UPDATE basis_pengetahuan SET kode_penyakit=?, kode_gejala=?, mb=?, md=? WHERE kode_pengetahuan=?");
    mysqli_stmt_bind_param($stmt, "ssdds", $kode_penyakit, $kode_gejala, $mb, $md, $id);
    mysqli_stmt_execute($stmt);

    header('location:../../index.php?module=' . $module . '&offset=' . $offset);
    exit();
}
?>