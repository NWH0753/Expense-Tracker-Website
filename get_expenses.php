<?php
include 'cors.php';
include 'db.php';

$selected_date = isset($_GET['filter_date']) ? $_GET['filter_date'] : date('Y-m-d');

try {
    $stmt = $pdo->prepare("SELECT * FROM expenses WHERE expense_date = ? ORDER BY id DESC");
    $stmt->execute([$selected_date]);
    $expenses = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // 4. Always return a JSON array (even if empty) for the frontend loop
    echo json_encode($expenses ? $expenses : []);
    
} catch(PDOException $e) {
    // 5. Provide database error feedback in JSON format
    http_response_code(500);
    echo json_encode(['error' => 'Database connection failed.', 'details' => $e->getMessage()]);
}
?>
