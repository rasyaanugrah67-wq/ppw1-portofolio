<?php
include_once("config.php");

// Jika user sudah login, langsung dialihkan ke index.php
if (isLoggedIn()) { 
    header('Location: index.php'); 
    exit(); 
}

$errors = [];
$success = "";

if (isset($_POST['register'])) {
    // Mengamankan input dari karakter aneh / SQL Injection
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $full_name = mysqli_real_escape_string($conn, $_POST['full_name']);
    $password = $_POST['password'];
    $confirm = $_POST['confirm_password'];
    
    // Validasi input kosong atau tidak sesuai kriteria
    if (empty($username)) $errors[] = 'Username tidak boleh kosong';
    if (empty($email)) $errors[] = 'Email tidak boleh kosong';
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Format email tidak valid';
    if (empty($full_name)) $errors[] = 'Nama lengkap tidak boleh kosong';
    if (strlen($password) < 6) $errors[] = 'Password minimal 6 karakter';
    if ($password !== $confirm) $errors[] = 'Konfirmasi password tidak cocok';
    
    // Cek apakah username atau email sudah pernah terdaftar di database
    $check = mysqli_query($conn, "SELECT id FROM users WHERE username='$username' OR email='$email'");
    if (mysqli_num_rows($check) > 0) {
        $errors[] = 'Username atau email sudah terdaftar';
    }
    
    // Jika tidak ada error sama sekali, simpan data ke database
    if (empty($errors)) {
        // Enkripsi password sebelum disimpan demi keamanan
        $hashed = password_hash($password, PASSWORD_DEFAULT);
        $sql = "INSERT INTO users (username, email, full_name, password) VALUES ('$username', '$email', '$full_name', '$hashed')";
        
        if (mysqli_query($conn, $sql)) {
            $success = 'Registrasi berhasil! Silakan <a href="login.php">Login di sini</a>.';
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
    <title>Register Akun Baru</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f9; padding: 50px; }
        .register-box { width: 350px; margin: 0 auto; padding: 20px; background: #fff; border: 1px solid #ccc; border-radius: 5px; }
        .error-msg { color: red; margin-bottom: 10px; font-size: 14px; }
        .success-msg { color: green; margin-bottom: 10px; font-size: 14px; }
        .form-group { margin-bottom: 12px; }
        .form-group label { display: block; margin-bottom: 5px; }
        .form-group input { width: 100%; padding: 8px; box-sizing: border-box; }
        button { width: 100%; padding: 10px; background-color: #28a745; color: white; border: none; border-radius: 3px; cursor: pointer; }
        button:hover { background-color: #218838; }
    </style>
</head>
<body>

<div class="register-box">
    <h2>Daftar Akun Baru</h2>
    
    <?php if(!empty($errors)): ?>
        <?php foreach($errors as $err): ?>
            <div class="error-msg">• <?= $err; ?></div>
        <?php endforeach; ?>
    <?php endif; ?>

    <?php if(!empty($success)): ?>
        <div class="success-msg"><?= $success; ?></div>
    <?php endif; ?>

    <form method="POST" action="">
        <div class="form-group">
            <label>Username</label>
            <input type="text" name="username" value="<?= isset($_POST['username']) ? htmlspecialchars($_POST['username']) : '' ?>" required>
        </div>
        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" value="<?= isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '' ?>" required>
        </div>
        <div class="form-group">
            <label>Nama Lengkap</label>
            <input type="text" name="full_name" value="<?= isset($_POST['full_name']) ? htmlspecialchars($_POST['full_name']) : '' ?>" required>
        </div>
        <div class="form-group">
            <label>Password (Min. 6 Karakter)</label>
            <input type="password" name="password" required>
        </div>
        <div class="form-group">
            <label>Konfirmasi Password</label>
            <input type="password" name="confirm_password" required>
        </div>
        <button type="submit" name="register">Daftar Sekarang</button>
    </form>
    <p style="text-align: center; font-size: 14px;">Sudah punya akun? <a href="login.php">Login di sini</a></p>
</div>

</body>
</html>