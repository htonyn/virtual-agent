<?php
include('connect.php');

$query = "CREATE TABLE IF NOT EXISTS flights(flightnumber int(6), depart VARCHAR(3), arrive VARCHAR(3), PRIMARY KEY (flightnumber))";
mysql_query($query);
$query = "CREATE TABLE IF NOT EXISTS seats(flightnumber int(6), seatnumber int(3), customerid int(6))";
mysql_query($query);

for($i = 1; $i <= 60; $i++) {
    // Test Data
    // Each flight will have a dummy set of 60 seats, 10 for first class, 20 for business class, then 30 for economy class
    // First Class: 01-10
    // Business Class: 11-30
    // Economy Class: 31-60
    // Flight 100000 will have every 3rd seat available
    if (($i%3) != 0) {
        $seat_query = "INSERT INTO seats(flightnumber, seatnumber, customerid) VALUES (100000, $i, 1)";
        mysql_query($seat_query);
    }
    // Flight 200000 is full
    $seat_query = "INSERT INTO seats(flightnumber, seatnumber, customerid) VALUES (200000, $i, 1)";
    mysql_query($seat_query);
}
mysql_close();
?>


