<?php
session_start();
require_once 'database.php';

if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: admin_login.php');
    exit;
}

// Statisztikák lekérése
$stats = [
    'total_bookings' => $pdo->query("SELECT COUNT(*) FROM bookings")->fetchColumn(),
    'active_bookings' => $pdo->query("
        SELECT COUNT(*) FROM bookings 
        WHERE status = 'confirmed' 
        AND check_in <= CURDATE() 
        AND check_out >= CURDATE()"
    )->fetchColumn(),
    'monthly_revenue' => $pdo->query("
        SELECT SUM(total_price) FROM bookings 
        WHERE MONTH(check_in) = MONTH(CURRENT_DATE())
        AND YEAR(check_in) = YEAR(CURRENT_DATE())
        AND status = 'confirmed'"
    )->fetchColumn(),
    'popular_rooms' => $pdo->query("
        SELECT r.room_number, COUNT(b.id) as booking_count 
        FROM rooms r 
        LEFT JOIN bookings b ON r.id = b.room_id 
        GROUP BY r.id 
        ORDER BY booking_count DESC 
        LIMIT 5"
    )->fetchAll(PDO::FETCH_ASSOC)
];
?>

<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <title>Statisztikák</title>
    <link rel="stylesheet" href="admin_style.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <div class="admin-container">
        <?php include 'admin_nav.php'; ?>
        
        <main class="admin-main">
            <h2>Statisztikák</h2>
            
            <div class="stats-grid">
                <div class="stat-card">
                    <h3>Összes foglalás</h3>
                    <p class="stat-number"><?= $stats['total_bookings'] ?></p>
                </div>
                
                <div class="stat-card">
                    <h3>Aktív foglalások</h3>
                    <p class="stat-number"><?= $stats['active_bookings'] ?></p>
                </div>
                
                <div class="stat-card">
                    <h3>Havi bevétel</h3>
                    <p class="stat-number"><?= number_format($stats['monthly_revenue'], 0, ',', ' ') ?> Ft</p>
                </div>
            </div>

            <div class="chart-container">
                <canvas id="bookingsChart"></canvas>
            </div>

            <div class="popular-rooms">
                <h3>Legnépszerűbb szobák</h3>
                <ul>
                    <?php foreach ($stats['popular_rooms'] as $room): ?>
                    <li>
                        <?= htmlspecialchars($room['room_number']) ?> 
                        (<?= $room['booking_count'] ?> foglalás)
                    </li>