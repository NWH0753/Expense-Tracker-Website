<?php
include 'db.php';
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, DELETE");
header("Access-Control-Allow-Headers: Content-Type");
header('Content-Type: application/json');

$id = isset($_GET['id']) ? $_GET['id'] : 0;

if ($id) {
    $sql = "DELETE FROM expenses WHERE id=?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id]);
    echo json_encode(['status' => 'success']);
} else {
    echo json_encode(['error' => 'No ID provided']);
}
?>