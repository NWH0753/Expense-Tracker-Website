<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, DELETE");
header("Access-Control-Allow-Headers: Content-Type");

function get_param($name) {
    // Uses the server CLI directly, bypassing vendor/autoload issues
    $command = "/usr/bin/aws ssm get-parameter --name \"$name\" --with-decryption --query \"Parameter.Value\" --output text --region us-east-1 2>&1";
    $value = shell_exec($command);
    return ($value !== null) ? trim($value) : null;
}

// Updated to match YOUR parameter names
$host   = get_param('/expense-app/db/host');
$dbname = get_param('/expense-app/db/name');      
$user   = get_param('/expense-app/db/user');
$pass   = get_param('/expense-app/db/password');

try {
    if (empty($host) || empty($user) || empty($pass) || empty($dbname)) {
        throw new Exception("Missing SSM Parameters.");
    }
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(Exception $e) {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => 'DB Connection Failed: ' . $e->getMessage()]);
    exit;
}
?>
