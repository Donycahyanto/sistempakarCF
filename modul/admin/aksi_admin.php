<?php

session_start();
if (!(isset($_SESSION['username']) && isset($_SESSION['password']))) {
    header('location:index.php');
    exit();
}

include "../../config/koneksi.php";

$module = $_GET['module'] ?? '';
$act = $_GET['act'] ?? '';

// Hapus admin
if ($module === 'admin' && $act === 'hapus') {
    if (isset($_GET['id'])) {
        $id_to_delete = mysqli_real_escape_string($conn, $_GET['id']);
        mysqli_query($conn, "DELETE FROM admin WHERE username='$id_to_delete'");
    }
    header('location:../../index.php?module=' . $module);
    exit;
}

// Input admin
if ($module === 'admin' && $act === 'input') {
    if (isset($_POST['username'], $_POST['nama_lengkap'], $_POST['password'])) {
        $username = mysqli_real_escape_string($conn, $_POST['username']);
        $nama_lengkap = mysqli_real_escape_string($conn, $_POST['nama_lengkap']);

        // Validasi input tidak boleh kosong
        if (empty($username) || empty($nama_lengkap) || empty($_POST['password'])) {
            $_SESSION['error'] = "Semua field harus diisi!";
            header('location:../../index.php?module=' . $module);
            exit;
        }

        // Validasi username unik
        $check = mysqli_query($conn, "SELECT username FROM admin WHERE username='$username'");
        if (mysqli_num_rows($check) > 0) {
            $_SESSION['error'] = "Username sudah digunakan!";
            header('location:../../index.php?module=' . $module);
            exit;
        }

        // Hash password
        $pass = md5($_POST['password']);

        // Prepared statement untuk insert
        $stmt = mysqli_prepare($conn, "INSERT INTO admin (username, password, nama_lengkap) VALUES (?, ?, ?)");
        if (!$stmt) {
            $_SESSION['error'] = "Prepare failed: " . mysqli_error($conn);
            header('location:../../index.php?module=' . $module);
            exit;
        }

        mysqli_stmt_bind_param($stmt, 'sss', $username, $pass, $nama_lengkap);
        
        if (mysqli_stmt_execute($stmt)) {
            $_SESSION['success'] = "Admin berhasil ditambahkan";
        } else {
            $_SESSION['error'] = "Gagal menambah admin: " . mysqli_stmt_error($stmt);
        }
        
        mysqli_stmt_close($stmt);
        header('location:../../index.php?module=' . $module);
        exit;
    } else {
        $_SESSION['error'] = "Data formulir tidak lengkap";
        header('location:../../index.php?module=' . $module);
        exit;
    }
}

// Update admin
if ($module === 'admin' && $act === 'update') {
    if (isset($_POST['username'], $_POST['nama_lengkap'], $_POST['id'])) {
        $username_baru = mysqli_real_escape_string($conn, $_POST['username']); // Username baru (misal: Dony2)
        $nama_lengkap = mysqli_real_escape_string($conn, $_POST['nama_lengkap']);
        $username_lama = mysqli_real_escape_string($conn, $_POST['id']); // Username lama (misal: Dony1)

        // 1. Lakukan update ke database
        $update_query = "UPDATE admin SET username='$username_baru', nama_lengkap='$nama_lengkap' WHERE username='$username_lama'";
        if (mysqli_query($conn, $update_query)) {
            
            // 2. Cek apakah admin yang diedit adalah admin yang sedang login
            if ($_SESSION['username'] === $username_lama) {
                // 3. Jika iya, update variabel sesi dengan username yang baru
                $_SESSION['username'] = $username_baru;
            }
            // Opsional: Tambahkan pesan sukses
            $_SESSION['success'] = "Data Admin berhasil diubah.";
        } else {
            // Opsional: Tambahkan pesan error jika query gagal
             $_SESSION['error'] = "Gagal mengubah data Admin: " . mysqli_error($conn);
        }

    } else {
        $_SESSION['error'] = "Data formulir tidak lengkap.";
    }
    header('location:../../index.php?module=' . $module);
    exit;
}
?>