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
        $status = $fetch_info['status'];
        $code = $fetch_info['code'];
        if($status == "verified"){
            if($code != 0){
                header('Location: reset-code.php');
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
    <title><?php echo $fetch_info['name'] ?> | Income</title>
    <link rel="stylesheet" href="../font/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/datetime/1.1.1/css/dataTables.dateTime.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.0.1/css/buttons.dataTables.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #f5f5dc, #d4a373); /* Updated to match home.php */
        }
        .card-header {
            background: linear-gradient(90deg, #d4a373, #a67c52); /* Updated to match home.php */
            color: white;
            font-weight: bold;
        }
        .dataTables_length {
            margin-left: 40px;
            margin-top: 5px;
        }
    </style>
</head>
<body>
    <?php include "nav.php"; ?>
    <br>
    <div class="container">
        <div class="row">
            <p class="h3 mb-3">Income statistics in brief</p>
            <div class="col-3">
                <div class="card border-success mb-3" style="max-width: 18rem;">
                    <div class="card-header h6">This month's income</div>
                    <div class="card-body text-success">
                        <h5 class="card-title"><?php echo date('F Y'); ?></h5>
                        <p class="card-text display-4 text-dark">
                            <?php
                            $date_min = date('Y-m-d', mktime(0, 0, 0, date('m'), 1, date('Y')));
                            $query = "SELECT sum(value) AS amount FROM income WHERE email = '" . $_SESSION['email'] . "' AND date >= '" . $date_min . "';";
                            $res = mysqli_query($con, $query);
                            if (mysqli_num_rows($res) > 0) {
                                $amount = mysqli_fetch_array($res);
                                if($amount["amount"] != NULL)
                                    echo "$" . $amount["amount"];
                                else
                                    echo "$0";
                            }
                            ?>
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-3">
                <div class="card border-primary mb-3" style="max-width: 18rem;">
                    <div class="card-header h6">Previous month's income</div>
                    <div class="card-body text-primary">
                        <h5 class="card-title"><?php echo date('F Y', mktime(0, 0, 0, date('m')-1, 1, date('Y')));?></h5>
                        <p class="card-text display-4 text-dark">
                            <?php
                            $date_min = date('Y-m-d', mktime(0, 0, 0, date('m')-1, 1, date('Y')));
                            $date_max = date('Y-m-d', mktime(0, 0, 0, date('m'), 1, date('Y')));
                            $query = "SELECT sum(value) AS amount FROM income WHERE email = '" . $_SESSION['email'] . "' AND date >= '" . $date_min . "' AND date < '" . $date_max . "';";
                            $res = mysqli_query($con, $query);
                            if (mysqli_num_rows($res) > 0) {
                                $amount = mysqli_fetch_array($res);
                                if($amount["amount"] != NULL)
                                    echo "$" . $amount["amount"];
                                else
                                    echo "$0";
                            }
                            ?>
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-3">
                <div class="card border-info mb-3" style="max-width: 18rem;">
                    <div class="card-header h6">This year's income</div>
                    <div class="card-body">
                        <h5 class="card-title" style="color: #00acc1"><?php echo date("Y",strtotime("-1 year"));?> - <?php echo date('Y'); ?></h5>
                        <p class="card-text display-4 text-dark">
                            <?php
                            $date_min = date('Y-m-d', mktime(0, 0, 0, 1, 1, date('Y')));
                            $query = "SELECT sum(value) AS amount FROM income WHERE email = '" . $_SESSION['email'] . "' AND date >= '" . $date_min . "';";
                            $res = mysqli_query($con, $query);
                            if (mysqli_num_rows($res) > 0) {
                                $amount = mysqli_fetch_array($res);
                                if($amount["amount"] != NULL)
                                    echo "$" . $amount["amount"];
                                else
                                    echo "$0";
                            }
                            ?>
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-3">
                <div class="card border-warning mb-3" style="max-width: 18rem;">
                    <div class="card-header h6">% change from last year</div>
                    <div class="card-body">
                        <h5 class="card-title" style="color: #e65100">Compared to <?php echo date("Y",strtotime("-1 year"));?></h5>
                        <p class="card-text display-4 text-dark">
                            <?php
                            $date_min = date('Y-m-d', mktime(0, 0, 0, 1, 1, date('Y')-1));
                            $date_max = date('Y-m-d', mktime(0, 0, 0, 1, 1, date('Y')));
                            $query1 = "SELECT sum(value) AS amount FROM income WHERE email = '" . $_SESSION['email'] . "' AND date >= '" . $date_min . "' AND date < '" . $date_max . "';";
                            $query2 = "SELECT sum(value) AS amount FROM income WHERE email = '" . $_SESSION['email'] . "' AND date >= '" . $date_max . "';";
                            $res1 = mysqli_query($con, $query1);
                            $res2 = mysqli_query($con, $query2);
                            if (mysqli_num_rows($res1) > 0 && mysqli_num_rows($res2) > 0) {
                                $amount1 = mysqli_fetch_array($res1);
                                $amount2 = mysqli_fetch_array($res2);
                                if($amount1["amount"] == NULL) {
                                    $amount1["amount"] = 0;
                                    echo "-";
                                }
                                else {
                                    if($amount2["amount"] == NULL)
                                        $amount2["amount"] = 0;
                                    $perc = ($amount2["amount"] - $amount1["amount"]) / $amount1["amount"] * 100;
                                    $perc = number_format((float)$perc, 2, '.', '');
                                    echo $perc . "%";
                                }
                            }
                            ?>
                        </p>
                    </div>
                </div>
            </div>

            <div class="row mt-5 mb-2">
                <p class="h3">Graphical Analysis</p>
                <div class="col-6">
                    <canvas id="income-category" width="600" height="400"></canvas>
                </div>
                <div class="col-6">
                    <canvas id="income-trend" width="600" height="400"></canvas>
                </div>
            </div>

            <div class="col-12 mt-5 pt-2">
                <p class="h3">Income records</p>
                <div class="card shadow bg-body rounded" style="width: 100%; background-color: #f9f9f9;"> <!-- Updated background -->
                    <div class="card-body">
                        <table>
                            <tbody>
                                <tr>
                                    <td class="pe-2">Minimum date:</td>
                                    <td><input type="text" id="min" name="min" class="form-control"></td> <!-- Added consistent input style -->
                                </tr>
                                <tr>
                                    <td class="pe-2">Maximum date:</td>
                                    <td><input type="text" id="max" name="max" class="form-control"></td> <!-- Added consistent input style -->
                                </tr>
                            </tbody>
                        </table>
                        <br>
                        <div class="table-responsive">
                            <table id="example"
                                class="table table-bordered text-nowrap text-center table-striped align-middle pt-3">
                                <thead style="background-color: #d4a373; color: white;"> <!-- Updated header style -->
                                    <tr>
                                        <th hidden>ID</th>
                                        <th>Name</th>
                                        <th>Category</th>
                                        <th>Date</th>
                                        <th>Amount</th>
                                        <th>
                                            <a href="#create" class="btn btn-primary p-2" style="height: 2.5em; width: 2.5em;" data-bs-toggle="modal">
                                                <i class="fa fa-lg fa-plus-circle" aria-hidden="true"></i>
                                            </a>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $income_data = "SELECT * FROM income where email = '" . $_SESSION['email'] . "';";
                                    $res = mysqli_query($con, $income_data);
                                    if (mysqli_num_rows($res) > 0) {
                                        while($row = mysqli_fetch_array($res)) {
                                    ?>
                                            <tr>
                                                <td hidden><?php echo $row["id"]; ?></td>
                                                <td><?php echo $row["name"]; ?></td>
                                                <td><?php echo $row["category"]; ?></td>
                                                <td><?php echo $row["date"]; ?></td>
                                                <td><?php echo $row["value"]; ?></td>
                                                <td>
                                                    <button type="submit" class="btn btn-warning p-2 editBtn" style="height: 2.5em; width: 2.5em;">
                                                        <i class="fa fa-lg fa-edit"></i>
                                                    </button>
                                                    <button type="submit" class="btn btn-danger p-2 deleteBtn" style="height: 2.5em; width: 2.5em;">
                                                        <i class="fa fa-lg fa-trash"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                    <?php
                                        }
                                      } else {
                                        echo "<tr><td colspan='6'>No records found</td></tr>";
                                      }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Create Modal -->
    `<div class="modal fade" id="create" tabindex="-1" role="dialog" aria-labelledby="CreateLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="CreateLabel">Add Income Record</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="container">
                    <form action="../View/income.php" method="POST" autocomplete="">
                        <div class="form-group row mt-2">
                            <label for="name" class="col-3 col-form-label"><strong>Name</strong></label>
                            <div class="col-9">
                                <input type="text" class="form-control" id="name-create" name="name" placeholder="Enter Name">
                            </div>
                        </div>
                        <div class="form-group row mt-2">
                            <label for="OnSale" class="col-3 col-form-label"><strong>Category</strong></label>
                            <div class="col-9">
                                <select class="form-control" id="category-create" name="category" onclick="EnableTextBox(this)">
                                    <?php
                                    $email = $_SESSION["email"];
                                    $categories = "SELECT DISTINCT category FROM income WHERE email='$email';";
                                    $res = mysqli_query($con, $categories);
                                    if (mysqli_num_rows($res) > 0) {
                                        while($row = mysqli_fetch_array($res)) {
                                    ?>
                                            <option><?php echo $row["category"] ?></option>
                                    <?php
                                        }
                                    ?>
                                        <option>Other</option>
                                    <?php
                                    } else {
                                    ?>
                                        <option>Create category</option>
                                    <?php
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row mt-2">
                            <label for="new-category" class="col-3 col-form-label"><strong>New Category</strong></label>
                            <div class="col-9">
                                <input type="text" class="form-control" id="new-category-create" name="new-category" placeholder="Create new category" disabled>
                            </div>
                        </div>
                        <div class="form-group row mt-2">
                            <label for="amount" class="col-3 col-form-label"><strong>Amount</strong></label>
                            <div class="col-9">
                                <input type="int" class="form-control" id="amount-create" name="amount" placeholder="Enter Amount">
                            </div>
                        </div>
                        <div class="form-group row mt-2 mb-3">
                            <label for="date" class="col-3 col-form-label"><strong>Date</strong></label>
                            <div class="col-9">
                                <input type="date" class="form-control" id="date-create" name="date" placeholder="Enter Date">
                            </div>
                        </div>
                        <div class="form-group row d-flex">
                            <button type="submit" name="add-income" class="btn btn-primary" style="width: 70px">Save</button>
                            <button type="button" class="btn btn-secondary ms-2" style="width: 70px" data-bs-dismiss="modal">Close</button>
                        </div>
                    </form>
                </div>
            </div>
            </div>
        </div>
    </div>


    <!-- Edit Modal -->
    <div class="modal fade" id="edit" tabindex="-1" role="dialog" aria-labelledby="editLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="editLabel">Edit Income Record</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="container">
                        <form action="../View/income.php" method="POST" autocomplete="">
                            <input hidden id="hiddenInput1" name="hiddenInput1" />
                            <div class="form-group row mt-2">
                                <label for="name" class="col-3 col-form-label"><strong>Name</strong></label>
                                <div class="col-9">
                                    <input type="text" class="form-control" id="name" name="name" placeholder="Enter Name">
                                </div>
                            </div>
                            <div class="form-group row mt-2">
                                <label for="OnSale" class="col-3 col-form-label"><strong>Category</strong></label>
                                <div class="col-9">
                                    <select class="form-control" id="category" name="category" onchange="EnableTextBox(this)">
                                        <?php
                                        $email = $_SESSION["email"];
                                        $categories = "SELECT DISTINCT category FROM income WHERE email='$email';";
                                        $res = mysqli_query($con, $categories);
                                        if (mysqli_num_rows($res) > 0) {
                                            while($row = mysqli_fetch_array($res)) {
                                        ?>
                                                <option><?php echo $row["category"] ?></option>
                                        <?php
                                            }
                                        ?>
                                            <option>Other</option>
                                        <?php
                                        } else {
                                        ?>
                                            <option>Create category</option>
                                        <?php
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row mt-2">
                                <label for="new-category" class="col-3 col-form-label"><strong>New Category</strong></label>
                                <div class="col-9">
                                    <input type="text" class="form-control" id="new-category" name="new-category" placeholder="Create new category" disabled>
                                </div>
                            </div>
                            <div class="form-group row mt-2">
                                <label for="amount" class="col-3 col-form-label"><strong>Amount</strong></label>
                                <div class="col-9">
                                    <input type="int" class="form-control" id="amount" name="amount" placeholder="Enter Amount">
                                </div>
                            </div>
                            <div class="form-group row mt-2 mb-3">
                                <label for="date" class="col-3 col-form-label"><strong>Date</strong></label>
                                <div class="col-9">
                                    <input type="date" class="form-control" id="date" name="date" placeholder="Enter Date">
                                </div>
                            </div>
                            <div class="form-group row d-flex">
                                <button type="submit" name="edit-income" class="btn btn-primary" style="width: 70px">Save</button>
                                <button type="button" class="btn btn-secondary ms-2" style="width: 70px" data-bs-dismiss="modal">Close</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- Delete Modal -->
    <div class="modal fade" id="delete" tabindex="-1" role="dialog" aria-labelledby="deleteLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="deleteLabel">Delete Record</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="container">
                        <h5>Are you sure you want to delete this record?</h5> <br>
                        <form action="../View/income.php" method="POST" autocomplete="">
                            <div class="form-group row d-flex">
                                <input hidden id="hiddenInput2" name="hiddenInput2" />
                                <button type="submit" name="delete-income" class="btn" style="width: 130px; background-color: #df4b4b; color: #ffffff">Delete Record</button>
                                <button type="button" class="btn btn-secondary ms-2" style="width: 70px"  data-bs-dismiss="modal">Close</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart Js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Fetch data for Income by Category chart
        fetch('../API/getIncomeBarGraphData.php')
            .then(response => response.json())
            .then(data => {
                const ctx = document.getElementById('income-category').getContext('2d');
                new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: data.map(d => d.category),
                        datasets: [{
                            label: 'Income by Category',
                            data: data.map(d => d.value),
                            backgroundColor: 'rgba(54, 162, 235, 0.5)',
                            borderColor: 'rgba(54, 162, 235, 1)',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: { position: 'top' },
                            title: { display: true, text: 'Income by Category' }
                        },
                        scales: {
                            y: { beginAtZero: true }
                        }
                    }
                });
            });

        // Fetch data for Income Trend chart
        fetch('../API/getIncomeLineGraphData.php')
            .then(response => response.json())
            .then(data => {
                const ctx = document.getElementById('income-trend').getContext('2d');
                new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: data.map((_, i) => `Month ${i + 1}`),
                        datasets: [{
                            label: 'Income Trend',
                            data: data.map(d => d.value),
                            borderColor: 'rgba(75, 192, 192, 1)',
                            backgroundColor: 'rgba(75, 192, 192, 0.2)',
                            fill: true
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: { position: 'top' },
                            title: { display: true, text: 'Income Trend' }
                        }
                    }
                });
            });
    </script>

    <!-- Bootsrap + JQuery -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>

    <script>
        //Script for delete income
        $(document).ready(function() {
            $(".deleteBtn").on("click", function() {

                $("#delete").modal("show");

                $tr = $(this).closest("tr");
                var data = $tr.children("td").map(function() {
                    return $(this).text();
                }).get();

                $("#hiddenInput2").val(data[0]);
            })

        })

        //Script for edit income
        $(document).ready(function() {
            $(".editBtn").on("click", function() {

                $("#edit").modal("show");

                $tr = $(this).closest("tr");
                var data = $tr.children("td").map(function() {
                    return $(this).text();
                }).get();

                $("#hiddenInput1").val(data[0]);
                $("#name").val(data[1]);
                $("#category").val(data[2]).change()
                $("#date").val(data[3]);
                $("#amount").val(data[4]);
            })

        })
    </script>

    <script>
        function EnableTextBox(ddlModels) {
            var selectedValue = ddlModels.options[ddlModels.selectedIndex].value;
            var create_input = document.getElementById("new-category-create");
            var edit_input = document.getElementById("new-category");
            create_input.disabled = (selectedValue == "Other" || selectedValue == "Create category") ? false : true;
            edit_input.disabled = (selectedValue == "Other" || selectedValue == "Create category") ? false : true;
    }
    </script>

    <script>
        $(document).ready(function () {
            showBarGraph();
        });

        function dynamicColors() {
            var r = Math.floor(Math.random() * 255);
            var g = Math.floor(Math.random() * 255);
            var b = Math.floor(Math.random() * 255);
            return "rgba(" + r + "," + g + "," + b + ", 0.6)";
        }

        function generateColors(a) {
            var pool = [];
            for(i = 0; i < a; i++) {
                pool.push(dynamicColors());
            }
            return pool;
        }

        function showBarGraph()
        {
            $.post("../Controller/getIncomeBarGraphData.php",
            function (data)
            {
                var category = [];
                var value = [];
                for (var i in data) {
                    category.push(data[i].category);
                    value.push(data[i].value);
                }

                var colors = generateColors(category.length); 

                var chartdata = {
                    labels: category,
                    datasets: [
                        {
                            label: 'Income',
                            backgroundColor: colors,
                            borderColor: colors,
                            data: value
                        }
                    ]
                };
                var graphTarget = $("#category");
                var barGraph = new Chart(graphTarget, {
                    type: 'bar',
                    data: chartdata,
                    options: {
                        responsive: false,
                        plugins: {
                            title: {
                                display: true,
                                text: 'Source wise income distribution (1 year)',
                            },
                        },
                    }
                });
            });
        }
    </script>

    <script>
        $(document).ready(function () {
            showLineGraph();
        });

        function showLineGraph() 
        {
            $.post("../Controller/getIncomeLineGraphData.php",
            function (data)
            {
                let months = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
                let currentMonth = new Date().getMonth();
                var value = [];
                var label = [];
                var tempMonth = "";
                for(var i=0; i<7; i++) {
                    tempMonth = (currentMonth - i + 12 ) % 12;
                    label.push(months[tempMonth]);
                }
                label.reverse();
                for (var i in data) {
                    value.push(data[i].value);
                }
                var chartdata = {
                    labels: label,
                    datasets: [
                        {
                            label: 'Income',
                            backgroundColor: '#3e95cd',
                            borderColor: '#3e95cd',
                            hoverBackgroundColor: '#CCCCCC',
                            hoverBorderColor: '#666666',
                            data: value
                        }
                    ]
                };
                var graphTarget = $("#time");
                var lineGraph = new Chart(graphTarget, {
                    type: 'line',
                    data: chartdata,
                    options: {
                        responsive: false,
                        plugins: {
                            title: {
                                display: true,
                                text: 'Income over time (7 months)',
                            },
                        },
                    }
                });
            });
        }
    </script>

    <!-- Datatables -->
    <script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.0.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.0.1/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.0.1/js/buttons.print.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js"></script>
    <script src="https://cdn.datatables.net/datetime/1.1.1/js/dataTables.dateTime.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#example').DataTable( {
                dom: 'Blfrtip',
                "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
                iDisplayLength: -1,
                buttons: [
                    'copy', 'csv', 'excel', 'pdf', 'print'
                ]
            } );
        } );
    </script>
    <script>
        var minDate, maxDate;
        // $("div.toolbar").html('From <input name="min" id="min" type="date" value="0"> / <input name="max" id="max" type="date" value="0">');
        // Custom filtering function which will search data in column four between two values
        $.fn.dataTable.ext.search.push(
            function( settings, data, dataIndex ) {
                var min = minDate.val();
                var max = maxDate.val();
                var date = new Date( data[3] );

                if (
                    ( min === null && max === null ) ||
                    ( min === null && date <= max ) ||
                    ( min <= date   && max === null ) ||
                    ( min <= date   && date <= max )
                ) {
                    return true;
                }
                return false;
            }
        );

        $(document).ready(function() {
            // Create date inputs
            minDate = new DateTime($('#min'), {
                format: 'MMMM Do YYYY'
            });
            maxDate = new DateTime($('#max'), {
                format: 'MMMM Do YYYY'
            });
            // DataTables initialisation
            var table = $('#example').DataTable();

            // Refilter the table
            $('#min, #max').on('change', function () {
                table.draw();
            });
        });
    </script>
       <footer class="bg-dark text-white text-center py-3 mt-5">
        <p class="mb-0">© 2025 Expense Tracker. All Rights Reserved</p>
        <p class="mb-0">College-Project</p>
        <p class="mb-0">Designed By:<br> Sarabjeet Singh<br>Vrutansh Harishbhai </p>
        
        
    </footer>
</body>
</html>