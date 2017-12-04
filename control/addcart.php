<?php
    session_start();
    if($_SESSION['id'] != NULL) {
        if($_SERVER["REQUEST_METHOD"] == "POST") {
            echo "Input is in POST<br>";
            foreach ($_POST as $key => $value) {
                echo '<p>Key in POST: '.$key.'</p>';
                foreach($value as $k => $v)
                {
                echo '<p>Key: '.$k.'</p>';
                echo '<p>Value: '.$v.'</p>';
                echo '<hr />';
                }
              
              }
              
            include('connect.php');
            if(isset($_POST['addcart-flight'])) {
                switch($_POST['select-flight']) {
                    case 'flight-1':
                        $flightnum = 100000;
                        break;
                    case 'flight-2':
                        $flightnum = 200000;
                        break;
                    case 'flight-3':
                        $flightnum = 300000;
                        break;
                }
                echo "Input from Flight<br>";
                echo "Selected flight number is: "+$flightnumber;
            } else if (isset($_POST['addcart-rental'])) {
                echo "Input from Rentals<br>";
            } else if (isset($_POST['addcart-parking'])) {
                echo "Input from Parking<br>";
                switch($_POST['select-parking']) {
                    case 'daily':
                        $parking = 1;
                        break;
                    case 'economy':
                        $parking = 2;
                        break;
                    case 'park-ride':
                        $parking = 3;
                        break;
                }
                echo $_POST['days-parked'];
            } else {
                echo "Cannot read input<br>";
            }
            if (isset($_POST['test'])) {
                echo "Test<br>";
            }
        }
    } else {
        header("location: ../login.html");
    }
    
?>