<title>Ubah Password - SpkarCF</title>
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
                            pattern='.{5,}' title='Minimal 5 karakter'
                            required /></td></tr>
                    <tr><td>Masukkan kembali password baru</td>
                        <td><input class='form-control' autocomplete='off'
                            placeholder='Ulangi password baru...'
                            type='password' name='newPass2'
                            pattern='.{5,}' title='Minimal 5 karakter'
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
            
            // 1. Ambil hash password dari database
            $stmt = mysqli_prepare($conn, "SELECT password FROM admin WHERE username = ?");
            mysqli_stmt_bind_param($stmt, "s", $_SESSION['username']);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            $data = mysqli_fetch_assoc($result);
            
            if (!$data) {
                die("<h2>User tidak ditemukan</h2>");
            }

            // 2. VERIFIKASI password lama (MENGGUNAKAN password_verify)
            // Cek apakah password lama yang dimasukkan cocok dengan hash di database
            if (password_verify($_POST['oldPass'], $data['password'])) { 
                if ($_POST['newPass1'] === $_POST['newPass2']) {
                    if (strlen($_POST['newPass1']) < 5) {
                        echo "<h2>Password minimal 5 karakter</h2>";
                        break;
                    }

                    // 3. HASH password baru (MENGGUNAKAN password_hash)
                    $newPassHashed = password_hash($_POST['newPass1'], PASSWORD_DEFAULT);
                    
                    // Update password dengan hash baru
                    $stmt_update = mysqli_prepare($conn, "UPDATE admin SET password = ? WHERE username = ?");
                    mysqli_stmt_bind_param($stmt_update, "ss", $newPassHashed, $_SESSION['username']);
                    
                    if (mysqli_stmt_execute($stmt_update)) {
                        // pop-up saat berhasil lalu kembali ke form password
                        echo "<script>alert('Password berhasil diubah'); window.location='?module=password';</script>";
                    } else {
                        echo "<h2>Gagal mengubah password</h2>";
                    }
                    mysqli_stmt_close($stmt_update);
                    
                } else {
                    echo "<script>alert('Password baru tidak sama'); window.location='?module=password';</script>";
                }
            } else {
                echo "<script>alert('Password lama salah'); window.location='?module=password';</script>";
            }
            mysqli_stmt_close($stmt);
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