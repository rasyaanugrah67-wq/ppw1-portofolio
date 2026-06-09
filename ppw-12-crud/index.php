<?php
include_once("config.php");
requireLogin();

$limit = 5;
$page = isset($_GET["page"]) ? (int)$_GET["page"] : 1;
$offset = ($page - 1) * $limit;

$search = isset($_GET["search"]) ? mysqli_real_escape_string($conn, $_GET["search"]) : "";
$where = "";
if (!empty($search)) {
    $where = "WHERE nim LIKE '%$search%' OR nama LIKE '%$search%' OR jurusan LIKE '%$search%' OR email LIKE '%$search%'";
}

$count_result = mysqli_query($conn, "SELECT COUNT(*) AS total FROM mahasiswa $where");
$total_data = mysqli_fetch_assoc($count_result)["total"];
$total_pages = ceil($total_data / $limit);

$query = "SELECT * FROM mahasiswa $where ORDER BY id DESC LIMIT $limit OFFSET $offset";
$result = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Daftar Mahasiswa</title>
    <style>
        table, th, td { border: 1px solid black; border-collapse: collapse; padding: 8px; } 
        .photo { width: 50px; height: 50px; object-fit: cover; }
    </style>
</head>
<body>
    <h2>Sistem Informasi Data Mahasiswa</h2>
    <p>Selamat Datang, <b><?= $_SESSION['full_name']; ?></b> | <a href="logout.php">Logout</a></p>
    
    <?php if (isset($_SESSION['message'])): ?>
        <div style="color: green;"><?= $_SESSION['message']; unset($_SESSION['message']); ?></div>
    <?php endif; ?>

    <p><a href="tambah.php">+ Tambah Mahasiswa</a></p>
    
    <form method="GET" action="">
        <input type="text" name="search" value="<?= htmlspecialchars($search) ?>" placeholder="Cari...">
        <button type="submit">Cari</button>
    </form><br>

    <table>
        <thead>
            <tr>
                <th>Foto</th><th>NIM</th><th>Nama</th><th>Jurusan</th><th>Email</th><th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = mysqli_fetch_assoc($result)): ?>
            <tr>
                <td>
                    <?php if ($row['foto']): ?>
                        <img src="uploads/mahasiswa/<?= $row["foto"] ?>" class="photo">
                    <?php else: echo "N/A"; endif; ?>
                </td>
                <td><?= htmlspecialchars($row['nim']) ?></td>
                <td><?= htmlspecialchars($row['nama']) ?></td>
                <td><?= htmlspecialchars($row['jurusan']) ?></td>
                <td><?= htmlspecialchars($row['email']) ?></td>
                <td>
                    <a href="detail.php?id=<?= $row['id'] ?>">Detail</a> | 
                    <a href="edit.php?id=<?= $row["id"] ?>">Edit</a> | 
                    <a href="hapus.php?id=<?= $row["id"] ?>" onclick="return confirm('Yakin hapus?')">Hapus</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <div><br>
        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
            <a href="?page=<?= $i ?>&search=<?= urlencode($search) ?>" style="<?= $i == $page ? 'font-weight:bold;color:red;' : '' ?>">[<?= $i ?>]</a>
        <?php endfor; ?>
    </div>
</body>
</html>