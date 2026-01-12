
<?php
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

    $mail->setFrom('info@symplescan.com', 'SympleScan Order Form');
    $mail->addAddress('info@symplescan.com');

    $mail->isHTML(true);

    $mail->Subject = 'New Order Form Submission from ' . htmlspecialchars($data['companyName']);
    $mail->Body = "
    <html>
    <body>
        <h2>Order Form Details:</h2>
        
        <h3>Sales Information</h3>
        <p><strong>Sales Rep:</strong> " . htmlspecialchars($data['salesRep']) . "</p>

        <h3>Company Information</h3>
        <p><strong>Company Name:</strong> " . htmlspecialchars($data['companyName']) . "</p>
        <p><strong>Company Address:</strong> " . htmlspecialchars($data['companyAddress']) . "</p>
        <p><strong>Contact Person:</strong> " . htmlspecialchars($data['contactPerson']) . "</p>
        <p><strong>Position:</strong> " . htmlspecialchars($data['position']) . "</p>
        <p><strong>Phone:</strong> " . htmlspecialchars($data['phone']) . "</p>
        <p><strong>Email:</strong> " . htmlspecialchars($data['email']) . "</p>

        <h3>Service Details</h3>
        <p><strong>Service Level:</strong> " . htmlspecialchars($data['serviceLevel']) . "</p>
        " . ($data['customAmount'] ? "<p><strong>Custom Amount:</strong> $" . htmlspecialchars($data['customAmount']) . "</p>" : "") . "
        <p><strong>Number of Boxes:</strong> " . htmlspecialchars($data['numberOfBoxes']) . "</p>

        <h3>Pick-Up Details</h3>
        <p><strong>Contact Name:</strong> " . htmlspecialchars($data['pickUpContactName']) . "</p>
        <p><strong>Location:</strong> " . htmlspecialchars($data['pickUpLocation']) . "</p>
        <p><strong>Hours:</strong> " . htmlspecialchars($data['pickUpHours']) . "</p>
        <p><strong>Phone:</strong> " . htmlspecialchars($data['pickUpPhone']) . "</p>
        <p><strong>Email:</strong> " . htmlspecialchars($data['pickUpEmail']) . "</p>

        <h3>Agreement</h3>
        <p><strong>E-Signature:</strong> " . htmlspecialchars($data['signature']) . "</p>
        <p><strong>Terms Accepted:</strong> Yes</p>
    </body>
    </html>";

    $mail->send();
    $response = [
        'success' => true,
        'message' => 'Order submitted successfully'
    ];
} catch (Exception $e) {
    $response = [
        'success' => false,
        'message' => $e->getMessage()
    ];
}

echo json_encode($response);
