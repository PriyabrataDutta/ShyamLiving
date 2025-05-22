<?php
/**
 * Booking Form Processing Script using PHPMailer
 */

require 'src/PHPMailer.php';
require 'src/SMTP.php';
require 'src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Only process POST requests
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Get and sanitize form data
    $full_name = filter_var($_POST['full_name'] ?? '', FILTER_SANITIZE_STRING);
    $email = filter_var($_POST['email'] ?? '', FILTER_SANITIZE_EMAIL);
    $phone = filter_var($_POST['phone'] ?? '', FILTER_SANITIZE_STRING);
    $pg_type = filter_var($_POST['pg_type'] ?? '', FILTER_SANITIZE_STRING);

    // Validate required fields
    if (empty($full_name) || empty($email) || empty($phone) || empty($pg_type)) {
        http_response_code(400);
        echo "Please fill in all required fields.";
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        http_response_code(400);
        echo "Please enter a valid email address.";
        exit;
    }

    // Create a new PHPMailer instance
    $mail = new PHPMailer(true);

    try {
        // SMTP configuration (use your actual SMTP details)
        $mail->isSMTP();
        $mail->Host = 'smtp.example.com';         // Replace with your SMTP host
        $mail->SMTPAuth = true;
        $mail->Username = 'your@email.com';       // Replace with your SMTP username
        $mail->Password = 'yourpassword';         // Replace with your SMTP password
        $mail->SMTPSecure = 'tls';                // Or use 'ssl'
        $mail->Port = 587;                        // Or 465 for SSL

        // Sender and recipient
        $mail->setFrom('your@email.com', 'Website Booking');
        $mail->addAddress('admin@example.com');   // Recipient's email

        // Email content
        $mail->isHTML(true);
        $mail->Subject = 'New Booking Request';
        $mail->Body    = "
            <html>
    <head>
        <title>New Booking Request</title>
        <style>
            body { font-family: Arial, sans-serif; line-height: 1.6; }
            .booking-details { background-color: #f9f9f9; padding: 20px; border-radius: 5px; }
            h2 { color: #c5a47e; }
            table { border-collapse: collapse; width: 100%; }
            th, td { padding: 10px; text-align: left; border-bottom: 1px solid #ddd; }
            th { background-color: #f2f2f2; }
        </style>
    </head>
    <body>
        <h2>New Booking Request</h2>
        <div class='booking-details'>
            <table>
                <tr>
                    <th>Full Name:</th>
                    <td>$full_name</td>
                </tr>
                <tr>
                    <th>Email:</th>
                    <td>$email</td>
                </tr>
                <tr>
                    <th>Phone:</th>
                    <td>$phone</td>
                </tr>
                <tr>
                    <th>Accommodation Type:</th>
                    <td>$pg_type</td>
                </tr>
            </table>
        </div>
    </body>
    </html>
    ";
        $mail->AltBody = "Name: $full_name\nEmail: $email\nPhone: $phone\nPG Type: $pg_type";

        $mail->send();
         // ✅ Redirect to thank you page on success
        header("Location: thank-you.html");
        exit;
        // http_response_code(200);
        // echo "Thank you! Your booking has been received.";

    } catch (Exception $e) {
        http_response_code(500);
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}
