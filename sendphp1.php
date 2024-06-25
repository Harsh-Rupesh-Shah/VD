<?php
// If the form is submitted
if (isset($_POST['submit-form'])) {
  // Set variables from the form
  $username = $_POST['username'];
  $email = $_POST['email'];
  $subject = $_POST['subject'];
  $message = $_POST['message'];

  // Email configurations (replace with your own)
  $to = 'manavrathod115@gmail.com'; // Replace with your recipient email
  $from = $email; // Replace with your email
  $fromName = 'Victoria Developers'; // Replace with your website name

  // Prepare the email body
  $body = "Name: $username \n";
  $body .= "Email: $email \n";
  $body .= "Subject: $subject \n";
  $body .= "Message: \n $message";

  // Set email headers
  $headers = "From: $fromName <$from>";
  $headers .= "Reply-To: $email \r\n";
  $headers .= "Content-Type: text/plain; charset=UTF-8 \r\n";

  // Try to send the email
  if (mail($to, $subject, $body, $headers)) {
    // Email sent successfully
    echo 'Thanks for contacting us! We will be in touch shortly.';
  } else {
    // Email sending failed
    echo 'There was an error sending your message. Please try again later.';
  }
}
?>
