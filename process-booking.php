<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // Sanitize and validate input
    $full_name = trim(filter_var($_POST['full_name'] ?? '', FILTER_SANITIZE_STRING));
    $email     = trim(filter_var($_POST['email'] ?? '', FILTER_SANITIZE_EMAIL));
    $phone     = trim(filter_var($_POST['phone'] ?? '', FILTER_SANITIZE_STRING));
    $pg_type   = trim(filter_var($_POST['pg_type'] ?? '', FILTER_SANITIZE_STRING));

    // Basic validation
    if (empty($full_name) || !filter_var($email, FILTER_VALIDATE_EMAIL) || empty($phone) || empty($pg_type)) {
        http_response_code(400);
        echo x"Invalid or missing form data.";
        exit;
    }

    // Send data to Google Sheets via Apps Script
    $postData = json_encode([
        'full_name' => $full_name,
        'email'     => $email,
        'phone'     => $phone,
        'pg_type'   => $pg_type
    ]);

    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, 'https://script.google.com/macros/s/AKfycbyfw9Yy-VJkTfhmoaJZIa-GzI7Qz1qmN90UBpBRKS3K6i-3ycsVXZRp2kgxPfVynwpl_g/exec'); // Replace this!
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    // checking the status code
    
    var_dump($httpCode);
    var_dump($response);
    exit;
    
    if ($httpCode === 200 && strpos($response, 'Success') !== false) {
    echo '<!DOCTYPE html>
    <html>
    <head>
        <title>Redirecting...</title>
        <script>
            // Redirect after 1 second delay (optional)
            setTimeout(function() {
                window.location.href = "/thank-you.html";
            }, 1000);
        </script>
    </head>
    <body>
        <p>Thank you! Redirecting you to the thank you page...</p>
    </body>
    </html>';
    exit;
} else {
    http_response_code(500);
    echo "There was a problem submitting the form. Please try again.";
    exit;
}


}
?>
