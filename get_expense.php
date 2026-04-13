<?php
include 'cors.php';
include 'db.php';

$id = isset($_GET['id']) ? $_GET['id'] : 0;
$stmt = $pdo->prepare("SELECT * FROM expenses WHERE id = ?");
$stmt->execute([$id]);
$expense = $stmt->fetch(PDO::FETCH_ASSOC);

echo json_encode($expense ? $expense : ['error' => 'Not found']);
?>