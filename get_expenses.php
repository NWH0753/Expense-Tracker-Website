<?php
include 'db.php';
header('Content-Type: application/json');

// Defends against the 1525 Incorrect Date error
$selected_date = isset($_GET['filter_date']) && !empty($_GET['filter_date']) && $_GET['filter_date'] !== 'undefined' 
                 ? $_GET['filter_date'] 
                 : date('Y-m-d');

try {
    $stmt = $pdo->prepare("SELECT * FROM expenses WHERE expense_date = ? ORDER BY id DESC");
    $stmt->execute([$selected_date]);
    $expenses = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($expenses ? $expenses : []);
} catch(PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Database connection failed.', 'details' => $e->getMessage()]);
}
?>
