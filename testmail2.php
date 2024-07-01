<?php
// Load Composer's autoloader
require 'vendor/autoload.php';

// Import PHPMailer classes into the global namespace
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Define some constants
define("RECIPIENT_NAME", "Manav Rathod");
define("RECIPIENT_EMAIL", "manavrathod115@gmail.com");
define("SMTP_SERVER", "mail.victoriadevelopers.com"); // Replace with your SMTP server name
define("SMTP_PORT", 587); // Usually 587 for TLS or 465 for SSL
define("SMTP_USERNAME", "info@victoriadevelopers.com"); // Replace with your SMTP username
define("SMTP_PASSWORD", "your-email-password"); // Replace with your SMTP password

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

if ($userName && $senderEmail && $message) {
    // Create a new PHPMailer instance
    $mail = new PHPMailer(true);

    try {
        // Server settings
        $mail->isSMTP();                                        // Set mailer to use SMTP
        $mail->Host = SMTP_SERVER;                              // Specify main and backup SMTP servers
        $mail->SMTPAuth = true;                                 // Enable SMTP authentication
        $mail->Username = SMTP_USERNAME;                        // SMTP username
        $mail->Password = SMTP_PASSWORD;                        // SMTP password
        $mail->SMTPSecure = 'tls';                              // Enable TLS encryption, `ssl` also accepted
        $mail->Port = SMTP_PORT;                                // TCP port to connect to

        // Recipients
        $mail->setFrom(SMTP_USERNAME, $userName);
        $mail->addAddress(RECIPIENT_EMAIL, RECIPIENT_NAME);     // Add a recipient

        // Content
        $mail->isHTML(false);                                   // Set email format to plain text
        $mail->Subject = $senderSubject;
        $mail->Body    = $message;

        // Send the email
        $mail->send();
        header('Location: contact.html?message=Successful');
        exit();
    } catch (Exception $e) {
        error_log("Message could not be sent. Mailer Error: {$mail->ErrorInfo}");
        header('Location: contact.html?message=Failed');
        exit();
    }
} else {
    // Set Location After Unsuccessful Submission
    header('Location: contact.html?message=Failed');
    exit();
}
?>
