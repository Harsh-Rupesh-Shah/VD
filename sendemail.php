<?php

// Define some constants
define("RECIPIENT_NAME", "Manav Rathod");
define("RECIPIENT_EMAIL", "manavrathod115@gmail.com");
define("SMTP_SERVER", "mail.victoriadevelopers.com"); // Replace with your SMTP server
define("SMTP_PORT", 587); // Usually 587 for TLS or 465 for SSL
define("SMTP_USERNAME", "info@victoriadevelopers.com"); // Replace with your SMTP username
define("SMTP_PASSWORD", "OS56xr12@"); // Replace with your SMTP password

// Function to sanitize input
function sanitizeInput($input) {
    return htmlspecialchars(strip_tags(trim($input)));
}

// Read the form values
$success = false;
$userName = isset($_POST['username']) ? sanitizeInput($_POST['username']) : "";
$senderEmail = isset($_POST['email']) ? filter_var(sanitizeInput($_POST['email']), FILTER_VALIDATE_EMAIL) : "";
$senderSubject = isset($_POST['subject']) ? sanitizeInput($_POST['subject']) : "";
$message = isset($_POST['message']) ? sanitizeInput($_POST['message']) : "";

// Function to send email using SMTP
function sendSMTPMail($to, $subject, $message, $headers) {
    $smtpServer = SMTP_SERVER;
    $smtpPort = SMTP_PORT;
    $smtpUsername = SMTP_USERNAME;
    $smtpPassword = SMTP_PASSWORD;

    $smtpConnection = fsockopen($smtpServer, $smtpPort, $errno, $errstr, 30);
    if (!$smtpConnection) {
        echo "Failed to connect to SMTP server: $errstr ($errno)";
        return false;
    }

    fputs($smtpConnection, "EHLO " . $smtpServer . "\r\n");
    fgets($smtpConnection, 512);

    fputs($smtpConnection, "AUTH LOGIN\r\n");
    fgets($smtpConnection, 512);
    fputs($smtpConnection, base64_encode($smtpUsername) . "\r\n");
    fgets($smtpConnection, 512);
    fputs($smtpConnection, base64_encode($smtpPassword) . "\r\n");
    fgets($smtpConnection, 512);

    fputs($smtpConnection, "MAIL FROM: <" . $smtpUsername . ">\r\n");
    fgets($smtpConnection, 512);
    fputs($smtpConnection, "RCPT TO: <" . $to . ">\r\n");
    fgets($smtpConnection, 512);
    fputs($smtpConnection, "DATA\r\n");
    fgets($smtpConnection, 512);
    fputs($smtpConnection, "Subject: " . $subject . "\r\n" . $headers . "\r\n" . $message . "\r\n.\r\n");
    fgets($smtpConnection, 512);
    fputs($smtpConnection, "QUIT\r\n");

    $response = fgets($smtpConnection, 512);
    fclose($smtpConnection);

    return strpos($response, '250') !== false;
}

// If all values exist, send the email
if ($userName && $senderEmail && $message) {
    $recipient = RECIPIENT_NAME . " <" . RECIPIENT_EMAIL . ">";
    $headers = "From: " . $userName . " <" . $senderEmail . ">";
    $msgBody = "Subject: " . $senderSubject . "\n\nMessage: " . $message;
    $success = sendSMTPMail($recipient, $senderSubject, $msgBody, $headers);

    // Set Location After Successful Submission
    if ($success) {
        header('Location: contact.html?message=Successful');
        exit();
    } else {
        header('Location: contact.html?message=Failed');
        exit();
    }
} else {
    // Set Location After Unsuccessful Submission
    header('Location: contact.html?message=Failed');
    exit();
}

?>
