<?php
include 'cors.php';
include 'db.php';

// -------------------- BACKEND (API) --------------------
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // 3. Enable JSON input parsing for fetch() requests
    $input = json_decode(file_get_contents("php://input"), true);
    
    // 4. Hybrid retrieval: checks JSON first, then falls back to traditional $_POST
    $item_name      = isset($input['item_name'])      ? $input['item_name']      : (isset($_POST['item_name']) ? $_POST['item_name'] : null);
    $category       = isset($input['category'])       ? $input['category']       : (isset($_POST['category']) ? $_POST['category'] : null);
    $amount         = isset($input['amount'])         ? $input['amount']         : (isset($_POST['amount']) ? $_POST['amount'] : null);
    $expense_date   = isset($input['expense_date'])   ? $input['expense_date']   : (isset($_POST['expense_date']) ? $_POST['expense_date'] : null);
    $payment_method = isset($input['payment_method']) ? $input['payment_method'] : (isset($_POST['payment_method']) ? $_POST['payment_method'] : null);
    $note           = isset($input['note'])           ? $input['note']           : (isset($_POST['note']) ? $_POST['note'] : '');

    try {
        $sql = "INSERT INTO expenses (item_name, category, amount, expense_date, payment_method, note) 
                VALUES (?, ?, ?, ?, ?, ?)";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([$item_name, $category, $amount, $expense_date, $payment_method, $note]);

        // 5. Consistent JSON response for the frontend
        echo json_encode(['status' => 'success']);
    } catch(PDOException $e) {
        http_response_code(500);
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
    exit;
} else {
    http_response_code(405);
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
}
?>


