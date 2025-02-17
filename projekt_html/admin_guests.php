<?php
session_start();
require_once 'database.php';

if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: admin_login.php');
    exit;
}

// Vendégek lekérése
$stmt = $pdo->query("
    SELECT g.*, COUNT(b.id) as booking_count 
    FROM guests g 
    LEFT JOIN bookings b ON g.id = b.guest_id 
    GROUP BY g.id 
    ORDER BY g.created_at DESC
");
$guests = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <title>Vendégek Kezelése</title>
    <link rel="stylesheet" href="admin_style.css">
</head>
<body>
    <div class="admin-container">
        <?php include 'admin_nav.php'; ?>
        
        <main class="admin-main">
            <div class="header-actions">
                <h2>Vendégek Kezelése</h2>
                <div class="search-box">
                    <input type="text" id="guestSearch" placeholder="Vendég keresése...">
                </div>
            </div>

            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Név</th>
                        <th>Email</th>
                        <th>Telefon</th>
                        <th>Foglalások száma</th>
                        <th>Regisztráció dátuma</th>
                        <th>Műveletek</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($guests as $guest): ?>
                    <tr>
                        <td><?= htmlspecialchars($guest['name']) ?></td>
                        <td><?= htmlspecialchars($guest['email']) ?></td>
                        <td><?= htmlspecialchars($guest['phone']) ?></td>
                        <td><?= $guest['booking_count'] ?></td>
                        <td><?= date('Y-m-d', strtotime($guest['created_at'])) ?></td>
                        <td>
                            <a href="view_guest.php?id=<?= $guest['id'] ?>" class="btn-view">Részletek</a>
                            <a href="edit_guest.php?id=<?= $guest['id'] ?>" class="btn-edit">Szerkesztés</a>
                            <a href="delete_guest.php?id=<?= $guest['id'] ?>" class="btn-delete" 
                               onclick="return confirm('Biztosan törli ezt a vendéget?')">Törlés</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </main>
    </div>

    <script>
    // Vendég keresés
    document.getElementById('guestSearch').addEventListener('keyup', function(e) {
        const searchText = e.target.value.toLowerCase();
        const tableRows = document.querySelectorAll('.admin-table tbody tr');
        
        tableRows.forEach(row => {
            const text = row.textContent.toLowerCase();
            row.style.display = text.includes(searchText) ? '' : 'none';
        });
    });
    </script>
</body>
</html>