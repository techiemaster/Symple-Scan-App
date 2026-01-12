
<?php
// Set headers before any output
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

$response = ['success' => false, 'message' => ''];

try {
    $data = json_decode(file_get_contents('php://input'), true);
    
    if (!$data) {
        throw new Exception('Invalid input data');
    }

    $mail = new PHPMailer(true);
    $mail->isSMTP();
    $mail->Host = 'c1102428.sgvps.net';
    $mail->SMTPAuth = true;
    $mail->Username = 'info@symplescan.com';
    $mail->Password = 'Symple2025$';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
    $mail->Port = 465;

    $mail->setFrom('info@symplescan.com', 'SympleScan Contact Form');
    $mail->addAddress('info@symplescan.com');   
    // $mail->addAddress('ooom.mmmof@gmail.com');

    $mail->isHTML(true);

    $mail->Subject = 'New Contact Form Submission from ' . htmlspecialchars($data['name']);
    $mail->Body = "
    <html>
    <body>
        <h2>Contact Form Details:</h2>
        <p><strong>Name:</strong> " . htmlspecialchars($data['name']) . "</p>
        <p><strong>Email:</strong> " . htmlspecialchars($data['email']) . "</p>
        <p><strong>Phone:</strong> " . htmlspecialchars($data['phone']) . "</p>
        <p><strong>Company:</strong> " . htmlspecialchars($data['company']) . "</p>
        <p><strong>Document Type:</strong> " . htmlspecialchars($data['docType']) . "</p>
        <p><strong>Volume:</strong> " . htmlspecialchars($data['volume']) . "</p>
        <p><strong>Source:</strong> " . htmlspecialchars($data['hearAbout']) . "</p>
        <p><strong>Message:</strong><br>" . nl2br(htmlspecialchars($data['message'])) . "</p>
    </body>
    </html>";

    $mail->send();
    $response = [
        'success' => true,
        'message' => 'Email sent successfully'
    ];
} catch (Exception $e) {
    $response = [
        'success' => false,
        'message' => $e->getMessage()
    ];
}

echo json_encode($response);
