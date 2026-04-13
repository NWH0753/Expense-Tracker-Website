<?php
// index.php (EC2 main page)
?>
<!DOCTYPE html>
<html>
<head>
    <title>Expense Tracker</title>

    <!-- S3 STATIC FILES -->
    <link rel="stylesheet" href="https://sourcebucket5924755.s3.us-east-1.amazonaws.com/style.css">
</head>

<body>
<div class="container">

    <div class="header-banner" style="margin-bottom: 20px;">
        <img src="https://sourcebucket5924755.s3.us-east-1.amazonaws.com/banner.jpg"
             style="width: 100%; height: 150px; object-fit: cover; border-radius: 12px;">
    </div>

    <div class="header-line" style="display:flex;align-items:center;gap:15px;">
        <img src="https://sourcebucket5924755.s3.us-east-1.amazonaws.com/banner.jpg"
             style="width:45px;height:45px;">
        <h1>My Expense Tracker</h1>
    </div>
a
    <div class="top-widgets">
        <div class="widget-box">
            <label>View Spending For:</label>
            <input type="date" id="filter_date">
        </div>

        <div class="widget-box">
            <span>Total Spend on <span id="display_date"></span>:
            RM <span id="total_amount">0.00</span></span>
        </div>
    </div>

    <div class="add-record">
        <a href="create_expense.php" id="add_expense_link">+ Add New Expense</a>
    </div>

    <table>
        <thead>
        <tr>
            <th>Item Name</th>
            <th>Category</th>
            <th>Payment Method</th>
            <th>Amount</th>
            <th>Note</th>
            <th>Actions</th>
        </tr>
        </thead>

        <tbody id="expense_list"></tbody>
    </table>

</div>

<script>
document.addEventListener('DOMContentLoaded', () => {

    const dateInput = document.getElementById('filter_date');
    const displayDate = document.getElementById('display_date');
    const addExpenseLink = document.getElementById('add_expense_link');

    let currentDate = new Date().toISOString().split('T')[0];

    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.has('filter_date')) {
        currentDate = urlParams.get('filter_date');
    }

    dateInput.value = currentDate;

    updateUI(currentDate);
    fetchExpenses(currentDate);

    dateInput.addEventListener('change', (e) => {
        const newDate = e.target.value;

        updateUI(newDate);
        fetchExpenses(newDate);

        window.history.pushState({}, '', `index.php?filter_date=${newDate}`);
    });

    function updateUI(date) {
        displayDate.textContent = date;
        addExpenseLink.href = `create_expense.php?date=${date}`;
    }
});

function fetchExpenses(date) {
    fetch(`http://expenseTracker-ALB-1215808858.us-east-1.elb.amazonaws.com/get_expenses.php?filter_date=${date}`)
        .then(res => res.json())
        .then(data => {

            const tbody = document.getElementById('expense_list');
            const totalSpan = document.getElementById('total_amount');

            tbody.innerHTML = '';
            let total = 0;

            if (!data || data.length === 0 || data.error) {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="6" style="text-align:center;padding:20px;">
                            No expenses recorded for this date.
                        </td>
                    </tr>`;
                totalSpan.textContent = '0.00';
                return;
            }

            data.forEach(exp => {
                total += parseFloat(exp.amount);

                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${escapeHTML(exp.item_name)}</td>
                    <td>${escapeHTML(exp.category)}</td>
                    <td>${escapeHTML(exp.payment_method || '-')}</td>
                    <td><strong>${parseFloat(exp.amount).toFixed(2)}</strong></td>
                    <td>${escapeHTML(exp.note || '')}</td>
                    <td>
                        <a href="update_expense.php?id=${exp.id}">Edit</a> |
                        <a href="#" onclick="deleteExpense(${exp.id});return false;" style="color:red;">Delete</a>
                    </td>
                `;
                tbody.appendChild(row);
            });

            totalSpan.textContent = total.toFixed(2);
        })
        .catch(err => console.error(err));
}

function deleteExpense(id) {
    if (confirm("Are you sure?")) {
        fetch(`http://expenseTracker-ALB-1215808858.us-east-1.elb.amazonaws.com/delete.php?id=${id}`)
            .then(res => res.json())
            .then(data => {
                if (data.status === 'success') {
                    fetchExpenses(document.getElementById('filter_date').value);
                }
            });
    }
}

function escapeHTML(str) {
    if (!str) return '';
    const div = document.createElement('div');
    div.textContent = str;
    return div.innerHTML;
}
</script>

</body>
</html>
