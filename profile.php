<?php 
session_start();
if($_SESSION['id'] != NULL) {
    $userid = $_SESSION['id'];
    include('control/connect.php');
    // Retrieves the customer ID for use using the userid stored in sessions
    $customer_query = "SELECT customerid from users where userid='$userid'";
    $return = mysql_query($customer_query);
    while ($result = mysql_fetch_assoc($return)) {
        $customerid = $result['customerid'];
    }
    // Get all order ids
    $order_output = "";
    $orders_query = "SELECT orderid, date_purchased FROM orders WHERE customerid='$customerid'";
    $orders_result = mysql_query($orders_query);
    while ($orders_return = mysql_fetch_assoc($orders_result)) {
        // Get the orderid
        $orderid = $orders_return['orderid'];
        $order_output .= "<div class=\"order-container\" onclick=\"toggleSection('order-$orderid')\">";
        $order_output .= "<h2>Order: #$orderid</h2><div class=\"order-content\" id=\"order-$orderid\" style=\"display: none\">";
        // Using the orderid, get all order_items with this orderid
        $order_item_query = "SELECT * FROM order_items where orderid='$orderid'";
        $order_result = mysql_query($order_item_query);
        $order_output .= "<table class=\"output-table\">";
        $order_output .= "<tr><th>Product</th><th>Quantity</th><th>Price</th></tr>";
        $total = 0;
        while ($order_return = mysql_fetch_assoc($order_result)) {
            $productid = $order_return['productid'];
            $quantity = $order_return['product_quantity'];
            $price = $order_return['product_price'];
            $type = $order_return['typeName'];
            $typeValue = $order_return['typeValue'];
            $thisPrice = $quantity * $price;
            $total += $thisPrice;
            switch($type) {
                case "Flight":
                    $flight_query = "SELECT * FROM flights WHERE flightnumber='$typeValue'";
                    $flight_result = mysql_query($flight_query);
                    while ($flight_return = mysql_fetch_assoc($flight_result)) {
                        $product = "Flight #".$typeValue." FROM: ".$flight_return['depart']." TO: ".$flight_return['arrive'];
                    }
                    break;
                case "Rental":
                    $rental_query = "SELECT * FROM rental WHERE rentalid='$typeValue'";
                    $rental_result = mysql_query($rental_query);
                    while ($rental_return = mysql_fetch_assoc($rental_result)) {
                        $product = "Rental: ".$rental_return['rentalName'];
                    }
                    break;
                case "Parking":
                    $parking_query = "SELECT * FROM parkingLot WHERE lotid='$typeValue'";
                    $parking_result = mysql_query($parking_query);
                    while ($parking_return = mysql_fetch_assoc($parking_result)) {
                        $product = "Parking: ".$parking_return['lotname'];
                    }
                    break;
            }
            $order_output .= "<tr><td>$product</td><td>$quantity</td><td>$$thisPrice</td></tr>";
        }
        $datep = $orders_return['date_purchased'];
        $order_output .= "</table><br><br><h2>Total Price: $$total</h2><h3>Date: $datep</h3>";
        $order_output .= "</div></div><hr>";
    }
    
} else {
    header("location: login.html");
}
?>

<!DOCTYPE HTML>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Ponkberry Travel Agency - Home Portal</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">   
    <link rel="stylesheet" href="styles/styles.css">
    <link rel="stylesheet" href="styles/home.css">
    <link rel="stylesheet" href="styles/cart.css">
    <link rel="stylesheet" href="styles/profile.css">
</head>
<body>
    <div class="header-container">
        <div id="header">
            <ul class="nav-list">
                <li><a href="home.html">Home</a></li>
                <li style="float: right"><a href="control/logout.php">Sign Out</a></li>
                <li style="float: right"><a href="profile.php">Profile</a></li>
                <li style="float: right"><a href="viewcart.php">Cart</a></li>
            </ul>
        </div>
    </div>
    <div id="content">
        <div id="content-container">
            <h1>Orders</h1><hr>
            <?php echo $order_output; ?>
        </div>                    
    </div>
</body>
<script>
    function submitForm() {
        document.forms['addcart.php'].submit();
    }

    function toggleSection(sectionName) {
        $('#'+sectionName).toggle('fast');
    }
</script>
<script src="scripts/scripts.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</html>