<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Load Composer's autoloader or manually include PHPMailer files
require 'vendor/autoload.php';
// or if you downloaded PHPMailer manually, include the following
// require 'path/to/PHPMailer/src/Exception.php';
// require 'path/to/PHPMailer/src/PHPMailer.php';
// require 'path/to/PHPMailer/src/SMTP.php';

// Output messages
$responses = [];
// Check if the form was submitted
if (isset($_POST['email'], $_POST['subject'], $_POST['name'], $_POST['msg'])) {
    // Validate email address
    if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        $responses[] = 'Email is not valid!';
    }
    // Make sure the form fields are not empty
    if (empty($_POST['email']) || empty($_POST['subject']) || empty($_POST['name']) || empty($_POST['msg'])) {
        $responses[] = 'Please complete all fields!';
    }
    // If there are no errors
    if (!$responses) {
        $mail = new PHPMailer(true);

        try {
            // Server settings
            $mail->isSMTP();                                            // Send using SMTP
            $mail->Host       = 'smtp.example.com';                     // Set the SMTP server to send through
            $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
            $mail->Username   = 'your-email@example.com';               // SMTP username
            $mail->Password   = 'your-email-password';                  // SMTP password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         // Enable TLS encryption; PHPMailer::ENCRYPTION_SMTPS encouraged
            $mail->Port       = 587;                                    // TCP port to connect to

            // Recipients
            $mail->setFrom('noreply@example.com', 'Mailer');
            $mail->addAddress('davehenzy@gmail.com');                   // Add a recipient
            $mail->addReplyTo($_POST['email'], $_POST['name']);

            // Content
            $mail->isHTML(true);                                        // Set email format to HTML
            $mail->Subject = $_POST['subject'];
            $mail->Body    = nl2br($_POST['msg']);                      // Use nl2br to preserve new lines
            $mail->AltBody = $_POST['msg'];

            $mail->send();
            $responses[] = 'Message sent!';
        } catch (Exception $e) {
            $responses[] = "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, minimum-scale=1">
    <title>Contact Form</title>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <form class="contact" method="post" action="">
        <h1>Contact Form</h1>
        <div class="fields">
            <label for="email">
                <i class="fas fa-envelope"></i>
                <input id="email" type="email" name="email" placeholder="Your Email" required>
            </label>
            <label for="name">
                <i class="fas fa-user"></i>
                <input id="name" type="text" name="name" placeholder="Your Name" required>
            </label>
            <label for="subject">
                <i class="fas fa-tag"></i>
                <input id="subject" type="text" name="subject" placeholder="Subject" required>
            </label>
            <label for="msg">
                <i class="fas fa-comment"></i>
                <textarea id="msg" name="msg" placeholder="Message" required></textarea>
            </label>
        </div>
        <?php if ($responses) : ?>
            <p class="responses"><?php echo implode('<br>', $responses); ?></p>
        <?php endif; ?>
        <input type="submit" value="Send">
    </form>
</body>
</html>
