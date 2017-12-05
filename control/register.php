<?php
    session_start();
    if($_SERVER["REQUEST_METHOD"] == "POST") {
        $reg_email = $_POST['email'];
        $reg_pass = $_POST['password'];
        $reg_fn = $_POST['firstname'];
        $reg_ln = $_POST['lastname'];
        $reg_address = $_POST['address'];
        $hash = hash('sha512', $reg_pass);
        // Check for duplicate login
        $dupe_query = "SELECT * FROM users where login='$reg_email'";
        include('connect.php');
        $dupe = mysql_query($dupe_query);
        if(!$dupe) {
            echo "Could not execute query.<br>";
            trigger_error(mysql_error(), E_USER_ERROR);
        } else {
            $count = mysql_num_rows($dupe);
            if ($count == 0) {
                $add_customer = "INSERT INTO customers(firstname, lastname, address) VALUES ('$reg_fn', '$reg_ln', '$reg_address')";
                mysql_query($add_customer);
                $getCustomerID = "SELECT customerid FROM customers WHERE firstname='$reg_fn' AND lastname='$reg_ln' AND address='$reg_address'";
                $result = mysql_query($getCustomerID);
                $return = mysql_fetch_assoc($result);
                $custID = $return['customerid'];
                // Customer Query
                $add_user = "INSERT INTO users(login, password, customerid) VALUES('$reg_email', '$hash', '$custID')";
                mysql_query($add_user);
                $cart_query = "INSERT INTO shoppingcart(customerid) VALUES ('$custID')";
                mysql_query($cart_query);
                mysql_close();
                // echo "Email: ".$reg_email."<br>";
                // echo "Pass: ".$reg_pass."<br>";
                // echo "FN: ".$reg_fn."<br>";
                // echo "LN: ".$reg_ln."<br>";
                // echo "Add: ".$reg_address."<br>";
                header('location: ../login.html');
            }
        }
    }
?>