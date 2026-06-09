<?php
session_start();

// Hapus semua data sesi yang aktif
session_unset();
session_destroy();

// Alihkan pengguna kembali ke halaman login
header('Location: login.php');
exit();
?>