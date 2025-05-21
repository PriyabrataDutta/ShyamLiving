<?php
/**
 * Booking Form Processing Script
 * 
 * This script processes the booking form submissions from booking.html
 * and sends an email notification to the site administrator.
 */

// Only process POST requests

require 'src/PHPMailer.php';
require 'src/SMTP.php';
require 'src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Only process POST requests
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Get form data and sanitize inputs
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

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        http_response_code(400);
        echo "Please enter a valid email address.";
        exit;
    }

    // Send email using PHPMailer
    $mail = new PHPMailer(true);

    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host       = 'smtp.example.com';      // Your SMTP server
        $mail->SMTPAuth   = true;
        $mail->Username   = 'your-email@example.com'; // Your SMTP username
        $mail->Password   = 'your-email-password';    // Your SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        // Recipients
        $mail->setFrom('your-email@example.com', 'Booking Form');
        $mail->addAddress('admin@example.com', 'Site Admin');  // Change to your admin email    
    
        // Build email content
    $email_content = "
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
    
    // Set email headers
    $headers = "From: $full_name <$email>\r\n";
    $headers .= "Reply-To: $email\r\n";
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
    
    $mail->send();
        // Success response
        http_response_code(200);
        echo "Thank you for your booking request! We'll contact you shortly to confirm your reservation.";
    } catch (Exception $e) {
        // Error response
        http_response_code(500);
        echo "Oops! Something went wrong and we couldn't send your message.";
    }
    // Send email
//     if (mail($recipient, $subject, $email_content, $headers)) {
//         // Success response
//         http_response_code(200);
//         echo "Thank you for your booking request! We'll contact you shortly to confirm your reservation.";
//     } else {
//         // Error response
//         http_response_code(500);
//         echo "Oops! Something went wrong and we couldn't send your message.";
//     }
    
// } else {
//     // Not a POST request
//     http_response_code(403);
//     echo "There was a problem with your submission, please try again.";
// }
} else {
    // Not a POST request
    http_response_code(403);
    echo "There was a problem with your submission, please try again.";
}
?>