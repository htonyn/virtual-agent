<?php
    session_start();
    if($_SERVER["REQUEST_METHOD"] == "POST") {
        $inputemail = $_POST['email'];
        $inputpassword = $_POST['password'];
        $hash = hash('sha512', $inputpassword);
        $login_query = "SELECT * FROM users WHERE login='$inputemail' AND password='$hash'";
        include('connect.php');
        $result = mysql_query($login_query);
        if($result) {
            $count = mysql_num_rows($result);
            if ($count == 1) {
                $return = mysql_fetch_assoc($result);
                $_SESSION['id'] = $return['userid'];
                mysql_close();
                if($return['admin_flag'] == 1) {
                    header("location: ../admin.php");
                } else {
                    echo $_SESSION['id'];
                    echo "Not an admin";
                    header("location: ../home.html");
                }
            } else {
                echo 'Wrong login';
                header('location: ../login.html');
            }
        } else {
            echo "Could not execute query.<br>";
            trigger_error(mysql_error(), E_USER_ERROR);
        }
    }
?>