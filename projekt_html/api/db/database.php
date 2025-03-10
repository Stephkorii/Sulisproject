<?php
// database.php

// Adatbázis kapcsolódási konstansok
define('DB_HOST', 'localhost');
define('DB_NAME', 'hotel_db');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_CHARSET', 'utf8mb4');

try {
    // PDO kapcsolat létrehozása
    $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
    
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ];
    
    $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);

} catch(PDOException $e) {
    // Hiba esetén
    die("Kapcsolódási hiba: " . $e->getMessage());
}


function sanitize_input($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}

function format_price($price) {
    return number_format($price, 0, ',', ' ') . ' Ft';
}

function get_room_status_text($status) {
    $statuses = [
        'available' => 'Elérhető',
        'occupied' => 'Foglalt',
        'maintenance' => 'Karbantartás alatt'
    ];
    return $statuses[$status] ?? $status;
}

function get_booking_status_text($status) {
    $statuses = [
        'pending' => 'Függőben',
        'confirmed' => 'Visszaigazolt',
        'cancelled' => 'Lemondva'
    ];
    return $statuses[$status] ?? $status;
}

// Dátum formázás
function format_date($date) {
    return date('Y-m-d', strtotime($date));
}

// Foglalás időtartamának kiszámítása
function calculate_nights($check_in, $check_out) {
    $check_in_date = new DateTime($check_in);
    $check_out_date = new DateTime($check_out);
    $interval = $check_in_date->diff($check_out_date);
    return $interval->days;
}

// Foglalás teljes árának kiszámítása
function calculate_total_price($price_per_night, $check_in, $check_out) {
    $nights = calculate_nights($check_in, $check_out);
    return $price_per_night * $nights;
}
?>