<?php

    $con = mysqli_connect('localhost','root','8994','contractManager');

    if (mysqli_connect_errno($con))
    {
       echo "Failed to connect to MySQL: " . mysqli_connect_error();
    }
 
    $username = $_GET['username'];

    $result = mysqli_query($con, "SELECT * FROM accounts WHERE username = '$username'");
 
    $no_of_rows = mysql_num_rows($result);

    $account = array();
    $account["exists"] = true;

    if ($no_of_rows > 0) {
        // user existed 
        echo json_encode($account);
    } else {
        // user not existed
        $account["exists"] = false;
        echo json_encode($account);
    }
    mysqli_close($con);
?>