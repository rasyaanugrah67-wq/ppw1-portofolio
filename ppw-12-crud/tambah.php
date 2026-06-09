<?php
include_once("config.php");
requireLogin();

$errors = [];
$success = "";

if (isset($_POST['submit'])) {
    $nim = mysqli_real_escape_string($conn, $_POST['nim']);
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $jurusan = mysqli_real_escape_string($conn, $_POST['jurusan']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $alamat = mysqli_real_escape_string($conn, $_POST['alamat']);
    $foto_filename = null;

    if (empty($nim)) $errors[] = 'NIM tidak boleh kosong';
    if (empty($nama)) $errors[] = 'Nama tidak boleh kosong';
    if (empty($jurusan)) $errors[] = 'Jurusan tidak boleh kosong';
    if (empty($email)) $errors[] = 'Email tidak boleh kosong';
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Format email tidak valid';

    // === TUGAS 2: VALIDASI NIM (ANGKA & 8-12 DIGIT) ===
    if (!empty($nim)) {
        if (!is_numeric($nim)) {
            $errors[] = 'Pesan Error: NIM hanya boleh berisi angka, tidak boleh mengandung huruf!';
        }
        if (strlen($nim) < 8 || strlen($nim) > 12) {
            $errors[] = 'Pesan Error: Panjang NIM tidak sesuai (harus antara 8 hingga 12 karakter)!';
        }
    }

    if (empty($errors)) {
        $chk = mysqli_query($conn, "SELECT nim FROM mahasiswa WHERE nim='$nim'");
        if (mysqli_num_rows($chk) > 0) $errors[] = 'NIM sudah terdaftar';
    }

    if (empty($errors) && !empty($_FILES['foto']['name'])) {
        $upload = uploadFile($_FILES['foto']);
        if ($upload['success']) {
            $foto_filename = $upload['filename'];
        } else {
            $errors[] = $upload['message'];
        }
    }

    if (empty($errors)) {
        $foto_sql = $foto_filename ? "'$foto_filename'" : 'NULL';
        $sql = "INSERT INTO mahasiswa (nim, nama, jurusan, email, alamat, foto) VALUES ('$nim', '$nama', '$jurusan', '$email', '$alamat', $foto_sql)";
        if (mysqli_query($conn, $sql)) {
            $success = 'Data berhasil ditambahkan!';
        } else {
            $errors[] = 'Error: ' . mysqli_error($conn);
            if ($foto_filename) deleteFile($foto_filename);
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Mahasiswa</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f9; padding: 20px; }
        .box { width: 400px; margin: 0 auto; background: #fff; padding: 20px; border: 1px solid #ccc; border-radius: 5px; }
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; margin-bottom: 5px; font-weight: bold; }
        .form-group input, .form-group textarea { width: 100%; padding: 8px; box-sizing: border-box; }
        .btn { padding: 10px 15px; background: #007bff; color: #fff; border: none; border-radius: 3px; cursor: pointer; }
        .error { color: red; margin-bottom: 10px; }
        .success { color: green; margin-bottom: 10px; }
    </style>
</head>
<body>
<div class="box">
    <h2>Tambah Data Mahasiswa</h2>
    <?php 
    if(!empty($errors)) { foreach($errors as $err) echo "<p class='error'>• $err</p>"; }
    if($success) echo "<p class='success'>$success</p>";
    ?>
    <form action="tambah.php" method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label>Foto Profil</label>
            <input type="file" name="foto" accept="image/*">
        </div>
        <div class="form-group">
            <label>NIM *</label>
            <input type="text" name="nim" value="<?= isset($_POST['nim']) ? htmlspecialchars($_POST['nim']) : '' ?>" required>
        </div>
        <div class="form-group">
            <label>Nama Lengkap *</label>
            <input type="text" name="nama" value="<?= isset($_POST['nama']) ? htmlspecialchars($_POST['nama']) : '' ?>" required>
        </div>
        <div class="form-group">
            <label>Jurusan *</label>
            <input type="text" name="jurusan" value="<?= isset($_POST['jurusan']) ? htmlspecialchars($_POST['jurusan']) : '' ?>" required>
        </div>
        <div class="form-group">
            <label>Email *</label>
            <input type="text" name="email" value="<?= isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '' ?>" required>
        </div>
        <div class="form-group">
            <label>Alamat</label>
            <textarea name="alamat" rows="3"><?= isset($_POST['alamat']) ? htmlspecialchars($_POST['alamat']) : '' ?></textarea>
        </div>
        <button type="submit" name="submit" class="btn">Simpan Data</button>
        <a href="index.php" style="margin-left: 10px;">Kembali</a>
    </form>
</div>
</body>
</html>