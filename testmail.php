<?php
// Define SMTP settings
$smtpServer = 'mail.victoriadevelopers.com';
$smtpPort = 587;
$smtpUsername = 'info@victoriadevelopers.com';
$smtpPassword = 'Batball@123';

// Sender and recipient details
$from = 'info@victoriadevelopers.com';
$to = 'hrsshah04022004@gmail.com';
$subject = 'Test Email';
$message = 'This is a test email.';

// Attempt to establish SMTP connection
$smtpConnection = fsockopen($smtpServer, $smtpPort, $errno, $errstr, 30);
if (!$smtpConnection) {
    die("Failed to connect to SMTP server: $errstr ($errno)");
}

// SMTP commands
$response = fgets($smtpConnection, 512); // Server response
fputs($smtpConnection, "EHLO example.com\r\n");
$response = fgets($smtpConnection, 512); // Server response
fputs($smtpConnection, "AUTH LOGIN\r\n");
$response = fgets($smtpConnection, 512); // Server response
fputs($smtpConnection, base64_encode($smtpUsername) . "\r\n");
$response = fgets($smtpConnection, 512); // Server response
fputs($smtpConnection, base64_encode($smtpPassword) . "\r\n");
$response = fgets($smtpConnection, 512); // Server response
fputs($smtpConnection, "MAIL FROM: <$from>\r\n");
$response = fgets($smtpConnection, 512); // Server response
fputs($smtpConnection, "RCPT TO: <$to>\r\n");
$response = fgets($smtpConnection, 512); // Server response
fputs($smtpConnection, "DATA\r\n");
$response = fgets($smtpConnection, 512); // Server response
fputs($smtpConnection, "Subject: $subject\r\n\r\n$message\r\n.\r\n");
$response = fgets($smtpConnection, 512); // Server response
fputs($smtpConnection, "QUIT\r\n");
fclose($smtpConnection);

// Check for success (response code 250 indicates success)
if (strpos($response, '250') !== false) {
    echo "Email sent successfully!";
} else {
    echo "Failed to send email. Server response: $response";
}
?>
