<?php
require 'vendor/autoload.php';

use Aws\Ssm\SsmClient;

// Create SSM client
$ssm = new SsmClient([
    'version' => 'latest',
    'region' => 'us-east-1' // change if your AWS region is different
]);

try {

    // Get values from Parameter Store
    $host = $ssm->getParameter([
        'Name' => '/expense-app/db/host'
    ])['Parameter']['Value'];

    $dbname = $ssm->getParameter([
        'Name' => '/expense-app/db/name'
    ])['Parameter']['Value'];

    $user = $ssm->getParameter([
        'Name' => '/expense-app/db/user'
    ])['Parameter']['Value'];

    $pass = $ssm->getParameter([
        'Name' => '/expense-app/db/password',
        'WithDecryption' => true
    ])['Parameter']['Value'];

    // Connect to RDS
    $pdo = new PDO(
        "mysql:host=$host;dbname=$dbname",
        $user,
        $pass
    );

    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch(Exception $e) {
    die("Database connection failed: " . $e->getMessage());
}
?>