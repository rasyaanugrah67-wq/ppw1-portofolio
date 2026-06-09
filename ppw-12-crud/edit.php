<?php
include_once("config.php");
requireLogin();

if (!isset($_GET['id'])) { header('Location: index.php'); exit(); }
$id = (int)$_GET['id'];

$result = mysqli_query($conn, "SELECT * FROM mahasiswa WHERE id=$id");
if (mysqli_num_rows($result) == 0) { header('Location: index.php'); exit(); }
$row = mysqli_fetch_assoc($result);
$current_foto = $row["foto"];

$errors = [];

if (isset($_POST['update'])) {
    $nim = mysqli_real_escape_string($conn, $_POST['nim']);
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $jurusan = mysqli_real_escape_string($conn, $_POST['jurusan']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $alamat = mysqli_real_escape_string($conn, $_POST['alamat']);
    $foto_filename = $current_foto;

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

    $chk = mysqli_query($conn, "SELECT nim FROM mahasiswa WHERE nim='$nim' AND id != $id");
    if (mysqli_num_rows($chk) > 0) $errors[] = 'NIM sudah digunakan oleh mahasiswa lain';

    if (isset($_POST['hapus_foto']) && $_POST['hapus_foto'] == '1') {
        if ($current_foto) deleteFile($current_foto);
        $foto_filename = null;
    }

    if (empty($errors) && !empty($_FILES['foto']['name'])) {
        $upload = uploadFile($_FILES['foto']);
        if ($upload['success']) {
            if ($current_foto) deleteFile($current_foto);
            $foto_filename = $upload['filename'];
        } else {
            $errors[] = $upload['message'];
        }
    }

    if (empty($errors)) {
        $foto_sql = $foto_filename ? "'$foto_filename'" : 'NULL';
        $sql = "UPDATE mahasiswa SET nim='$nim', nama='$nama', jurusan='$jurusan', email='$email', alamat='$alamat', foto=$foto_sql WHERE id=$id";
        if (mysqli_query($conn, $sql)) {
            $_SESSION['message'] = 'Data berhasil diperbarui!';
            header('Location: index.php');
            exit();
        } else {
            $errors[] = 'Error: ' . mysqli_error($conn);
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Mahasiswa</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f9; padding: 20px; }
        .box { width: 400px; margin: 0 auto; background: #fff; padding: 20px; border: 1px solid #ccc; border-radius: 5px; }
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; margin-bottom: 5px; font-weight: bold; }
        .form-group input, .form-group textarea { width: 100%; padding: 8px; box-sizing: border-box; }
        .btn { padding: 10px 15px; background: #e0a800; color: #fff; border: none; border-radius: 3px; cursor: pointer; }
        .error { color: red; margin-bottom: 10px; }
    </style>
</head>
<body>
<div class="box">
    <h2>Edit Data Mahasiswa</h2>
    <?php if(!empty($errors)) { foreach($errors as $err) echo "<p class='error'>• $err</p>"; } ?>
    
    <form method="POST" action="" enctype="multipart/form-data">
        <div class="form-group">
            <label>Foto Saat Ini</label>
            <?php if($current_foto): ?>
                <img src="uploads/mahasiswa/<?= $current_foto ?>" width="120" style="display:block; margin-bottom:5px; border:1px solid #ccc;">
                <input type="checkbox" name="hapus_foto" value="1"> Hapus Foto Lama
            <?php else: echo "<p style='font-style:italic; color:#666;'>Tidak ada foto profil</p>"; endif; ?>
        </div>
        <div class="form-group">
            <label>Ganti Foto Baru (Opsional)</label>
            <input type="file" name="foto" accept="image/*">
        </div>
        <div class="form-group">
            <label>NIM *</label>
            <input type="text" name="nim" value="<?= htmlspecialchars($row['nim']) ?>" required>
        </div>
        <div class="form-group">
            <label>Nama Lengkap *</label>
            <input type="text" name="nama" value="<?= htmlspecialchars($row['nama']) ?>" required>
        </div>
        <div class="form-group">
            <label>Jurusan *</label>
            <input type="text" name="jurusan" value="<?= htmlspecialchars($row['jurusan']) ?>" required>
        </div>
        <div class="form-group">
            <label>Email *</label>
            <input type="text" name="email" value="<?= htmlspecialchars($row['email']) ?>" required>
        </div>
        <div class="form-group">
            <label>Alamat</label>
            <textarea name="alamat" rows="3"><?= htmlspecialchars($row['alamat']) ?></textarea>
        </div>
        <button type="submit" name="update" class="btn">Perbarui Data</button>
        <a href="index.php" style="margin-left: 10px;">Batal</a>
    </form>
</div>
</body>
</html>