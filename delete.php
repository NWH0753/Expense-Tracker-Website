<?php
include 'cors.php';
include 'db.php';

$id = isset($_GET['id']) ? $_GET['id'] : 0;

if ($id) {
    try {
        $sql = "DELETE FROM expenses WHERE id=?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$id]);
        
        // 4. Return a clear success status for the JavaScript fetch()
        echo json_encode(['status' => 'success']);
    } catch(PDOException $e) {
        http_response_code(500);
        echo json_encode(['status' => 'error', 'message' => 'Database deletion failed.']);
    }
} else {
    // 5. Provide a specific error if no ID is sent
    http_response_code(400);
    echo json_encode(['error' => 'No ID provided']);
}
?>
