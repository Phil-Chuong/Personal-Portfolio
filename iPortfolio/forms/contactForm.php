<?php
// Start output buffering
ob_start();

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

require '../assets/vendor/phpMailer/src/Exception.php';
require '../assets/vendor/phpMailer/src/PHPMailer.php';
require '../assets/vendor/phpMailer/src/SMTP.php';

// Clear any accidental output
ob_clean();

// Set JSON header
header('Content-Type: application/json');

$response = ['success' => false, 'message' => ''];

try {
    // Check request method
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Only POST requests are accepted');
    }

    // Get input data from either POST or php://input
    $input = file_get_contents('php://input');
    if (!empty($input)) {
        $data = json_decode($input, true);
    } else {
        $data = $_POST;
    }

    // Check required fields
    $required = ['full_name', 'email', 'subject', 'message'];
    foreach ($required as $field) {
        if (empty($data[$field])) {
            throw new Exception("Please fill in all required fields");
        }
    }

    // Sanitize inputs
    $name = filter_var($data['full_name'], FILTER_SANITIZE_STRING);
    $email = filter_var($data['email'], FILTER_SANITIZE_EMAIL);
    $subject = filter_var($data['subject'], FILTER_SANITIZE_STRING);
    $message = filter_var($data['message'], FILTER_SANITIZE_STRING);

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        throw new Exception("Invalid email format");
    }

    // Configure and send email
    $mail = new PHPMailer(true);
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'phichuong80@gmail.com';
    $mail->Password = 'lytrhtomkwstbjfz';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;

    // SSL certificate verification settings
    // OPTION 1: For local development/testing (less secure)
    $mail->SMTPOptions = [
        'ssl' => [
            'verify_peer' => false,
            'verify_peer_name' => false,
            'allow_self_signed' => true
        ]
    ];
    
    // $mail->SMTPOptions = [
    //     'ssl' => [
    //         'cafile' => 'c:\xampp\apache\bin\cacert.pem', // Update this path to your CA certificate file
    //         'verify_peer' => true,
    //         'verify_peer_name' => true,
    //         'allow_self_signed' => false
    //     ]
    // ];

    $mail->setFrom('phichuong80@gmail.com', 'Contact Form');
    $mail->addAddress('phichuong80@gmail.com');
    $mail->addReplyTo($email, $name);
    $mail->isHTML(true);
    $mail->Subject = "Contact Form: $subject";
    $mail->Body = "<h3>New Contact Form Submission</h3>
                  <p><strong>Name:</strong> $name</p>
                  <p><strong>Email:</strong> $email</p>
                  <p><strong>Subject:</strong> $subject</p>
                  <p><strong>Message:</strong></p>
                  <div>".nl2br($message)."</div>";


    // Plain text version
    $mail->AltBody = "Name: $name\nEmail: $email\nSubject: $subject\nMessage:\n$message";

    if ($mail->send()) {
        $response['success'] = true;
        $response['message'] = 'Message sent successfully!';
    } else {
        throw new Exception('Failed to send email');
    }

} catch (Exception $e) {
    $response['message'] = $e->getMessage();
    error_log('Contact Form Error: ' . $e->getMessage());
}

// Ensure only JSON is output
ob_end_clean();
echo json_encode($response);
exit;