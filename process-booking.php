<?php
/**
 * Booking Form Processing Script
 * 
 * This script processes the booking form submissions from booking.html
 * and sends an email notification to the site administrator.
 */

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
    
    // Set email recipient
    $recipient = "info@shyamstay.com";
    
    // Set email subject
    $subject = "New Booking Request from $full_name";
    
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
    
    // Send email
    if (mail($recipient, $subject, $email_content, $headers)) {
        // Success response
        http_response_code(200);
        echo "Thank you for your booking request! We'll contact you shortly to confirm your reservation.";
    } else {
        // Error response
        http_response_code(500);
        echo "Oops! Something went wrong and we couldn't send your message.";
    }
    
} else {
    // Not a POST request
    http_response_code(403);
    echo "There was a problem with your submission, please try again.";
}
?>