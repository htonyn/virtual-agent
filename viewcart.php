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

        $cart_and_items = "SELECT * FROM shoppingcart s1 JOIN shoppingcart_items s2 on s1.cartid=s2.cartid WHERE customerid='$customerid'";
        $result = mysql_query($cart_and_items);
        $cart_output = "<table class=\"output-table\">";
        $cart_output .= "<tr><th>Product</th><th>Quantity</th><th>Price</th></tr>";
        $total = 0;
        while ($return = mysql_fetch_assoc($result)) {
            $productid = $return['productid'];
            $quantity = $return['product_quantity'];
            $price = $return['product_price'];
            $type = $return['typeName'];
            $typeValue = $return['typeValue'];
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
            $cart_output .= "<tr><td>$product</td><td>$quantity</td><td>$$thisPrice</td></tr>";
        }
        $cart_output .= "</table><br><br><h2>Total Price: $$total</h2>";
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
    <link rel="stylesheet" href="styles/profile.css">
    <link rel="stylesheet" href="styles/cart.css">
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
            <h2>Cart</h2><hr>
            <div id="cart-items">
                <?php echo $cart_output; ?>
            </div>
            <div class="container">
                <form action="control/order.php" class="form-horizontal" role="form" method="POST">
                    <fieldset>
                    <legend>Payment</legend>
                    <div class="form-group">
                        <label class="col-sm-3 control-label" for="card-holder-name">Name on Card</label>
                        <div class="col-sm-9">
                        <input type="text" class="form-control" name="card-holder-name" id="card-holder-name" placeholder="Card Holder's Name">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label" for="card-number">Card Number</label>
                        <div class="col-sm-9">
                        <input type="text" class="form-control" name="card-number" id="card-number" placeholder="Debit/Credit Card Number" onchange="displayType()">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-8 cc-container">
                            <img class="credit-card-img" id="cc-a" src="src/american.png"/>
                            <img class="credit-card-img" id="cc-d" src="src/discover.png"/>
                            <img class="credit-card-img" id="cc-m" src="src/master.png"/>
                            <img class="credit-card-img" id="cc-v" src="src/visa.png"/>                            
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label" for="expiry-month">Expiration Date</label>
                        <div class="col-sm-9">
                        <div class="row">
                            <div class="col-xs-3">
                            <select class="form-control col-sm-2" name="expiry-month" id="expiry-month">
                                <option>Month</option>
                                <option value="01">Jan (01)</option>
                                <option value="02">Feb (02)</option>
                                <option value="03">Mar (03)</option>
                                <option value="04">Apr (04)</option>
                                <option value="05">May (05)</option>
                                <option value="06">June (06)</option>
                                <option value="07">July (07)</option>
                                <option value="08">Aug (08)</option>
                                <option value="09">Sep (09)</option>
                                <option value="10">Oct (10)</option>
                                <option value="11">Nov (11)</option>
                                <option value="12">Dec (12)</option>
                            </select>
                            </div>
                            <div class="col-xs-3">
                            <select class="form-control" name="expiry-year">
                                <option value="13">2013</option>
                                <option value="14">2014</option>
                                <option value="15">2015</option>
                                <option value="16">2016</option>
                                <option value="17">2017</option>
                                <option value="18">2018</option>
                                <option value="19">2019</option>
                                <option value="20">2020</option>
                                <option value="21">2021</option>
                                <option value="22">2022</option>
                                <option value="23">2023</option>
                            </select>
                            </div>
                        </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label" for="cvv">Card CVV</label>
                        <div class="col-sm-3">
                        <input type="text" class="form-control" name="cvv" id="cvv" placeholder="Security Code">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label" for="sa">Shipping Address</label>
                        <div class="col-sm-9">
                        <input type="text" class="form-control" name="sa" id="sa" placeholder="Shipping Address">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label" for="ba">Billing Address</label>
                        <div class="col-sm-9">
                        <input type="text" class="form-control" name="ba" id="ba" placeholder="Billing Address">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label" for="pn">Phone Number</label>
                        <div class="col-sm-3">
                        <input type="text" class="form-control" name="pn" id="pn" placeholder="Phone Number">
                        </div>
                    </div>                                        
                    <div class="form-group">
                        <div class="col-sm-offset-3 col-sm-9">
                        <input type="hidden" name="order" value="Order"/>
                        <button type="submit" class="btn btn-success" formmethod="post" onclick="submitForm()">Pay Now</button>
                        </div>
                    </div>
                    </fieldset>
                </form>
                </div>
        </div>       
    </div>
</body>
<script>
    function displayType() {
        // Visa starts with 4
        // Discover starts with 6011, 64, 65
        // Master starts with 2221-2720 or 51-55
        // American starts with 34 or 37
        master = /^5[1-5]\d{14}/g;
        visa = /^4\d{12}(\d{3})?/g;
        discover = /^6011\d{14}/g;
        american = /^3[47]\d{13}/g;
        if(visa.test($('#card-number').val())) {
            console.log("It's visa");
            $('.cc-container img').css('opacity', 0.3);
            $('#cc-v').css('opacity', 1.0);
        } else if (master.test($('#card-number').val())) {
            console.log("It's master");
            $('.cc-container img').css('opacity', 0.3);
            $('#cc-m').css('opacity', 1.0);
        } else if (american.test($('#card-number').val())) {
            console.log("It's american");
            $('.cc-container img').css('opacity', 0.3);
            $('#cc-a').css('opacity', 1.0);
        } else if (discover.test($('#card-number').val())) {
            console.log("It's discover");
            $('.cc-container img').css('opacity', 0.3);
            $('#cc-d').css('opacity', 1.0);
        } else {
            console.log("It's something");
            $('.cc-container img').css('opacity', 0.3);
        }
        
    }

    function submitForm() {
        document.forms['order.php'].submit();
    }

    function toggleSection(sectionName) {
        $('#'+sectionName).toggle('fast');
    }
</script>
<script src="scripts/scripts.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</html>