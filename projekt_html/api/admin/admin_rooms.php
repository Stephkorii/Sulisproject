<?php
session_start();
require_once 'database.php';

if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: admin_login.php');
    exit;
}

// Szobák lekérése
$stmt = $pdo->query("SELECT * FROM rooms ORDER BY room_number");
$rooms = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <title>Szobák Kezelése</title>
    <link rel="stylesheet" href="admin_style.css">
</head>
<body>
    <div class="admin-container">
        <?php include 'admin_nav.php'; ?>
        
        <main class="admin-main">
            <div class="header-actions">
                <h2>Szobák Kezelése</h2>
                <a href="add_room.php" class="btn-add">Új szoba hozzáadása</a>
            </div>

            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Szobaszám</th>
                        <th>Típus</th>
                        <th>Kapacitás</th>
                        <th>Ár/Éj</th>
                        <th>Státusz</th>
                        <th>Műveletek</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($rooms as $room): ?>
                    <tr>
                        <td><?= htmlspecialchars($room['room_number']) ?></td>
                        <td><?= htmlspecialchars($room['room_type']) ?></td>
                        <td><?= $room['capacity'] ?></td>
                        <td><?= number_format($room['price_per_night'], 0, ',', ' ') ?> Ft</td>
                        <td><?= $room['status'] ?></td>
                        <td>
                            <a href="edit_room.php?id=<?= $room['id'] ?>" class="btn-edit">Szerkesztés</a>
                            <a href="delete_room.php?id=<?= $room['id'] ?>" class="btn-delete" 
                               onclick="return confirm('Biztosan törli ezt a szobát?')">Törlés</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </main>
    </div>
</body>
</html>