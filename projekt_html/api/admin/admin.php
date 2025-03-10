<?php
session_start();

// Alapértelmezett admin felhasználó létrehozása (ezt éles környezetben módosítsd!)
$default_admin = [
    'username' => 'admin',
    'password' => password_hash('admin123', PASSWORD_DEFAULT)
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Ellenőrzés (éles környezetben ezt adatbázisból kéne!)
    if ($username === $default_admin['username'] && 
        password_verify($password, $default_admin['password'])) {
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_username'] = $username;
        header('Location: admin_dashboard.php');
        exit;
    } else {
        $error = "Hibás felhasználónév vagy jelszó!";
    }
}
?>

<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <title>Admin Bejelentkezés</title>
    <link rel="stylesheet" href="admin_style.css">
</head>
<body>
    <div class="login-container">
        <h2>Admin Bejelentkezés</h2>
        <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>
        <form method="POST">
            <div class="form-group">
                <label>Felhasználónév:</label>
                <input type="text" name="username" required>
            </div>
            <div class="form-group">
                <label>Jelszó:</label>
                <input type="password" name="password" required>
            </div>
            <button type="submit">Bejelentkezés</button>
        </form>
    </div>
</body>
</html>