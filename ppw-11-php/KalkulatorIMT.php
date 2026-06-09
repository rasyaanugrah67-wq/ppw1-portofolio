<?php

function hitungIMT($berat, $tinggiCm) {
    
    $tinggiMéter = $tinggiCm / 100;
    
    
    $imt = $berat / ($tinggiMéter * $tinggiMéter);
    
    
    if ($imt < 18.5) {
        $kategori = 'Kurus';
    } elseif ($imt >= 18.5 && $imt < 25.1) {
        $kategori = 'Normal';
    } elseif ($imt >= 25.1 && $imt < 27.1) {
        $kategori = 'Gemuk';
    } else {
        $kategori = 'Obesitas';
    }
    
    return [
        'skor' => round($imt, 1),
        'kategori' => $kategori
    ];
}

//  Pengujian Fungsi
$berat_badan = 90; // kg
$tinggi_badan = 166; // cm

$hasil_imt = hitungIMT($berat_badan, $tinggi_badan);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <style>
        .card {
            font-family: Arial, sans-serif;
            border: 1px solid #ccc;
            padding: 20px;
            border-radius: 8px;
            width: 300px;
            background-color: #f9f9f9;
        }
        .highlight {
            font-weight: bold;
            color: #2c3e50;
        }
    </style>
</head>
<body>

    <h2>Kalkulator IMT</h2>
    <div class="card">
        <p>Berat Badan: <span class="highlight"><?php echo $berat_badan; ?> kg</span></p>
        <p>Tinggi Badan: <span class="highlight"><?php echo $tinggi_badan; ?> cm</span></p>
        <hr>
        <p>Skor IMT: <span class="highlight"><?php echo $hasil_imt['skor']; ?></span></p>
        <p>Kategori: <span class="highlight" style="color: #e74c3c;"><?php echo $hasil_imt['kategori']; ?></span></p>
    </div>

</body>
</html>