<?php

$nama = "Rasya Anugrah Dyvanio";
$nim = "25/560920/SV/26494";
$prodi = "Teknologi Rekayasa Perangkat Lunak (TRPL)";
$asal_kota = "Yogyakarta";
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Profil Diri</title>
    <style>
        table {
            width: 50%;
            border-collapse: collapse;
            margin: 20px 0;
            font-family: Arial, sans-serif;
        }
        th, td {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 12px;
        }
        th {
            background-color: #4CAF50;
            color: white;
            width: 30%;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>

    <h2>Profil Mahasiswa</h2>
    <table>
        <tr>
            <th>Komponen</th>
            <th>Informasi</th>
        </tr>
        <tr>
            <td><strong>Nama</strong></td>
            <td><?php echo $nama; ?></td>
        </tr>
        <tr>
            <td><strong>NIM</strong></td>
            <td><?php echo $nim; ?></td>
        </tr>
        <tr>
            <td><strong>Prodi</strong></td>
            <td><?php echo $prodi; ?></td>
        </tr>
        <tr>
            <td><strong>Asal Kota</strong></td>
            <td><?php echo $asal_kota; ?></td>
        </tr>
    </table>

</body>
</html>