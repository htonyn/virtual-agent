<?php
include('connect.php');

$query = "CREATE TABLE IF NOT EXISTS customers(customerid int(6) AUTO_INCREMENT, firstname varchar(255), lastname varchar(255), address varchar(255), PRIMARY KEY (customerid))";
mysql_query($query);
$query = "CREATE TABLE IF NOT EXISTS inventory(productid int(12), name varchar(255), price int, PRIMARY KEY (productid))";
mysql_query($query);
$query = "CREATE TABLE IF NOT EXISTS orders(orderid int(12), status varchar(255), date_purchased DATE, PRIMARY KEY (orderid))";
mysql_query($query);
$query = "CREATE TABLE IF NOT EXISTS users(userid int(6) AUTO_INCREMENT, login varchar(255), password varchar(255), customerid int(6), admin_flag TINYINT(1), PRIMARY KEY (userid))";
mysql_query($query);
$query = "CREATE TABLE IF NOT EXISTS shoppingcart(cartid int(6), customerid int(6), date_initialized DATE, PRIMARY KEY (cartid))";
mysql_query($query);
$query = "CREATE TABLE IF NOT EXISTS shoppingcart_items(cartid int(6), productid int(12), product_quantity int(2), product_price int)";
mysql_query($query);
$pw = hash('sha512', 'pw2');
$dupe_query = "SELECT * from users where login='hnguyen124'";
$result = mysql_query($dupe_query);
if (mysql_num_rows($result) < 1) {
    $admin_query = "INSERT INTO users(login, password, customerid, admin_flag) VALUES ('hnguyen124@student.gsu.edu', '$pw', 1, 1)";
    mysql_query($admin_query);
    $customer_query = "INSERT INTO customers(firstname, lastname, address) VALUES ('Tony', 'Nguyen', '123 Potato Lane')";
    mysql_query($customer_query);
}
?>