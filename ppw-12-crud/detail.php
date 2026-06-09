<?php
include_once("config.php");
requireLogin();

if (!isset($_GET['id'])) {
    header('Location: index.php');
    exit();
}

$id = (int)$_GET['id'];

$query = "SELECT * FROM mahasiswa WHERE id = $id";
$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) == 0) {
    header('Location: index.php');
    exit();
}

$row = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Detail Mahasiswa - <?= htmlspecialchars($row['nama']) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light py-5">
    <div class="container" style="max-width: 600px;">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white text-center py-3">
                <h4 class="mb-0">Detail Informasi Mahasiswa</h4>
            </div>
            <div class="card-body p-4 text-center">
                
                <div class="mb-4">
                    <?php if (!empty($row['foto']) && file_exists("uploads/mahasiswa/" . $row['foto'])): ?>
                        <img src="uploads/mahasiswa/<?= $row['foto'] ?>" alt="Foto Profil" class="img-thumbnail shadow-sm" style="width: 200px; height: 200px; object-fit: cover;">
                    <?php else: ?>
                        <div class="bg-secondary text-white d-inline-flex align-items-center justify-content-center rounded img-thumbnail shadow-sm" style="width: 200px; height: 200px;">
                            <span class="fs-4">Tanpa Foto</span>
                        </div>
                    <?php endif; ?>
                </div>

                <table class="table table-striped text-start border fs-5">
                    <tr>
                        <th width="35%">NIM</th>
                        <td><?= htmlspecialchars($row['nim']) ?></td>
                    </tr>
                    <tr>
                        <th>Nama</th>
                        <td><?= htmlspecialchars($row['nama']) ?></td>
                    </tr>
                    <tr>
                        <th>Jurusan</th>
                        <td><?= htmlspecialchars($row['jurusan']) ?></td>
                    </tr>
                    <tr>
                        <th>Email</th>
                        <td><?= htmlspecialchars($row['email']) ?></td>
                    </tr>
                    <tr>
                        <th>Alamat</th>
                        <td><?= nl2br(htmlspecialchars($row['alamat'])) ?></td>
                    </tr>
                    <tr>
                        <th>Tanggal Daftar</th>
                        <td><?= date('d F Y, H:i', strtotime($row['created_at'])) ?> WIB</td>
                    </tr>
                </table>

                <div class="d-flex justify-content-between mt-4">
                    <a href="index.php" class="btn btn-outline-secondary px-4">Kembali</a>
                    <a href="edit.php?id=<?= $row['id'] ?>" class="btn btn-warning px-4">Edit Data</a>
                </div>

            </div>
        </div>
    </div>
</body>
</html>