<title>Ubah Password - Chirexs 1.0</title>
<?php

?>
<?php
if (isset($_SESSION['username']) && isset($_SESSION['password'])) {
    switch($_GET['act'] ?? 'default') {
        default:
            echo "<form method='post' action='?module=password&act=updatepassword'>
                <table class='table table-bordered'>
                    <tr><td width=220>Masukkan password lama</td>
                        <td><input class='form-control' autocomplete='off' 
                            placeholder='Ketik password lama...' 
                            type='password' name='oldPass' required /></td></tr>
                    <tr><td>Masukkan password baru</td>
                        <td><input class='form-control' autocomplete='off'
                            placeholder='Ketik password baru...'
                            type='password' name='newPass1' 
                            pattern='.{8,}' title='Minimal 8 karakter'
                            required /></td></tr>
                    <tr><td>Masukkan kembali password baru</td>
                        <td><input class='form-control' autocomplete='off'
                            placeholder='Ulangi password baru...'
                            type='password' name='newPass2'
                            pattern='.{8,}' title='Minimal 8 karakter'
                            required /></td></tr>
                    <tr><td></td><td>
                        <input class='btn btn-success' type='submit' 
                               name='submit' value='Simpan' />
                    </td></tr>
                </table>
            </form>";
            break;

        case "updatepassword":
            include "config/koneksi.php";
            
            // Validasi input
            if (!isset($_POST['oldPass'], $_POST['newPass1'], $_POST['newPass2'])) {
                die("<h2>Form tidak lengkap</h2>");
            }
            
            // Gunakan prepared statement
            $stmt = mysqli_prepare($conn, "SELECT password FROM admin WHERE username = ?");
            mysqli_stmt_bind_param($stmt, "s", $_SESSION['username']);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            $data = mysqli_fetch_assoc($result);
            
            if (!$data) {
                die("<h2>User tidak ditemukan</h2>");
            }

            // Verifikasi password lama
            if ($data['password'] === md5($_POST['oldPass'])) { // TODO: Ganti ke password_verify
                if ($_POST['newPass1'] === $_POST['newPass2']) {
                    if (strlen($_POST['newPass1']) < 8) {
                        echo "<h2>Password minimal 8 karakter</h2>";
                        break;
                    }

                    // Update password
                    $newPass = md5($_POST['newPass1']); // TODO: Ganti ke password_hash
                    $stmt = mysqli_prepare($conn, "UPDATE admin SET password = ? WHERE username = ?");
                    mysqli_stmt_bind_param($stmt, "ss", $newPass, $_SESSION['username']);
                    
                    if (mysqli_stmt_execute($stmt)) {
                        echo "<h2>Password berhasil diubah</h2>";
                    } else {
                        echo "<h2>Gagal mengubah password</h2>";
                    }
                } else {
                    echo "<h2>Password baru tidak sama</h2>";
                }
            } else {
                echo "<h2>Password lama salah</h2>";
            }
            break;
    }
} else {
    echo "<h2>Akses Ditolak</h2>
          <br>
          <strong>Anda harus login untuk mengakses menu ini!</strong><br><br>
          <input type='button' value='Klik Disini' 
           onclick=\"location.href='./'\"><br><br>";
}
?>