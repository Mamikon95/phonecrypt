<?php
/**
 * Retrieves user data by E-mail and sends it
 */
require 'classes/Database.php';
require 'classes/Crypt.php';

$crypt = new Crypt();
$db = new Database();

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

    // Search through all Emails to find a matching one
    $list = $db->getAllUsers();
    $comparedEmail = $found = '';
    foreach ($list as $item) {
        $comparedEmail = $crypt->decrypt($item['email']);
        if ($comparedEmail === $email) {
            $found = $item;
        }
    }

    if (empty($found)) {
        $result['errors'][] = "Email is not found, try a different one.";
    } else {
        // Proceed if found
        $phone = $crypt->decrypt($found['phone']);
        $result['success'] = TRUE;
        // Compose email
        $to      = $email;
        $subject = 'Your PHONE';
        $message = "Your PHONE is: $phone";
        $headers = 'From: noreply@nomail.com' . "\r\n" .
                   'X-Mailer: PHP/' . phpversion();
        // Send it!
        mail($to, $subject, $message, $headers);
    }
}

header('Content-Type: application/json');
print json_encode($result);