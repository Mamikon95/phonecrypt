<?php
/**
 * Retrieve phone from DB and decrypt it
 */
require 'db.php';
require 'classes/Crypt.php';

$crypt = new Crypt();

$result = [
    'success' => FALSE
];
// Retrieve values
$email = filter_input(INPUT_POST, 'email');

if (!$email || filter_var($email, FILTER_VALIDATE_EMAIL) === FALSE) {
    $result['errors'][] = 'Valid email is required';
} else {
    // Proceed if everything's alright
    $email =  filter_var($email, FILTER_VALIDATE_EMAIL);
    $sth = $conn->prepare("SELECT phone, phone_key FROM users WHERE email = '$email' LIMIT 1");
    $sth->execute();
    $data = $sth->fetch(PDO::FETCH_ASSOC);
    if (empty($data)) {
        $result['errors'][] = "Email is not found, try a different one.";
    } else {
        $phone = $crypt->decrypt($data['phone'], $data['phone_key']);
        $result['success'] = TRUE;
        // Compose email
        $to      = $email;
        $subject = 'Your PHONE';
        $message = "<p><strong>Your PHONE is:</strong><br>$phone</p>";
        $headers = 'From: noreply@nomail.com' . "\r\n" .
                   'X-Mailer: PHP/' . phpversion();
        // Send it!
        mail($to, $subject, $message, $headers);
    }
}

header('Content-Type: application/json');
print json_encode($result);