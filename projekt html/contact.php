<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Űrlap adatok lekérése
    $name = $_POST["name"];
    $message = $_POST["message"];

    // E-mail beállítások
    $to = "info@szalloda.hu";
    $subject = "Új üzenet érkezett a weboldalról";
    $headers = "From: " . $name . "\r\n";
    $headers .= "Reply-To: " . $name . "\r\n";
    $headers .= "X-Mailer: PHP/" . phpversion();

    // E-mail tartalom összeállítása
    $email_message = "Név: " . $name . "\n";
    $email_message .= "Üzenet: " . $message . "\n";

    // E-mail küldése
    if (mail($to, $subject, $email_message, $headers)) {
        echo "Köszönjük! Az üzeneted elküldtük.";
    } else {
        echo "Hiba történt az üzenet küldése során. Kérjük, próbáld újra később.";
    }
}
?>