<?php
    include('connect.php');
    function getAvailableSeats($flightnum) {
        $query = "SELECT f.flightnumber, s.seatnumber from flights f JOIN seats s on f.flightnumber=s.flightnumber where f.flightnumber='$flightnum'";
        $query2 = "SELECT a.number from seatChart a LEFT JOIN ($query) as p ON a.number=p.seatnumber WHERE p.flightnumber IS NULL";
        $result = mysql_query($query);
        $result2 = mysql_query($query2);
        $firstclass = 0;
        $business = 0;
        $economy = 0;
        $output = "<table>";
        while($return = mysql_fetch_assoc($result2)) {
            $seatnum = $return['number'];
            if ($seatnum <= 10) {
                $firstclass++;
            } else if ($seatnum <= 30) {
                $business++;
            } else {
                $economy++;
            }
            $output = $output + "<tr><td>$seatnum</td></tr>";
        }
        $output = $output + "</table>";
        // echo $output;
        // echo "First Class: $firstclass<br>";
        // echo "Business Class: $business<br>";
        // echo "Economy Class: $economy<br>";        
    }
?>