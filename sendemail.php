<?php
// Enable error reporting (for debugging)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Define constants for SMTP settings
define("RECIPIENT_NAME", "Harsh Shah");
define("RECIPIENT_EMAIL", "hrsshah04022004@gmail.com");
define("SMTP_SERVER", "mail.victoriadevelopers.com");
define("SMTP_PORT", 587);
define("SMTP_USERNAME", "info@victoriadevelopers.com");
define("SMTP_PASSWORD", "Batball@123");

// Function to sanitize input
function sanitizeInput($input) {
    return htmlspecialchars(strip_tags(trim($input)));
}

// Function to send email using SMTP
function sendSMTPMail($to, $subject, $message, $headers) {
    $smtpServer = SMTP_SERVER;
    $smtpPort = SMTP_PORT;
    $smtpUsername = SMTP_USERNAME;
    $smtpPassword = SMTP_PASSWORD;

    // Open a socket connection to the SMTP server
    $smtpConnection = fsockopen($smtpServer, $smtpPort, $errno, $errstr, 30);
    if (!$smtpConnection) {
        error_log("Failed to connect to SMTP server: $errstr ($errno)");
        return false;
    }

    // Initial server response
    $serverResponse = fgets($smtpConnection, 512);
    error_log("Server response: $serverResponse");

    // Send EHLO command and get server response
    fputs($smtpConnection, "EHLO " . $smtpServer . "\r\n");
    $serverResponse = fgets($smtpConnection, 512);
    error_log("EHLO response: $serverResponse");

    // Start TLS if required
    fputs($smtpConnection, "STARTTLS\r\n");
    $serverResponse = fgets($smtpConnection, 512);
    error_log("STARTTLS response: $serverResponse");

    // Authenticate with the SMTP server
    fputs($smtpConnection, "AUTH LOGIN\r\n");
    $serverResponse = fgets($smtpConnection, 512);
    error_log("AUTH LOGIN response: $serverResponse");

    // Send base64 encoded username
    fputs($smtpConnection, base64_encode($smtpUsername) . "\r\n");
    $serverResponse = fgets($smtpConnection, 512);
    error_log("Username response: $serverResponse");

    // Send base64 encoded password
    fputs($smtpConnection, base64_encode($smtpPassword) . "\r\n");
    $serverResponse = fgets($smtpConnection, 512);
    error_log("Password response: $serverResponse");

    // Set sender and recipient
    fputs($smtpConnection, "MAIL FROM: <" . $smtpUsername . ">\r\n");
    $serverResponse = fgets($smtpConnection, 512);
    error_log("MAIL FROM response: $serverResponse");

    fputs($smtpConnection, "RCPT TO: <" . $to . ">\r\n");
    $serverResponse = fgets($smtpConnection, 512);
    error_log("RCPT TO response: $serverResponse");

    // Send email data
    fputs($smtpConnection, "DATA\r\n");
    $serverResponse = fgets($smtpConnection, 512);
    error_log("DATA response: $serverResponse");

    // Construct email headers and body
    $emailContent = "Subject: " . $subject . "\r\n" . $headers . "\r\n\r\n" . $message . "\r\n";
    fputs($smtpConnection, $emailContent . "\r\n.\r\n");
    $serverResponse = fgets($smtpConnection, 512);
    error_log("Message send response: $serverResponse");

    // Quit SMTP session
    fputs($smtpConnection, "QUIT\r\n");
    $serverResponse = fgets($smtpConnection, 512);
    error_log("QUIT response: $serverResponse");

    fclose($smtpConnection);

    // Check if email was sent successfully (expecting 250 OK response)
    return strpos($serverResponse, '250') !== false;
}

// Read form input (assuming this script is processing form submission)
$userName = isset($_POST['username']) ? sanitizeInput($_POST['username']) : "";
$senderEmail = isset($_POST['email']) ? filter_var(sanitizeInput($_POST['email']), FILTER_VALIDATE_EMAIL) : "";
$senderSubject = isset($_POST['subject']) ? sanitizeInput($_POST['subject']) : "";
$message = isset($_POST['message']) ? sanitizeInput($_POST['message']) : "";

// If all values exist, send the email
if ($userName && $senderEmail && $message) {
    $recipient = RECIPIENT_NAME . " <" . RECIPIENT_EMAIL . ">";
    $headers = "From: " . $userName . " <" . $senderEmail . ">\r\n";
    $msgBody = "Subject: " . $senderSubject . "\r\n\r\nMessage: " . $message;

    // Send email using custom SMTP function
    $success = sendSMTPMail(RECIPIENT_EMAIL, $senderSubject, $msgBody, $headers);

    // Redirect after sending email
    if ($success) {
        header('Location: contact.html?message=Successful');
        exit();
    } else {
        header('Location: contact.html?message=Failed');
        exit();
    }
} else {
    // Redirect if form input is incomplete
    header('Location: contact.html?message=Failed');
    exit();
}
?>
