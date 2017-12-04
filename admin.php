<?php
    session_start();
    if($_SESSION['id'] != NULL) {
        $userid = $_SESSION['id'];
        include('control/connect.php');
        $admin_query = "SELECT * FROM users WHERE userid='$userid'";        
        $result = mysql_query($admin_query);
        $count = mysql_num_rows($result);
        while($return = mysql_fetch_assoc($result)) {
            if ($return['admin_flag'] == 0) {
                header("location: home.php");
            } else {
                $login = $return['login'];
                $user_query = "SELECT * from users";
                $user_result = mysql_query($user_query);
                $user_output = "<table class=\"output-table\">";
                $user_output .= "<tr><td>UserID</td><td>Login</td><td>CustomerID</td><td>Admin Flag</td></tr>";
                while($user_return = mysql_fetch_assoc($user_result)) {
                    $id_user = $user_return['userid'];
                    $login_user = $user_return['login'];
                    $cust_user = $user_return['customerid'];
                    $admin_user = $user_return['admin_flag'];
                    
                    $user_output .= "<tr><td>$id_user</td><td>$login_user</td><td>$cust_user</td><td>$admin_user</td></tr>";
                }
                $user_output .= "</table>";

                $output = "";
                $query = "SELECT * from customers";
                $result = mysql_query($query);
                $output .= "<table class=\"output-table\">";
                $output .= "<tr><td>CustomerID</td><td>First Name</td><td>Last Name</td><td>Address</td></tr>";
                while($return = mysql_fetch_assoc($result)) {
                    $var1 = $return['customerid'];
                    $var2 = $return['firstname'];
                    $var3 = $return['lastname'];
                    $var4 = $return['address'];
                    
                    $output .= "<tr><td>$var1</td><td>$var2</td><td>$var3</td><td>$var4</td></tr>";
                }
                $output .= "</table>";
                $cust_output = $output;

                $output = "";
                $query = "SELECT * from orders";
                $result = mysql_query($query);
                $output .= "<table class=\"output-table\">";
                $output .= "<tr><td>OrderID</td><td>CustomerID</td><td>Date</td></tr>";
                while($return = mysql_fetch_assoc($result)) {
                    $var1 = $return['orderid'];
                    $var2 = $return['customerid'];
                    $var3 = $return['date_purchased'];
                    
                    $output .= "<tr><td>$var1</td><td>$var2</td><td>$var3</td></tr>";
                }
                $output .= "</table>";
                $order_output = $output;
                
                $output = "";
                $query = "SELECT * from flights";
                $result = mysql_query($query);
                $output .= "<table class=\"output-table\">";
                $output .= "<tr><td>Flight Number</td><td>FROM</td><td>TO</td></tr>";
                while($return = mysql_fetch_assoc($result)) {
                    $var1 = $return['flightnumber'];
                    $var2 = $return['depart'];
                    $var3 = $return['arrive'];
                    
                    $output .= "<tr><td>$var1</td><td>$var2</td><td>$var3</td></tr>";
                }
                $output .= "</table>";
                $flight_output = $output;

                $output = "";
                $query = "SELECT * from seats";
                $result = mysql_query($query);
                $output .= "<table class=\"output-table\">";
                $output .= "<tr><td>Flight Number</td><td>FROM</td><td>TO</td></tr>";
                while($return = mysql_fetch_assoc($result)) {
                    $var1 = $return['flightnumber'];
                    $var2 = $return['seatnumber'];
                    $var3 = $return['customerid'];
                    
                    $output .= "<tr><td>$var1</td><td>$var2</td><td>$var3</td></tr>";
                }
                $output .= "</table>";
                $seat_output = $output;

                $output = "";
                $query = "SELECT * from inventory";
                $result = mysql_query($query);
                $output .= "<table class=\"output-table\">";
                $output .= "<tr><td>ProductID</td><td>Product Name</td><td>Price</td><td>Type</td><td>Type Value</td></tr>";
                while($return = mysql_fetch_assoc($result)) {
                    $var1 = $return['productid'];
                    $var2 = $return['name'];
                    $var3 = $return['price'];
                    $var4 = $return['typeName'];
                    $var5 = $return['typeValue'];
                    
                    $output .= "<tr><td>$var1</td><td>$var2</td><td>$var3</td><td>$var4</td><td>$var5</td></tr>";
                }
                $output .= "</table>";
                $inv_output = $output;

                $output = "";
                $query = "SELECT p.lotid, p.lotname, j.counter from parkingLot p JOIN (SELECT lotid, COUNT(*) as counter from parkingReservation GROUP BY lotid) j where p.lotid=j.lotid";
                $result = mysql_query($query);
                $output .= "<table class=\"output-table\">";
                $output .= "<tr><td>Lot ID</td><td>Lot Name</td><td>Number of Reservations</td></tr>";
                while($return = mysql_fetch_assoc($result)) {
                    $var1 = $return['lotid'];
                    $var2 = $return['lotname'];
                    $var3 = $return['counter'];
                    
                    $output .= "<tr><td>$var1</td><td>$var2</td><td>$var3</td></tr>";
                }
                $output .= "</table>";
                $lot_output = $output;

                $output = "";
                $query = "SELECT * from parkingReservation";
                $result = mysql_query($query);
                $output .= "<table class=\"output-table\">";
                $output .= "<tr><td>Lot ID</td><td>Days</td><td>OrderID</td></tr>";
                while($return = mysql_fetch_assoc($result)) {
                    $var1 = $return['lotid'];
                    $var2 = $return['days'];
                    $var3 = $return['orderid'];
                    
                    $output .= "<tr><td>$var1</td><td>$var2</td><td>$var3</td></tr>";
                }
                $output .= "</table>";
                $park_output = $output;
                
            }
        }
    } else {
        header("location: login.html");
    }

?>

<!DOCTYPE HTML>
<html>
<head>
    <meta charset="UTF-8">
    <title>Ponkberry Travel Agency</title>
    <link rel="stylesheet" href="styles/styles.css">
    <link rel="stylesheet" href="styles/admin.css">
</head>
<body>
    <div class="header-container">
        <div id="header">
            <ul class="nav-list">
                <li><a href="home.html">Home</a></li>
                <li style="float: right"><a href="control/logout.php">Log Out</a></li>
            </ul>
        </div>
    </div>
    <div id="admin-menu">
        <ul class="nav-list">
            <li><a href="profile.php">User: <?php echo $login; ?></a></li>
            <li><a href="#" onclick="tabSwap('admin', 'output')">Admin</a></li>
            <li><a href="#" onclick="tabSwap('users', 'output')">Users</a></li>
            <li><a href="#" onclick="tabSwap('customers', 'output')">Customers</a></li>
            <li><a href="#" onclick="tabSwap('orders', 'output')">Orders</a></li>
            <li><a href="#" onclick="tabSwap('inventory', 'output')">Inventory</a></li>
            <li><a href="#" onclick="tabSwap('flight', 'output')">Flight</a></li>
            <li><a href="#" onclick="tabSwap('seats', 'output')">Seats</a></li>
            <li><a href="#" onclick="tabSwap('parking', 'output')">Parking</a></li>
            <li><a href="#" onclick="tabSwap('parking-reservation', 'output')">Parking Reservations</a></li>
            <!-- <li><a href="#" onclick="tabSwap('rental', 'output')">Rental</a></li> -->
        </ul>
    </div>
    <div id="content">
        <div id="output">
            <div id="admin" class="content-container">
                <table class="output-table">
                    <tr><th>Test 1</th><th>Test 1</th><th>Test 1</th><th>Test 1</th></tr>
                    <tr><td>Content</td><td>Content</td><td>Content</td><td>Content</td></tr>
                    <tr><td>Content</td><td>Content</td><td>Content</td><td>Content</td></tr>
                    <tr><td>Content</td><td>Content</td><td>Content</td><td>Content</td></tr>
                    <tr><td>Content</td><td>Content</td><td>Content</td><td>Content</td></tr>
                    <tr><td>Content</td><td>Content</td><td>Content</td><td>Content</td></tr>
                </table>
            </div>
            <div id="users" class="content-container" style="display: none">
                <?php echo $user_output; ?>
            </div>
            <div id="customers" class="content-container" style="display: none">
                <?php echo $cust_output; ?>
            </div>
            <div id="orders" class="content-container" style="display: none">
                <?php echo $order_output; ?>
            </div>            
            <div id="inventory" class="content-container" style="display: none">
                <?php echo $inv_output; ?>
            </div>
            <div id="flight" class="content-container" style="display: none">
                <?php echo $flight_output; ?>
            </div>
            <div id="seats" class="content-container" style="display: none">
                <?php echo $seat_output; ?>
            </div>            
            <div id="parking" class="content-container" style="display: none">
            <?php echo $lot_output; ?>
            </div>
            <div id="parking-reservation" class="content-container" style="display: none">
                <?php echo $park_output; ?>
            </div>
            <div id="rental" class="content-container" style="display: none">
                    
            </div>
        </div>
    </div>
</body>
<script src="scripts/scripts.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</html>