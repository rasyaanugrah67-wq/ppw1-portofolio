<?php
include_once("config.php");

// Jika user ternyata sudah login, langsung dialihkan ke halaman utama index.php
if (isLoggedIn()) { 
    header('Location: index.php'); 
    exit(); 
}

$error = "";

if (isset($_POST['login'])) {
    // Mengamankan input dari SQL Injection
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password'];
    
    // Cari user berdasarkan username ATAU email di database
    $query = "SELECT * FROM users WHERE username = '$username' OR email = '$username'";
    $result = mysqli_query($conn, $query);
    
    if (mysqli_num_rows($result) == 1) {
        $user = mysqli_fetch_assoc($result);
        
        // Verifikasi password input dengan hash bcrypt yang ada di database
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['full_name'] = $user['full_name'];
            
            header('Location: index.php');
            exit();
        }
    }
    // Pesan error disamakan demi keamanan (mencegah teknik user enumeration)
    $error = "Username atau password salah!";
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login Sistem</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f9; padding: 50px; }
        .login-box { width: 300px; margin: 0 auto; padding: 20px; background: #fff; border: 1px solid #ccc; border-radius: 5px; }
        .error-msg { color: red; margin-bottom: 15px; }
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; margin-bottom: 5px; }
        .form-group input { width: 100%; padding: 8px; box-sizing: border-box; }
        button { width: 100%; padding: 10px; background-color: #007bff; color: white; border: none; border-radius: 3px; cursor: pointer; }
        button:hover { background-color: #0056b3; }
    </style>
</head>
<body>

<div class="login-box">
    <h2>Login Sistem</h2>
    
    <?php if (!empty($error)): ?>
        <div class="error-msg"><?= $error; ?></div>
    <?php endif; ?>

    <form method="POST" action="">
        <div class="form-group">
            <label>Username / Email</label>
            <input type="text" name="username" required>
        </div>
        <div class="form-group">
            <label>Password</label>
            <input type="password" name="password" required>
        </div>
        <button type="submit" name="login">Masuk</button>
    </form>
    <p style="text-align: center; font-size: 14px;">Belum punya akun? <a href="register.php">Daftar di sini</a></p>
</div>

</body>
</html>