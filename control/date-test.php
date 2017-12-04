<?php
    include('connect.php');

    $date = date("Y-M-D");
    $lastWeek = time() - (7 * 24 * 60 * 60);
    $insert_date = strtotime('2018-11-29');

    $query = "INSERT INTO orders(customerid, date_purchased) VALUES (1, $insert_date)";
    mysql_query($query);

    for ($i = 1; $i < 50; $i++) {
        $randOrderID = rand(1, 34);
        $randDays = rand(1, 50);
        $randLot = rand(1, 3);
        $query = "INSERT INTO parkingReservation(lotid, days, orderid) VALUES ($randLot, $randDays, $randOrderID)";
        mysql_query($query);
    }
?>