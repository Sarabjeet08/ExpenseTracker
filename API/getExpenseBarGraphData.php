<?php
    require_once "../Controller/controllerUserData.php"; 
    require "../Model/connection.php";

    header('Content-Type: application/json');

    $date_min = date('Y-m-d', mktime(0, 0, 0, 1, 1, date('Y')));
    $sqlQuery = "SELECT SUM(value) AS value, category from expense where email = '" . $_SESSION['email'] . "' AND date >= '" . $date_min . "' GROUP BY category;";

    $result = mysqli_query($con, $sqlQuery);

    $data = array();
    foreach ($result as $row) {
        $data[] = $row;
    }

    echo json_encode($data);

?>