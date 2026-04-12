<?php
include 'db.php';
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, DELETE");
header("Access-Control-Allow-Headers: Content-Type");
header('Content-Type: application/json');

$selected_date = isset($_GET['filter_date']) ? $_GET['filter_date'] : date('Y-m-d');

try {
    $stmt = $pdo->prepare("SELECT * FROM expenses WHERE expense_date = ? ORDER BY id DESC");
    $stmt->execute([$selected_date]);
    $expenses = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($expenses);
    
} catch(PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Database connection failed.']);
}
?>