<?php
    session_start();
    if($_SESSION['id'] != NULL) {
        $userid = $_SESSION['id'];
        echo "UserID: $userid<br>";
        include('connect.php');
        
        

        // Retrieves the customer ID for use using the userid stored in sessions
        $customer_query = "SELECT * from users WHERE userid='$userid'";
        $return = mysql_query($customer_query);
        echo "Rows: ".mysql_num_rows($return)."<br>";
        while ($result = mysql_fetch_assoc($return)) {
            $customerid = $result['customerid'];
        }
        $getcartid = "SELECT * FROM shoppingcart WHERE customerid='$customerid'";
        $cartid_return = mysql_query($getcartid);
        while ($cartid_result = mysql_fetch_assoc($cartid_return)) {
            $cartid = $cartid_result['cartid'];
            echo "CartID: $cartid<br>";
        }
        echo "CustomerID: $customerid<br>";
        $cart_and_items = "SELECT * FROM shoppingcart s1 JOIN shoppingcart_items s2 on s1.cartid=s2.cartid WHERE customerid='$customerid'";
        $get_cart_result = mysql_query($cart_and_items);
        $count = mysql_fetch_row($get_cart_result);
        if ($count > 0) {
            $new_order_query = "INSERT INTO orders(customerid, date_purchased) VALUES ($customerid, now())";
            $test_new_order = mysql_query($new_order_query);
            if ($test_new_order) {
                $orderid = mysql_insert_id();
                echo "OrderID: $orderid<br>";
            }
            $cart_and_items = "SELECT * FROM shoppingcart s1 JOIN shoppingcart_items s2 on s1.cartid=s2.cartid WHERE customerid='$customerid'";
            $get_cart_result = mysql_query($cart_and_items);
            while ($return = mysql_fetch_assoc($get_cart_result)) {
                echo "Entered query loop for each shopping cart";
                $productid = $return['productid'];
                $quantity = $return['product_quantity'];
                $price = $return['product_price'];
                $type = $return['typeName'];
                $typeValue = $return['typeValue'];
                
                $transfer_cart_to_order = "INSERT INTO order_items(orderid, productid, product_quantity, product_price, typeName, typeValue) VALUES ('$orderid', '$productid', $quantity, '$price', '$type', '$typeValue')";
                mysql_query($transfer_cart_to_order);
                echo "CartID: $cartid<br>";
                echo "ProductID: $productid<br>";
                $delete_from_cart = "DELETE FROM shoppingcart_items WHERE cartid='$cartid' AND productid='$productid'";
                mysql_query($delete_from_cart);
            }
        }
        // $testMessage = "Order Confirmation: Order # $orderid";
        // $to      = 'factummeretricis@gmail.com';
        // $subject = 'the subject';
        // $headers = 'From: htonynguyen@gmail.com' . "\r\n" .
        //     'Reply-To: htonynguyen@gmail.com' . "\r\n" .
        //     'X-Mailer: PHP/' . phpversion();
        // $from = "htonynguyen@gmail.com";
        // ini_set('sendmail_from', $from);
        // $sentMail = mail($to, $subject, $testMessage, $headers);
        // mail('factummeretricis@gmail.com', 'Subject', 'Body');
        // if ($sentMail) {
        //     echo "Mail was accepted.<br>";
        // } else {
        //     echo "Mail was denied.<br>";
        // }
        header("location: ../profile.php");
    } else {
        header("location: ../login.html");
    }
?>