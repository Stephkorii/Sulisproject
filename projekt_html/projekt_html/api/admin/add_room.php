<?php
session_start();
require_once 'C:/Program Files/XAMPP/htdocs/vizsgaremek/projekt_html/api/db/database.php';

if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: admin_login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $stmt = $pdo->prepare("
            INSERT INTO rooms (room_number, room_type, capacity, price_per_night, description, amenities, status)
            VALUES (:room_number, :room_type, :capacity, :price_per_night, :description, :amenities, :status)
        ");
        
        $stmt->execute([
            'room_number' => $_POST['room_number'],
            'room_type' => $_POST['room_type'],
            'capacity' => $_POST['capacity'],
            'price_per_night' => $_POST['price_per_night'],
            'description' => $_POST['description'],
            'amenities' => $_POST['amenities'],
            'status' => $_POST['status']
        ]);

        header('Location: admin_rooms.php?success=1');
        exit;
    } catch (PDOException $e) {
        $error = "Hiba történt: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <title>Új szoba hozzáadása</title>
    <link rel="stylesheet" href="admin_style.css">
</head>
<body>
    <div class="admin-container">
        <?php include 'admin_nav.php'; ?>
        
        <main class="admin-main">
            <h2>Új szoba hozzáadása</h2>
            
            <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>
            
            <form class="admin-form" method="POST">
                <div class="form-group">
                    <label>Szobaszám:</label>
                    <input type="text" name="room_number" required>
                </div>
                
                <div class="form-group">
                    <label>Szoba típusa:</label>
                    <select name="room_type" required>
                        <option value="single">Egyágyas</option>
                        <option value="double">Kétágyas</option>
                        <option value="suite">Lakosztály</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label>Kapacitás:</label>
                    <input type="number" name="capacity" min="1" required>
                </div>
                
                <div class="form-group">
                    <label>Ár/Éjszaka (Ft):</label>
                    <input type="number" name="price_per_night" min="0" required>
                </div>
                
                <div class="form-group">
                    <label>Felszereltség:</label>
                    <textarea name="amenities" rows="3" placeholder="Pl.: WiFi, TV, Minibár"></textarea>
                </div>
                
                <div class="form-group">
                    <label>Státusz:</label>
                    <select name="status" required>
                        <option value="available">Elérhető</option>
                        <option value="occupied">Foglalt</option>
                        <option value="maintenance">Karbantartás alatt</option>
                    </select>
                </div>
                
                <div class="form-actions">
                    <button type="submit" class="btn-add">Szoba hozzáadása</button>
                    <a href="admin_rooms.php" class="btn-cancel">Mégse</a>
                </div>
            </form>
        </main>
    </div>
</body>
</html>