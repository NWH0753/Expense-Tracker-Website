<?php
require 'vendor/autoload.php';
use Aws\Ssm\SsmClient;

// Initialize the SSM Client to fetch RDS credentials securely
$ssm = new SsmClient([
    'version' => 'latest',
    'region'  => 'us-east-1' 
]);

try {
    // Retrieve connection details from AWS Parameter Store
    $host   = $ssm->getParameter(['Name' => '/expense-app/db/host'])['Parameter']['Value'];
    $dbname = $ssm->getParameter(['Name' => '/expense-app/db/name'])['Parameter']['Value'];
    $user   = $ssm->getParameter(['Name' => '/expense-app/db/user'])['Parameter']['Value'];
    $pass   = $ssm->getParameter(['Name' => '/expense-app/db/password', 'WithDecryption' => true])['Parameter']['Value'];

    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["error" => "Database connection failed"]);
    exit;
}
?>
