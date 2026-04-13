<?php
include 'cors.php';
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $id = $_POST['id']; 
    $item_name = $_POST['item_name'];
    $category = $_POST['category'];
    $amount = $_POST['amount'];
    $expense_date = $_POST['expense_date']; 
    $payment_method = $_POST['payment_method']; 
    $note = $_POST['note']; 

    try {
        $sql = "UPDATE expenses SET item_name=?, category=?, amount=?, expense_date=?, payment_method=?, note=? WHERE id=?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$item_name, $category, $amount, $expense_date, $payment_method, $note, $id]);
        
        echo json_encode(['status' => 'success']);
    } catch(PDOException $e) {
        http_response_code(500);
        echo json_encode(['status' => 'error', 'message' => 'Database update failed.']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
}
?>