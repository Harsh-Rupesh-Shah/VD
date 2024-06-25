<?php

// Define some constants
define("RECIPIENT_NAME", "Manav Rathod");
define("RECIPIENT_EMAIL", "manavrathod115@gmail.com");

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

// If all values exist, send the email
if ($userName && $senderEmail && $message) {
    $recipient = RECIPIENT_NAME . " <" . RECIPIENT_EMAIL . ">";
    $headers = "From: " . $userName . " <" . $senderEmail . ">";
    $msgBody = "Subject: " . $senderSubject . "\n\nMessage: " . $message;
    $success = mail($recipient, $senderSubject, $msgBody, $headers);

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
