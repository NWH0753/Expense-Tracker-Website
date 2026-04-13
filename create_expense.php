<?php
include 'cors.php';
include 'db.php';

// -------------------- BACKEND (API) --------------------
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $item_name = $_POST['item_name'];
    $category = $_POST['category'];
    $amount = $_POST['amount'];
    $expense_date = $_POST['expense_date']; 
    $payment_method = $_POST['payment_method'];
    $note = $_POST['note'];

    $sql = "INSERT INTO expenses (item_name, category, amount, expense_date, payment_method, note)
            VALUES (?, ?, ?, ?, ?, ?)";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([$item_name, $category, $amount, $expense_date, $payment_method, $note]);

    echo json_encode(['status' => 'success']);
    exit;
}
?>

<!-- -------------------- FRONTEND (HTML) -------------------- -->
<!DOCTYPE html>
<html>
<head>
    <title>Add Expense</title>

    <!-- S3 CSS (if you use S3) -->
    <link rel="stylesheet" href="https://sourcebucket5924755.s3.us-east-1.amazonaws.com/style.css">
</head>

<body>
<div class="container">

    <div class="header-line"><h1>My Expense Tracker</h1></div>

    <div class="title-box">Add New Expense</div>

    <div class="form-container">
        <form id="expenseForm">

            <input type="hidden" name="expense_date" id="expense_date">

            <div class="form-group">
                <label>Item Name</label>
                <input type="text" name="item_name" required>
            </div>

            <div class="form-group">
                <label>Category</label>
                <select name="category" required>
                    <option value="Food & Dining">Food & Dining</option>
                    <option value="Transportation">Transportation</option>
                    <option value="Utilities">Utilities</option>
                    <option value="Entertainment">Entertainment</option>
                    <option value="Shopping">Shopping</option>
                    <option value="Health & Fitness">Health & Fitness</option>
                </select>
            </div>

            <div class="form-group">
                <label>Payment Method</label>
                <select name="payment_method" required>
                    <option value="Cash">Cash</option>
                    <option value="Credit Card">Credit Card</option>
                    <option value="E-Wallet">E-Wallet</option>
                    <option value="Bank Transfer">Bank Transfer</option>
                </select>
            </div>

            <div class="form-group">
                <label>Amount (RM)</label>
                <input type="number" step="0.01" name="amount" required>
            </div>

            <div class="form-group">
                <label>Note</label>
                <textarea name="note"></textarea>
            </div>

            <button type="submit">Save Expense</button>
        </form>
    </div>

</div>

<script>
const urlParams = new URLSearchParams(window.location.search);
const targetDate = urlParams.get('date') || new Date().toISOString().split('T')[0];

document.getElementById('expense_date').value = targetDate;

document.getElementById('expenseForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const formData = new FormData(this);

    fetch('', {   // SAME FILE (VERY IMPORTANT)
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if (data.status === 'success') {
            window.location.href = `index.php`;
        }
    });
});
</script>

</body>
</html>
