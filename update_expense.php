<?php
include 'cors.php';
include 'db.php';

/* =========================
   BACKEND: UPDATE (POST)
========================= */
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    try {
        $id = $_POST['id'];
        $item_name = $_POST['item_name'];
        $category = $_POST['category'];
        $amount = $_POST['amount'];
        $expense_date = $_POST['expense_date'];
        $payment_method = $_POST['payment_method'];
        $note = $_POST['note'];

        $sql = "UPDATE expenses 
                SET item_name=?, category=?, amount=?, expense_date=?, payment_method=?, note=? 
                WHERE id=?";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            $item_name,
            $category,
            $amount,
            $expense_date,
            $payment_method,
            $note,
            $id
        ]);

        echo json_encode(["status" => "success"]);
        exit;

    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(["status" => "error", "message" => "Database update failed"]);
        exit;
    }
}

/* =========================
   FRONTEND PAGE STARTS HERE
========================= */
?>
<!DOCTYPE html>
<html>
<head>
    <title>Edit Expense</title>

    <!-- S3 STATIC FILE -->
    <link rel="stylesheet" href="https://sourcebucket5924755.s3.us-east-1.amazonaws.com/style.css">
</head>

<body>

<div class="container">

    <div class="header-line"><h1>My Expense Tracker</h1></div>

    <div class="title-box">Edit Expense</div>

    <div class="form-container">

        <form id="updateForm">

            <input type="hidden" name="id" id="expense_id">
            <input type="hidden" name="expense_date" id="expense_date">

            <div class="form-group">
                <label>Item Name</label>
                <input type="text" name="item_name" id="item_name" required>
            </div>

            <div class="form-group">
                <label>Category</label>
                <select name="category" id="category_select" required>
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
                <select name="payment_method" id="payment_method" required>
                    <option value="Cash">Cash</option>
                    <option value="Credit Card">Credit Card</option>
                    <option value="Debit Card">Debit Card</option>
                    <option value="E-Wallet">E-Wallet</option>
                    <option value="Bank Transfer">Bank Transfer</option>
                </select>
            </div>

            <div class="form-group">
                <label>Amount (RM)</label>
                <input type="number" step="0.01" name="amount" id="amount" required>
            </div>

            <div class="form-group">
                <label>Note</label>
                <textarea name="note" id="note"></textarea>
            </div>

            <div class="button-group">
                <a href="#" id="cancel_btn" class="btn btn-cancel">Cancel</a>
                <button type="submit" class="btn btn-save">Update Expense</button>
            </div>

        </form>

    </div>

</div>

<script>
const urlParams = new URLSearchParams(window.location.search);
const expenseId = urlParams.get('id');

/* =========================
   LOAD DATA (GET API)
========================= */
fetch(`http://expensetracker-alb-1215808858.us-east-1.elb.amazonaws.com/get_expense.php?id=${expenseId}`)
.then(res => res.json())
.then(data => {

    if (!data || data.error) {
        alert("Record not found!");
        return;
    }

    document.getElementById('expense_id').value = data.id;
    document.getElementById('expense_date').value = data.expense_date;

    document.getElementById('item_name').value = data.item_name;
    document.getElementById('amount').value = data.amount;
    document.getElementById('category_select').value = data.category;
    document.getElementById('payment_method').value = data.payment_method || 'Cash';
    document.getElementById('note').value = data.note || '';

    document.getElementById('cancel_btn').href =
        `index.php?filter_date=${data.expense_date}`;
});

/* =========================
   UPDATE SUBMIT
========================= */
document.getElementById('updateForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const formData = new FormData(this);

    fetch("http://expensetracker-alb-1215808858.us-east-1.elb.amazonaws.com/update_expense.php", {
        method: "POST",
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if (data.status === "success") {
            window.location.href =
                `index.php?filter_date=${formData.get('expense_date')}`;
        }
    });
});
</script>

</body>
</html>
