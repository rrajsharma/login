<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Include the PHPMailer autoloader (if using Composer)
require 'vendor/autoload.php';  // Replace with your PHPMailer path if you downloaded it manually

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect form data
    $name = htmlspecialchars($_POST['name']);
    $email = htmlspecialchars($_POST['email']);
    $message = htmlspecialchars($_POST['message']);
    
    // Check if voice message file is uploaded
    $voiceMessage = isset($_FILES['voiceMessage']) ? $_FILES['voiceMessage'] : null;
    if ($voiceMessage && $voiceMessage['error'] == 0) {
        $voiceMessageFile = $voiceMessage['tmp_name'];
        $voiceMessageName = $voiceMessage['name'];
    } else {
        $voiceMessageFile = null;
        $voiceMessageName = null;
    }

    // Create a new PHPMailer instance
    $mail = new PHPMailer(true);

    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';  // Use Gmail's SMTP server
        $mail->SMTPAuth = true;
        $mail->Username = 'your-email@gmail.com'; // Your Gmail address
        $mail->Password = 'your-app-password'; // Your App Password (if 2FA is enabled)
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587; // SMTP port for Gmail

        // Recipients
        $mail->setFrom('no-reply@yourdomain.com', 'Website Contact Form');  // Change this to your email
        $mail->addAddress($email);  // Add user's email address
        $mail->addAddress('Kaizenngv@gmail.com');  // Add Kaizenngv email address

        // Content
        $mail->isHTML(false);  // Set to false to send plain text email
        $mail->Subject = 'New Contact Form Submission';
        $mail->Body    = "Name: $name\nEmail: $email\nMessage: $message";

        // Add voice message attachment
        if ($voiceMessageFile) {
            $mail->addAttachment($voiceMessageFile, $voiceMessageName);  // Attach the voice message
        }

        // Send the email
        $mail->send();
        echo 'Message has been sent to both the user and the admin!';
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}
?>
