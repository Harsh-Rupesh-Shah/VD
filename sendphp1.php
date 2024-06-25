<?php
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $to = "manavrathod115@gmail.com"; // Change this to your email address
    $subject = "Contact Form Submission";

    $name = $_POST['username'];
    $email = $_POST['email'];
    $subject = $_POST['subject'];
    $message = $_POST['message'];

    $headers = "From: $email\r\n";
    $headers .= "Reply-To: $email\r\n";
    $headers .= "Content-Type: text/html; charset=UTF-8\r\n";

    $body = "<h2>Contact Form Submission</h2>
             <p><strong>Name:</strong> {$name}</p>
             <p><strong>Email:</strong> {$email}</p>
             <p><strong>Subject:</strong> {$subject}</p>
             <p><strong>Message:</strong><br>{$message}</p>";

    if(mail($to, $subject, $body, $headers)) {
        echo "<script>alert('Your message has been sent successfully!'); window.location = 'contact.html';</script>";
    } else {
        echo "<script>alert('There was an error sending your message. Please try again later.'); window.location = 'contact.html';</script>";
    }
}
?>
