<?php

date_default_timezone_set('Asia/Jakarta');

$bulan_inggris = date('F');
$daftar_bulan = [
    'January' => 'Januari', 'February' => 'Februari', 'March' => 'Maret', 
    'April' => 'April', 'May' => 'Mei', 'June' => 'Juni', 
    'July' => 'Juli', 'August' => 'Agustus', 'September' => 'September', 
    'October' => 'Oktober', 'November' => 'November', 'December' => 'Desember'
];
$bulan_sekarang = $daftar_bulan[$bulan_inggris];

// tanggal hari ini 
$hari_ini = (int)date('d');

// total jumlah hari di bulan sekarang
$total_hari_bulan_ini = (int)date('t');

// sisa hari
$hari_tersisa = $total_hari_bulan_ini - $hari_ini;
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <style>
        .info-box {
            font-family: Arial, sans-serif;
            background-color: #e8f4f8;
            border-left: 5px solid #2980b9;
            padding: 15px;
            width: 400px;
            margin-top: 20px;
        }
    </style>
</head>
<body>

    <h2>Informasi Waktu</h2>
    <div class="info-box">
        <p>Bulan Sekarang: <strong><?php echo $bulan_sekarang; ?></strong></p>
        <p>Hari ini tanggal: <strong><?php echo $hari_ini; ?></strong> dari <?php echo $total_hari_bulan_ini; ?> hari.</p>
        <p>Sisa hari di bulan ini: <strong style="color: #27ae60;"><?php echo $hari_tersisa; ?> hari lagi</strong>.</p>
    </div>

</body>
</html>`