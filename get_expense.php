<?php
include 'cors.php';
include 'db.php';

$id = isset($_GET['id']) ? $_GET['id'] : 0;

try {
    $stmt = $pdo->prepare("SELECT * FROM expenses WHERE id = ?");
    $stmt->execute([$id]);
    $expense = $stmt->fetch(PDO::FETCH_ASSOC);

    // 4. Return the record or a clear JSON error if not found
    if ($expense) {
        echo json_encode($expense);
    } else {
        http_response_code(404);
        echo json_encode(['error' => 'Not found']);
    }
} catch(PDOException $e) {
    // 5. Provide database error feedback in JSON format
    http_response_code(500);
    echo json_encode(['error' => 'Database query failed.']);
}
?>
