<?php
 
    /*
     * Following code will create a new product row
     * All product details are read from HTTP Post Request
     */
     
    // array for JSON response
    $response = array();
     
    // check for required fields
    if (isset($_POST['name']) && isset($_POST['nric']) && isset($_POST['phone']) && isset($_POST['username']) && isset($_POST['password'])) {
     
        $name = $_POST['name'];
        $nric = $_POST['nric'];
        $phone = $_POST['phone'];
        $username = $_POST['username'];
        $password = $_POST['password'];
     
        // include db connect class
        require_once __DIR__ . '/db_connect.php';
     
        // connecting to db
        $db = new DB_CONNECT();
     
        // mysql inserting a new row
        $result = mysql_query("INSERT INTO accounts(name, nric, phone, username, password) VALUES('$name', '$nric', '$phone', 'username', 'password')");
     
        // check if row inserted or not
        if ($result) {
            // successfully inserted into database
            $response["success"] = 1;
            $response["message"] = "Account successfully created.";
     
            // echoing JSON response
            echo json_encode($response);
        } else {
            // failed to insert row
            $response["success"] = 0;
            $response["message"] = "Oops! An error occurred.";
     
            // echoing JSON response
            echo json_encode($response);
        }
    } else {
        // required field is missing
        $response["success"] = 0;
        $response["message"] = "Required field(s) is missing";
     
        // echoing JSON response
        echo json_encode($response);
    }
?>