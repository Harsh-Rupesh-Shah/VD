<?php
$to = "manavrathod115@gmail.com";
$subject = "Test Email";
$message = "This is a test email to check if the mail function is working.";
$headers = "From: no-reply@example.com\r\n";

if (mail($to, $subject, $message, $headers)) {
    echo "Test email sent successfully.";
} else {
    echo "Failed to send test email.";
}
?>
