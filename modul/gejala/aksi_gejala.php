<?php
session_start();
if (!(isset($_SESSION['username']) && isset($_SESSION['password']))) {
    header('location:../../index.php');
    exit();
}

include "../../config/koneksi.php";

$module = $_GET['module'] ?? '';
$act = $_GET['act'] ?? '';

// default page size (sama seperti di listing)
$limit = 15;

// Hapus gejala
if ($module === 'gejala' && $act === 'hapus') {
    $id = $_GET['id'] ?? '';
    $id = mysqli_real_escape_string($conn, $id);
    $offset = intval($_GET['offset'] ?? 0);

    $stmt = mysqli_prepare($conn, "DELETE FROM gejala WHERE kode_gejala = ?");
    mysqli_stmt_bind_param($stmt, "s", $id);
    mysqli_stmt_execute($stmt);

    // pastikan offset tidak melebihi jumlah baris setelah delete
    $total = intval(mysqli_fetch_row(mysqli_query($conn, "SELECT COUNT(*) FROM gejala"))[0] ?? 0);
    if ($total <= 0) $offset = 0;
    elseif ($offset >= $total) {
        $offset = max(0, floor(($total - 1) / $limit) * $limit);
    }

    header('location:../../index.php?module=' . $module . '&offset=' . $offset);
    exit();
}

// Input gejala
elseif ($module === 'gejala' && $act === 'input') {
    $nama_gejala = trim($_POST['nama_gejala'] ?? '');

    $stmt = mysqli_prepare($conn, "INSERT INTO gejala (nama_gejala) VALUES (?)");
    mysqli_stmt_bind_param($stmt, "s", $nama_gejala);
    mysqli_stmt_execute($stmt);

    $insert_id = mysqli_insert_id($conn);

    // hitung posisi (rank) item baru dalam urutan ORDER BY kode_gejala
    $posStmt = mysqli_prepare($conn, "SELECT COUNT(*) AS pos FROM gejala WHERE kode_gejala <= ?");
    mysqli_stmt_bind_param($posStmt, "s", $insert_id);
    mysqli_stmt_execute($posStmt);
    $posRes = mysqli_stmt_get_result($posStmt);
    $posRow = mysqli_fetch_assoc($posRes);
    $rank = intval($posRow['pos'] ?? 0);
    $newoffset = 0;
    if ($rank > 0) {
        $newoffset = floor(($rank - 1) / $limit) * $limit;
    }

    header("location:../../index.php?module=" . $module . "&offset=" . intval($newoffset));
    exit();
}

// Update gejala
elseif ($module === 'gejala' && $act === 'update') {
    $id = $_POST['id'] ?? '';
    $nama_gejala = $_POST['nama_gejala'] ?? '';
    $offset = intval($_POST['offset'] ?? 0);

    $stmt = mysqli_prepare($conn, "UPDATE gejala SET nama_gejala = ? WHERE kode_gejala = ?");
    mysqli_stmt_bind_param($stmt, "ss", $nama_gejala, $id);
    mysqli_stmt_execute($stmt);

    header('location:../../index.php?module=' . $module . '&offset=' . $offset);
    exit();
}
?>