<?php 
require_once "../Controller/controllerUserData.php"; 
require_once "../Controller/controllerIncomeExpenseData.php";
require "../Model/connection.php";
?>
<?php 
$email = $_SESSION['email'];
$password = $_SESSION['password'];
if($email != false && $password != false){
    $sql = "SELECT * FROM usertable WHERE email = '$email'";
    $run_Sql = mysqli_query($con, $sql);
    if($run_Sql){
        $fetch_info = mysqli_fetch_assoc($run_Sql);
        if ($fetch_info) {
            $status = $fetch_info['status'];
            $code = $fetch_info['code'];
            if($status == "verified"){
                if($code != 0){
                    header('Location: reset-code.php');
                }
            }else{
                header('Location: user-otp.php');
            }
        }else{
            header('Location: user-otp.php');
        }
    }
}else{
    header('Location: login-user.php');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo $fetch_info['name'] ?> | Portfolio</title>
    <link rel="stylesheet" href="../font/css/font-awesome.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #f5f5dc, #d4a373); /* Ensure consistency with income.php */
        }
        canvas {
            margin: 0 auto;
        }
        .card-header {
            background: linear-gradient(90deg, #d4a373, #a67c52); /* Ensure consistency with income.php */
            color: white;
            font-weight: bold;
        }
        .chart-container {
            background: linear-gradient(135deg, #f5f5dc, #d4a373);
            padding: 20px;
            border-radius: 10px;
        }
    </style>
</head>
<body>
    <?php include "nav.php"; ?>
    <br>
    <h5 class="ms-3 mb-2 fw-bolder">Welcome <?php echo $fetch_info['name'] ?>,</h5>
    <br>
    <div class="container">
        <div class="card shadow">
            <div class="card-header">
                <ul class="nav nav-tabs card-header-tabs" id="nav-tab" role="tablist">
                    <li class="nav-item">
                        <button class="nav-link active" id="nav-budget-tab" data-bs-toggle="tab" data-bs-target="#nav-budget" type="button" role="tab" aria-controls="nav-budget" aria-selected="true">Budget & Savings</button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link" id="nav-income-tab" data-bs-toggle="tab" data-bs-target="#nav-income" type="button" role="tab" aria-controls="nav-income" aria-selected="false">Income</button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link" id="nav-expense-tab" data-bs-toggle="tab" data-bs-target="#nav-expense" type="button" role="tab" aria-controls="nav-expense" aria-selected="false">Expenses</button>
                    </li>
                </ul>
            </div>

            <div class="card-body">
                <div class="tab-content" id="nav-tabContent">
                    <div class="tab-pane fade show active" id="nav-budget" role="tabpanel" aria-labelledby="nav-budget-tab">
                        <div class="row mt-2">
                            <div class="col-2">
                                <div class="card border-danger mb-3">
                                    <div class="card-header h6">Current Monthly Budget</div>
                                    <div class="card-body text-danger">
                                        <h5 class="card-title"><?php echo date('F Y'); ?></h5>
                                        <p class="card-text display-6 text-dark">
                                            <?php
                                            $date_min = date('Y-m-d', mktime(0, 0, 0, date('m'), 1, date('Y')));
                                            $query = "SELECT budget FROM budget WHERE email = '" . $_SESSION['email'] . "' AND date >= '$date_min';";
                                            $res = mysqli_query($con, $query);
                                            if (mysqli_num_rows($res) > 0) {
                                                $res_data = mysqli_fetch_array($res);
                                                if($res_data["budget"] != NULL)
                                                    echo "$" . $res_data["budget"];
                                                else
                                                    echo "$0";
                                            }
                                            else
                                                echo "$0";
                                            ?>
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-2">
                                <div class="card border-danger mb-3">
                                    <div class="card-header h6">Previous month's budget</div>
                                    <div class="card-body text-danger">
                                        <h5 class="card-title"><?php echo date('F Y', mktime(0, 0, 0, date('m')-1, 1, date('Y')));?></h5>
                                        <p class="card-text display-6 text-dark">
                                            <?php
                                            $date_min = date('Y-m-d', mktime(0, 0, 0, date('m')-1, 1, date('Y')));
                                            $date_max = date('Y-m-d', mktime(0, 0, 0, date('m'), 1, date('Y')));
                                            $query = "SELECT budget FROM budget WHERE email = '" . $_SESSION['email'] . "' AND date >= '$date_min' AND date < '$date_max';";
                                            $res = mysqli_query($con, $query);
                                            if (mysqli_num_rows($res) > 0) {
                                                $res_data = mysqli_fetch_array($res);
                                                if($res_data["budget"] != NULL)
                                                    echo "$" . $res_data["budget"];
                                                else
                                                    echo "$0";
                                            }
                                            else
                                                echo "$0";
                                            ?>
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-2">
                                <div class="card border-success mb-3">
                                    <div class="card-header h6">Previous month's savings<span class="fw-bold">*<span></div>
                                    <div class="card-body text-success">
                                        <h5 class="card-title"><?php echo date('F Y', mktime(0, 0, 0, date('m')-1, 1, date('Y')));?></h5>
                                        <p class="card-text display-6 text-dark">
                                            <?php
                                            $date_min = date('Y-m-d', mktime(0, 0, 0, date('m')-1, 1, date('Y')));
                                            $date_max = date('Y-m-d', mktime(0, 0, 0, date('m'), 1, date('Y')));
                                            $query = "SELECT savings FROM budget WHERE email = '" . $_SESSION['email'] . "' AND date >= '$date_min' AND date < '$date_max';";
                                            $res = mysqli_query($con, $query);
                                            if (mysqli_num_rows($res) > 0) {
                                                $res_data = mysqli_fetch_array($res);
                                                if($res_data["savings"] != NULL)
                                                    echo "$" . $res_data["savings"];
                                                else
                                                    echo "$0";
                                            }
                                            else
                                                echo "$0";
                                            ?>
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-2">
                                <div class="card border-success mb-3">
                                    <div class="card-header h6">% savings last month<span class="fw-bold">*<span></div>
                                    <div class="card-body text-success">
                                        <h5 class="card-title"><?php echo date('F Y', mktime(0, 0, 0, date('m')-1, 1, date('Y')));?></h5>
                                        <p class="card-text display-6 text-dark">
                                            <?php
                                            $date_min = date('Y-m-d', mktime(0, 0, 0, date('m')-1, 1, date('Y')));
                                            $date_max = date('Y-m-d', mktime(0, 0, 0, date('m'), 1, date('Y')));
                                            $query = "SELECT savings, budget FROM budget WHERE email = '" . $_SESSION['email'] . "' AND date >= '$date_min' AND date < '$date_max';";
                                            $res = mysqli_query($con, $query);
                                            if (mysqli_num_rows($res) > 0) {
                                                $res_data = mysqli_fetch_array($res);
                                                if($res_data["budget"] == 0)
                                                    echo '-';
                                                else {
                                                    $perc = ($res_data["savings"] / $res_data["budget"]) * 100;
                                                    $perc = number_format((float)$perc, 2, '.', '');
                                                    echo $perc . "%";
                                                }
                                            }
                                            else {
                                                echo '0%';
                                            }
                                            ?>
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-2">
                                <div class="card border-success mb-3">
                                    <div class="card-header h6">Average savings of last 6 months</div>
                                    <div class="card-body text-success">
                                        <h5 class="card-title"><?php echo date('M', mktime(0, 0, 0, date('m')-5, 1, date('Y')));?> to <?php echo date('M Y', mktime(0, 0, 0, date('m'), 1, date('Y')));?></h5>
                                        <p class="card-text display-6 text-dark">
                                            <?php
                                            $date_min = date('Y-m-d', mktime(0, 0, 0, date('m')-6, 1, date('Y')));
                                            $date_max = date('Y-m-d', mktime(0, 0, 0, date('m'), 1, date('Y')));
                                            $query = "SELECT AVG(savings) AS savings FROM budget WHERE email = '" . $_SESSION['email'] . "' AND date >= '$date_min' AND date < '$date_max';";
                                            $res = mysqli_query($con, $query);
                                            if (mysqli_num_rows($res) > 0) {
                                                $res_data = mysqli_fetch_array($res);
                                                $savings = number_format((float)$res_data["savings"], 2, '.', '');
                                                if($res_data["savings"] != NULL)
                                                    echo "$" . $savings;
                                                else
                                                    echo "$0";
                                            }
                                            else
                                                echo "$0";
                                            ?>
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-2">
                                <div class="card border-success mb-3">
                                    <div class="card-header h6">Total savings of last 6 months</div>
                                    <div class="card-body text-success">
                                        <h5 class="card-title"><?php echo date('M', mktime(0, 0, 0, date('m')-5, 1, date('Y')));?> to <?php echo date('M Y', mktime(0, 0, 0, date('m'), 1, date('Y')));?></h5>
                                        <p class="card-text display-6 text-dark">
                                            <?php
                                            $date_min = date('Y-m-d', mktime(0, 0, 0, date('m')-6, 1, date('Y')));
                                            $date_max = date('Y-m-d', mktime(0, 0, 0, date('m'), 1, date('Y')));
                                            $query = "SELECT SUM(savings) AS savings FROM budget WHERE email = '" . $_SESSION['email'] . "' AND date >= '$date_min' AND date < '$date_max';";
                                            $res = mysqli_query($con, $query);
                                            if (mysqli_num_rows($res) > 0) {
                                                $res_data = mysqli_fetch_array($res);
                                                $savings = $res_data["savings"];
                                                if($res_data["savings"] != NULL)
                                                    echo "$" . $savings;
                                                else
                                                    echo "$0";
                                            }
                                            else
                                                echo "$0";
                                            ?>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <p class="card-text mt-3 mb-0"><span class="fw-bold">* Previous month's savings</span> = Previous month's budget - Previous month's expenses</p>
                        <p class="card-text mb-0"><span class="fw-bold">* % savings of last month</span> = (Previous month's savings &divide; Previous month's budget) &times; 100</p>
                        <br>

                        <?php
                        $date_min = date('Y-m-d', mktime(0, 0, 0, date('m'), 1, date('Y')));
                        $res = mysqli_query($con, "SELECT budget FROM budget WHERE date>='$date_min' AND email='" . $_SESSION['email'] . "' ORDER BY date DESC;");
                        if (mysqli_num_rows($res) > 0) {
                            $res_data = mysqli_fetch_array($res);
                            $budget = $res_data["budget"];
                            $get_expense = "SELECT SUM(value) AS amount FROM expense WHERE date >= '$date_min' AND email = '" . $_SESSION['email'] . "'";
                            $expense = mysqli_query($con, $get_expense);
                            $expense_data = mysqli_fetch_array($expense);
                            if($expense_data["amount"] == NULL) {
                                $expense_data["amount"] = 0;
                            }
                            $perc = ($expense_data["amount"] / $budget) * 100;
                            $perc = number_format((float)$perc, 2, '.', '');
                        }
                        else {
                            $perc = 0;
                        }
                        ?>
                        <?php
                        if($perc > 100) {
                        ?>
                            <svg xmlns="http://www.w3.org/2000/svg" style="display: none;">
                                <symbol id="exclamation-triangle-fill" fill="currentColor" viewBox="0 0 16 16">
                                    <path
                                        d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z" />
                                </symbol>
                            </svg>
                            <div class="alert alert-danger d-flex align-items-center" role="alert" style="max-width: 320px;">
                                <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Danger:">
                                    <use xlink:href="#exclamation-triangle-fill" />
                                </svg>
                                <div>
                                    You have exceeded your budget !!!
                                </div>
                            </div>
                        <?php
                        }
                        ?>
                        <h5 class="mt-1">% of budget spent this month</h5>
                        <div class="progress mb-3" style="height: 20px;">
                        <?php
                        if($perc == 0 && mysqli_num_rows($res) == 0) {
                            echo "* Budget not set !";
                        }
                        ?>
                            <div class="progress-bar" role="progressbar" style="width: <?= $perc ?>%; background-color:#1a237e !important;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">
                                <?= $perc ?>%
                            </div>
                        </div>
                        <br>
                        <a href="#budget_modal" class="btn btn-primary" data-bs-toggle="modal">Set or Change Budget</a>
                    </div>


                    <div class="tab-pane fade" id="nav-income" role="tabpanel" aria-labelledby="nav-income-tab">
                        <div class="card border-primary" style="width: 23rem;">
                            <div class="card-body">
                                <h5 class="card-title mb-0">
                                    Income earned this month: 
                                    <?php
                                        $date_min = date('Y-m-d', mktime(0, 0, 0, date('m'), 1, date('Y')));
                                        $query = "SELECT SUM(value) as amount FROM income WHERE email = '" . $_SESSION['email'] . "' AND date >= '$date_min';";
                                        $res = mysqli_query($con, $query);
                                        if (mysqli_num_rows($res) > 0) {
                                            $res_data = mysqli_fetch_array($res);
                                            if($res_data["amount"] != NULL)
                                                echo "$" . $res_data["amount"];
                                            else
                                                echo "$0";
                                        }
                                        else
                                            echo "$0";
                                    ?>
                                </h5>
                            </div>
                        </div>
                        <br>
                        <h5 class="card-title">Last 10 income entries</h5>
                        <table class="table table-striped table-hover table-bordered align-middle">
                            <thead>
                                <tr>
                                    <th scope="col">Name</th>
                                    <th scope="col">Category</th>
                                    <th scope="col">Date</th>
                                    <th scope="col">Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                    <?php
                                    $income_data = "SELECT * FROM income where email = '" . $_SESSION['email'] . "' ORDER BY date DESC LIMIT 10;";
                                    $res = mysqli_query($con, $income_data);
                                    if (mysqli_num_rows($res) > 0) {
                                        // output data of each row
                                        while($row = mysqli_fetch_array($res)) {
                                    ?>
                                            <tr>
                                                <td><?php echo $row["name"]; ?></td>
                                                <td><?php echo $row["category"]; ?></td>
                                                <td><?php echo $row["date"]; ?></td>
                                                <td><?php echo $row["value"]; ?></td>
                                            </tr>
                                    <?php
                                        }
                                    } 
                                    else
                                        echo "0 results";
                                    ?>
                            </tbody>
                        </table>
                        <a href="income.php" class="btn btn-primary">Show detailed income statistics</a>
                    </div>


                    <div class="tab-pane fade" id="nav-expense" role="tabpanel" aria-labelledby="nav-expense-tab">
                        <div class="card border-danger" style="width: 21rem;">
                            <div class="card-body">
                                <h5 class="card-title mb-0">
                                    This month's expenses: 
                                    <?php
                                        $date_min = date('Y-m-d', mktime(0, 0, 0, date('m'), 1, date('Y')));
                                        $query = "SELECT SUM(value) as amount FROM expense WHERE email = '" . $_SESSION['email'] . "' AND date >= '$date_min';";
                                        $res = mysqli_query($con, $query);
                                        if (mysqli_num_rows($res) > 0) {
                                            $res_data = mysqli_fetch_array($res);
                                            if($res_data["amount"] != NULL)
                                                echo "$" . $res_data["amount"];
                                            else
                                                echo "$0";
                                        }
                                        else
                                            echo "$0";
                                    ?>
                                </h5>
                            </div>
                        </div>
                        <br>
                        <h5 class="card-title">Last 10 expense entries</h5>
                        <table class="table table-striped table-hover table-bordered align-middle">
                            <thead>
                                <tr>
                                    <th scope="col">Name</th>
                                    <th scope="col">Category</th>
                                    <th scope="col">Date</th>
                                    <th scope="col">Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                    <?php
                                    $expense_data = "SELECT * FROM expense where email = '" . $_SESSION['email'] . "' ORDER BY date DESC LIMIT 10;";
                                    $res = mysqli_query($con, $expense_data);
                                    if (mysqli_num_rows($res) > 0) {
                                        // output data of each row
                                        while($row = mysqli_fetch_array($res)) {
                                    ?>
                                            <tr>
                                                <td><?php echo $row["name"]; ?></td>
                                                <td><?php echo $row["category"]; ?></td>
                                                <td><?php echo $row["date"]; ?></td>
                                                <td><?php echo $row["value"]; ?></td>
                                            </tr>
                                    <?php
                                        }
                                    } 
                                    else
                                        echo "0 results";
                                    ?>
                            </tbody>
                        </table>
                        <a href="expense.php" class="btn btn-primary">Show detailed expense statistics</a>
                    </div>
                </div>
            </div>
        </div>
        <br>
        <br>
    </div>

    <div class="container mb-5">
        <h5 class="mb-2">Graphical Analysis: Income Vs Expense</h5>
        <div class="chart-container">
            <canvas id="income-expense" width="1200" height="600"></canvas>
        </div>
    </div>
    <div class="container mb-5">
        <h5 class="mb-2">Graphical Analysis: Budget Vs Savings</h5>
        <div class="chart-container">
            <canvas id="budget-savings" width="1200" height="600"></canvas>
        </div>
    </div>

    <!--Budget Modal-->
    <div class="modal fade" id="budget_modal" tabindex="-1" role="dialog" aria-labelledby="editLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="editLabel">Set or change Budget</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="container">
                    <form action="../View/home.php" method="POST">
                        <div class="form-group row mt-2">
                            <label for="budget" class="col-4 col-form-label"><strong>Budget Value</strong></label>
                            <div class="col-8">
                                <input type="number" class="form-control" id="budget" name="budget">
                            </div>
                        </div>
                        <br>
                        <div class="form-group row d-flex">
                            <button type="submit" class="btn btn-primary" style="width: 70px" name="set-budget">Save</button>
                            <button type="button" class="btn btn-secondary ms-2" style="width: 70px" data-bs-dismiss="modal">Close</button>
                        </div>
                    </form>
                </div>
            </div>
            </div>
        </div>
    </div>


    <!-- Include Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Fetch data for Income vs Expense chart
        fetch('../API/getIncomeExpenseGraphData.php')
            .then(response => response.json())
            .then(data => {
                const ctx = document.getElementById('income-expense').getContext('2d');
                new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: Array.from({ length: data.length }, (_, i) => `Month ${i + 1}`),
                        datasets: [
                            {
                                label: 'Income',
                                data: data.map(d => d[0]),
                                borderColor: 'rgba(75, 192, 192, 1)',
                                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                                fill: true,
                            },
                            {
                                label: 'Expense',
                                data: data.map(d => d[1]),
                                borderColor: 'rgba(255, 99, 132, 1)',
                                backgroundColor: 'rgba(255, 99, 132, 0.2)',
                                fill: true,
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: { position: 'top' },
                            title: { display: true, text: 'Income vs Expense' }
                        }
                    }
                });
            });

        // Fetch data for Budget vs Savings chart
        fetch('../API/getBudgetSavingsGraphData.php')
            .then(response => response.json())
            .then(data => {
                const ctx = document.getElementById('budget-savings').getContext('2d');
                new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: Array.from({ length: data.length }, (_, i) => `Month ${i + 1}`),
                        datasets: [
                            {
                                label: 'Budget',
                                data: data.map(d => d.budget),
                                backgroundColor: 'rgba(54, 162, 235, 0.5)',
                                borderColor: 'rgba(54, 162, 235, 1)',
                                borderWidth: 1,
                            },
                            {
                                label: 'Savings',
                                data: data.map(d => d.savings),
                                backgroundColor: 'rgba(75, 192, 192, 0.5)',
                                borderColor: 'rgba(75, 192, 192, 1)',
                                borderWidth: 1,
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: { position: 'top' },
                            title: { display: true, text: 'Budget vs Savings' }
                        },
                        scales: {
                            y: { beginAtZero: true }
                        }
                    }
                });
            });
    </script>

    <!-- Include Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

       <footer class="bg-dark text-white text-center py-3 mt-5">
        <p class="mb-0">© 2025 Expense Tracker. All Rights Reserved</p>
        <p class="mb-0">College-Project</p>
        <p class="mb-0">Designed By:<br> Sarabjeet Singh<br>Vrutansh Harishbhai </p>
        
        
    </footer>
</body>
</html>