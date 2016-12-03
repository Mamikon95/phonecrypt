<?php
/**
 * Encrypt and add phone to DB
 */
require 'db.php';
require 'classes/Crypt.php';

$crypt = new Crypt();

$result = [
    'success' => FALSE
];
// Retrieve values
$phone = filter_input(INPUT_POST, 'phone');
$email = filter_input(INPUT_POST, 'email');

// Sanitize values
if (!$phone) {
    $result['errors'][] = 'Phone is required';
}

$email = filter_var($email, FILTER_VALIDATE_EMAIL);

if ($email === FALSE) {
    $result['errors'][] = 'Valid email is required';
} else {
    // Check if email already exists
    $sth = $conn->prepare("SELECT email FROM users WHERE email = '$email'");
    $sth->execute();
    $data = $sth->fetch(PDO::FETCH_ASSOC);
    if (!empty($data)) {
        $result['errors'][] = 'Email already exists.';
    }
}

// Proceed if everything's alright
if (empty($result['errors'])) {
    $result['success'] = TRUE;
    $email = filter_var($email, FILTER_VALIDATE_EMAIL);
    $phone = trim(strip_tags($phone));
    list($phone, $key) = $crypt->encrypt($phone);
    $conn->exec("INSERT INTO users (phone, phone_key, email) VALUES ('$phone', '$key', '$email')");
}

header('Content-Type: application/json');
print json_encode($result);
