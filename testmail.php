<?php
// SMTP server settings
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

// Capture server response
$response = fgets($smtpConnection, 512);
echo "Initial server response: $response<br>";

// Send EHLO command and capture server response
fputs($smtpConnection, "EHLO example.com\r\n");
$response = fgets($smtpConnection, 512);
echo "EHLO response: $response<br>";

// Send AUTH LOGIN command and capture server response
fputs($smtpConnection, "AUTH LOGIN\r\n");
$response = fgets($smtpConnection, 512);
echo "AUTH LOGIN response: $response<br>";

// Send base64 encoded username and capture server response
fputs($smtpConnection, base64_encode($smtpUsername) . "\r\n");
$response = fgets($smtpConnection, 512);
echo "Username response: $response<br>";

// Send base64 encoded password and capture server response
fputs($smtpConnection, base64_encode($smtpPassword) . "\r\n");
$response = fgets($smtpConnection, 512);
echo "Password response: $response<br>";

// Send MAIL FROM command and capture server response
fputs($smtpConnection, "MAIL FROM: <$from>\r\n");
$response = fgets($smtpConnection, 512);
echo "MAIL FROM response: $response<br>";

// Send RCPT TO command and capture server response
fputs($smtpConnection, "RCPT TO: <$to>\r\n");
$response = fgets($smtpConnection, 512);
echo "RCPT TO response: $response<br>";

// Construct email headers and body
$emailContent = "Subject: $subject\r\n";
$emailContent .= "From: $from\r\n";
$emailContent .= "\r\n"; // End of headers, empty line before message body
$emailContent .= "$message\r\n";

// Send DATA command and capture server response
fputs($smtpConnection, "DATA\r\n");
$response = fgets($smtpConnection, 512);
echo "DATA response: $response<br>";

// Send email content and end with period (.)
fputs($smtpConnection, $emailContent . "\r\n.\r\n");
$response = fgets($smtpConnection, 512);
echo "Message send response: $response<br>";


// Send QUIT command and capture server response
fputs($smtpConnection, "QUIT\r\n");
$response = fgets($smtpConnection, 512);
echo "QUIT response: $response<br>";

// Close SMTP connection
fclose($smtpConnection);

// Check for success (response code 250 indicates success)
if (strpos($response, '250') !== false) {
    echo "Email sent successfully!";
} else {
    echo "Failed to send email. Server response: $response";
}
?>
