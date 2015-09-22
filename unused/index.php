<?php
     
    /*
     * File to handle all API requests
     * Accepts GET and POST
     * 
     * Each request will be identified by TAG
     * Response will be JSON data
     * check for POST request 
     */
    if (isset($_POST['tag']) && $_POST['tag'] != '') {
        // get tag
        $tag = $_POST['tag'];
     
        // include db handler
        require_once 'include/DB_Functions.php';
        $db = new DB_Functions();
     
        // response Array
        $response = array("tag" => $tag, "error" => FALSE);
     
        // check for tag type
        if ($tag == 'login') {
            // Request type is check Login
            $username = $_POST['username'];
            $password = $_POST['password'];
     
            // check for user
            $user = $db->getAccountByUsernameAndPassword($username, $password);
            if ($user != false) {
                // user found
                $response["error"] = FALSE;
                $response["user"]["account_id"] = $user["account_id"];
                $response["user"]["name"] = $user["name"];
                $response["user"]["nric"] = $user["nric"];
                $response["user"]["phone"] = $user["phone"];
                $response["user"]["email"] = $user["email"];
                $response["user"]["profpic"] = $user["profpic"];
                echo json_encode($response);
            } else {
                // user not found
                // echo json with error = 1
                $response["error"] = TRUE;
                $response["error_msg"] = "Incorrect username or password!";
                echo json_encode($response);
            }
        } else if ($tag == 'register') {
            // Request type is Register new user
            $name = $_POST['name'];
            $nric = $_POST['nric'];
            $phone = $_POST['phone'];
            $email = $_POST['name'];
            $username = $_POST['username'];
            $password = $_POST['password'];
     
            // check if user is already existed
            if ($db->isUserExisted($username)) {
                // user is already existed - error response
                $response["error"] = TRUE;
                $response["error_msg"] = "User already existed";
                echo json_encode($response);
            } else {
                // store user
                $user = $db->storeUser($name, $nric, $phone, $email, $username, $password);
                if ($user) {
                    // user stored successfully
                    $response["error"] = FALSE;
                    $response["user"]["account_id"] = $user["account_id"];
                    $response["user"]["name"] = $user["name"];
                    $response["user"]["nric"] = $user["nric"];
                    $response["user"]["phone"] = $user["phone"];
                    $response["user"]["email"] = $user["email"];
                    $response["user"]["profpic"] = $user["profpic"];
                    echo json_encode($response);
                } else {
                    // user failed to store
                    $response["error"] = TRUE;
                    $response["error_msg"] = "Error occured in Registration";
                    echo json_encode($response);
                }
            }
        } else {
            // user failed to store
            $response["error"] = TRUE;
            $response["error_msg"] = "Unknown 'tag' value. It should be either 'login' or 'register'";
            echo json_encode($response);
        }
    } else {
        $response["error"] = TRUE;
        $response["error_msg"] = "Required parameter 'tag' is missing!";
        echo json_encode($response);
    }
?>