<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: admin_login.php');
    exit;
}

require_once 'C:/Program Files/XAMPP/htdocs/vizsgaremek/projekt_html/api/db/database.php';

// Foglalások lekérése
$stmt = $pdo->query("
    SELECT b.*, g.name as guest_name, r.room_number 
    FROM bookings b 
    JOIN guests g ON b.guest_id = g.id 
    JOIN rooms r ON b.room_id = r.id 
    ORDER BY b.check_in DESC
");
$bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <title>Admin Vezérlőpult</title>
    <link rel="stylesheet" href="admin_style.css">
</head>
<body>
    <div class="admin-container">
        <nav class="admin-nav">
            <h1>Hotel Admin</h1>
            <ul>
                <li><a href="admin_dashboard.php">Vezérlőpult</a></li>
                <li><a href="admin_bookings.php">Foglalások</a></li>
                <li><a href="admin_rooms.php">Szobák</a></li>
                <li><a href="admin_guests.php">Vendégek</a></li>
                <li><a href="admin_logout.php">Kijelentkezés</a></li>
            </ul>
        </nav>

        <main class="admin-main">
            <h2>Foglalások Áttekintése</h2>
            <div class="stats-container">
                <div class="stat-box">
                    <h3>Mai foglalások</h3>
                    <p>5</p>
                </div>
                <div class="stat-box">
                    <h3>Aktív vendégek</h3>
                    <p>12</p>
                </div>
                <div class="stat-box">
                    <h3>Szabad szobák</h3>
                    <p>8</p>
                </div>
            </div>

            <h3>Legújabb foglalások</h3>
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Foglalás ID</th>
                        <th>Vendég</th>
                        <th>Szoba</th>
                        <th>Érkezés</th>
                        <th>Távozás</th>
                        <th>Státusz</th>
                        <th>Műveletek</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($bookings as $booking): ?>
                    <tr>
                        <td><?= $booking['id'] ?></td>
                        <td><?= htmlspecialchars($booking['guest_name']) ?></td>
                        <td><?= $booking['room_number'] ?></td>
                        <td><?= $booking['check_in'] ?></td>
                        <td><?= $booking['check_out'] ?></td>
                        <td><?= $booking['status'] ?></td>
                        <td>
                            <a href="edit_booking.php?id=<?= $booking['id'] ?>" class="btn-edit">Szerkesztés</a>
                            <a href="delete_booking.php?id=<?= $booking['id'] ?>" class="btn-delete" onclick="return confirm('Biztosan törli?')">Törlés</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </main>
    </div>
</body>
</html>