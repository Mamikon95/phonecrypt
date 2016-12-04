<?php
/**
 * Encrypt and add user data to DB
 */
require 'classes/Database.php';
require 'classes/Crypt.php';

$crypt = new Crypt();
$db = new Database();

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
    $list = $db->getAllUsers();
    $comparedEmail = $found = '';
    foreach ($list as $item) {
        $comparedEmail = $crypt->decrypt($item['email']);
        if ($comparedEmail === $email) {
            $result['errors'][] = 'Email already exists, try another one.';
        }
    }
}

// Proceed if everything's alright
if (empty($result['errors'])) {
    $result['success'] = TRUE;
    $email = filter_var($email, FILTER_VALIDATE_EMAIL);
    $phone = trim(strip_tags($phone));
    // Encrypt both Email & Phone
    $emailCrypt = $crypt->encrypt($email);
    $phoneCrypt = $crypt->encrypt($phone);
    // And save 'em
    $db->insertUserData($phoneCrypt, $emailCrypt, $vector);
}

header('Content-Type: application/json');
print json_encode($result);
