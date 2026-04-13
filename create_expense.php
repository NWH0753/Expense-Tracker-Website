<?php
include 'db.php';
header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $input = json_decode(file_get_contents("php://input"), true);
    
    // Checks JSON first, falls back to standard POST
    $item_name      = isset($input['item_name'])      ? $input['item_name']      : $_POST['item_name'];
    $category       = isset($input['category'])       ? $input['category']       : $_POST['category'];
    $amount         = isset($input['amount'])         ? $input['amount']         : $_POST['amount'];
    $expense_date   = isset($input['expense_date'])   ? $input['expense_date']   : $_POST['expense_date'];
    $payment_method = isset($input['payment_method']) ? $input['payment_method'] : $_POST['payment_method'];
    $note           = isset($input['note'])           ? $input['note']           : $_POST['note'];

    try {
        $sql = "INSERT INTO expenses (item_name, category, amount, expense_date, payment_method, note) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$item_name, $category, $amount, $expense_date, $payment_method, $note]);
        
        echo json_encode(['status' => 'success']);
    } catch(PDOException $e) {
        http_response_code(500);
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
}
?>
