<?php
use PHPMailer\PHPMailer\PHPMailer;

class EmailNotification {
    private $mailer;

    public function __construct() {
        $this->mailer = new PHPMailer(true);
        // Email beállítások...
    }

    public function sendBookingConfirmation($booking, $guest) {
        $this->mailer->addAddress($guest['email']);
        $this->mailer->Subject = 'Foglalás visszaigazolása';
        
        $body = $this->getBookingConfirmationTemplate($booking, $guest);
        $this->mailer->Body = $body;
        
        return $this->mailer->send();
    }
}