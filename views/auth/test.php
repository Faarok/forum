<?php

$to = 'destinataire@example.com';
$subject = 'Test email';
$message = 'Hello, this is a test email.';
$headers = 'From: webmaster@localhost' . "\r\n" .
           'Reply-To: webmaster@localhost' . "\r\n" .
           'X-Mailer: PHP/' . phpversion();

if (mail($to, $subject, $message, $headers)) {
    echo 'Email sent successfully!';
} else {
    echo 'Failed to send email.';
}