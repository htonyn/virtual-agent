<?php
    session_start();
    if($_SESSION['id'] != NULL) {
        if($_SERVER["REQUEST_METHOD"] == "POST") {
            include('connect.php');
            $userid = $_SESSION['id'];
            // Retrieves the customer ID for use using the userid stored in sessions
            $customer_query = "SELECT customerid from users where userid='$userid'";
            $return = mysql_query($customer_query);
            while ($result = mysql_fetch_assoc($return)) {
                $customerid = $result['customerid'];
            }
            // With the customerID, we can now pull the cartid that links all the items from the customer.
            $cart_query = "SELECT * from shoppingcart where customerid='$customerid'";
            $return = mysql_query($cart_query);
            $count = mysql_num_rows($return);
            if ($count == 0) {
                // If there's no cartid with that customerid, it means the user doesn't have a cart, so we create one.
                // By default, registration will take care of creating a cart for the user
                $cart_query = "INSERT INTO shoppingcart(customerid) VALUES ('$customerid')";
                $add_cart = mysql_query($cart_query);
                // After we create the new cart, we do need to get that cart id now
                if (!$add_cart) {
                    // Error
                } else {
                    $cart_query = "SELECT * from shoppingcart where customerid='$customerid'";
                    $return = mysql_query($cart_query);
                }         
            }
            while($result = mysql_fetch_assoc($return)) {
                $cartid = $result['cartid'];
            }
            
            // echo "Input is in POST<br>";
            // print_r($_POST);
            // foreach ($_POST as $key => $value) {
            //     echo '<p>Key in POST: '.$key.'</p>';
            //     foreach($value as $k => $v) {
            //         echo '<p>Key: '.$k.'</p>';
            //         echo '<p>Value: '.$v.'</p>';
            //         echo '<hr />';
            //     }
            // }

            // Now we add stuff into the cart            
            if(isset($_POST['addcart-flight'])) {
                // echo "Input from Flight<br>";
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
                switch($_POST['flight-class']) {
                    case 'first-class':
                        $productid = 5;
                        break;
                    case 'business-class':
                        $productid = 6;
                        break;
                    case 'economy-class':
                        $productid = 7;
                        break;
                    case 'cargo':
                        $productid = 8;
                        break;
                }
                $get_price = "SELECT price from inventory where productid='$productid'";
                $result = mysql_query($get_price);
                while ($return = mysql_fetch_assoc($result)) {
                    $price = $return['price'];
                }
                $type = "Flight";
                $typeValue = $flightnum;
                echo "Purchase Type: ".$type."<br>";
                echo "Flight Num: ".$flightnum."<br>";
                echo "Flight Class: ".$productid."<br>";
                echo "Price: ".$price."<br>";

                echo "CustomerID: ".$customerid."<br>";
                echo "CartID: ".$cartid."<br>";

                $cart_add = "INSERT INTO shoppingcart_items(cartid, productid, product_quantity, product_price, typeName, typeValue) VALUES ('$cartid', '$productid', 1, '$price', '$type', '$typeValue')";
                mysql_query($cart_add);
            } else if (isset($_POST['addcart-rental'])) {
                // echo "Input from Rentals<br>";
                $type = "Rental";
                switch($_POST['rental-dropdown']) {
                    case 'suv':
                        $productid = 1;
                        $price = 50;
                        $typeValue = 1;
                        break;
                    case 'compact':
                        $productid = 2;
                        $price = 30;
                        $typeValue = 2;
                        break;
                    case 'midsize':
                        $productid = 3;
                        $price = 40;
                        $typeValue = 3;
                        break;
                    case 'luxury':
                        $productid = 4;
                        $price = 100;
                        $typeValue = 4;
                        break;
                }
                $cart_add = "INSERT INTO shoppingcart_items(cartid, productid, product_quantity, product_price, typeName, typeValue) VALUES ('$cartid', '$productid', 1, '$price', '$type', '$typeValue')";
                mysql_query($cart_add);
            } else if (isset($_POST['addcart-parking'])) {
                // echo "Input from Parking<br>";
                $type = "Parking";
                switch($_POST['select-parking']) {
                    case 'daily':
                        $productid = 10;
                        $typeValue = 1;
                        $price = 16;
                        break;
                    case 'economy':
                        $productid = 11;
                        $typeValue = 2;
                        $price = 12;
                        break;
                    case 'park-ride':
                        $productid = 9;
                        $typeValue = 3;
                        $price = 9;
                        break;
                }
                $quantity = $_POST['days-parked'];
                $cart_add = "INSERT INTO shoppingcart_items(cartid, productid, product_quantity, product_price, typeName, typeValue) VALUES ('$cartid', '$productid', '$quantity', '$price', '$type', '$typeValue')";
                mysql_query($cart_add);
            } else {
                echo "Cannot read input<br>";
            }
            header("location: ../viewcart.php");
        }
    } else {
        header("location: ../login.html");
    }
    
?>