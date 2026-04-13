<?php
include 'cors.php';
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $item_name = $_POST['item_name'];
    $category = $_POST['category'];
    $amount = $_POST['amount'];
    $expense_date = $_POST['expense_date']; 
    $payment_method = $_POST['payment_method'];
    $note = $_POST['note'];

    $sql = "INSERT INTO expenses (item_name, category, amount, expense_date, payment_method, note) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$item_name, $category, $amount, $expense_date, $payment_method, $note]);
    
    echo json_encode(['status' => 'success']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
}
?>