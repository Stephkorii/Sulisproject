<?php
class BookingSystem {
    private $db;

    public function __construct() {
        $this->db = new PDO("mysql:host=localhost;dbname=hotel", "username", "password");
    }

    // Szabad szobák keresése
    public function checkAvailability($checkIn, $checkOut, $roomType = null) {
        $query = "SELECT r.* FROM rooms r 
                 WHERE r.id NOT IN (
                     SELECT room_id FROM bookings 
                     WHERE (check_in <= :check_out AND check_out >= :check_in)
                     AND status = 'confirmed'
                 )";
        
        if ($roomType) {
            $query .= " AND r.room_type = :room_type";
        }

        $stmt = $this->db->prepare($query);
        $stmt->execute([
            'check_in' => $checkIn,
            'check_out' => $checkOut,
            'room_type' => $roomType
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Foglalás létrehozása
    public function createBooking($data) {
        try {
            $this->db->beginTransaction();

            // Vendég adatainak mentése
            $guestStmt = $this->db->prepare("INSERT INTO guests (name, email, phone) VALUES (:name, :email, :phone)");
            $guestStmt->execute([
                'name' => $data['guest_name'],
                'email' => $data['guest_email'],
                'phone' => $data['guest_phone']
            ]);
            $guestId = $this->db->lastInsertId();

            // Foglalás létrehozása
            $bookingStmt = $this->db->prepare("INSERT INTO bookings (room_id, guest_id, check_in, check_out, total_price, status) 
                                             VALUES (:room_id, :guest_id, :check_in, :check_out, :total_price, 'pending')");
            $bookingStmt->execute([
                'room_id' => $data['room_id'],
                'guest_id' => $guestId,
                'check_in' => $data['check_in'],
                'check_out' => $data['check_out'],
                'total_price' => $data['total_price']
            ]);

            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollBack();
            return false;
        }
    }
}