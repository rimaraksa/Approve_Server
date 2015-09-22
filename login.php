<?php
    try {
        $con = mysqli_connect('localhost','root','8994','contractManager');
        $myDir = '/Users/rimaraksa/Sites/android_connect/';

        if (mysqli_connect_errno($con))
        {
           echo "Failed to connect to MySQL: " . mysqli_connect_error();
        }
        else{
            $username = $_POST['username'];
            $password = $_POST['password'];

            $result = mysqli_query($con, "SELECT * FROM accounts WHERE username = '$username' AND password = '$password'");
         
            $data = mysqli_fetch_array($result);

            $account = array();
            $account["account_id"] = $data["account_id"];
            $account["name"] = $data["name"];
            $account["nric"] = $data["nric"];
            $account["phone"] = $data["phone"];
            $account["profpic"] = $data["profpic"];
            $account["signature"] = $data["signature"];

            // Encode the profpic file
            $targetPath = "pictures/";
            $fileUpload = $myDir . $targetPath . $account["profpic"];
            $data = file_get_contents($fileUpload);
            $base64 = base64_encode($data);
            $account["encodedProfpic"] = $base64;

            // Encode the signature file
            $targetPath = "signatures/";
            $fileUpload = $myDir . $targetPath . $account["signature"];
            $data = file_get_contents($fileUpload);
            $base64 = base64_encode($data);
            $account["encodedSignature"] = $base64;        
            
            
            $account["success"] = 1;
            if(!$data){
                $account["success"] = 0;    
            }

            // Get the contracts info with respect to the account

            $result = mysqli_query($con, "SELECT status
                                            FROM contracts 
                                            WHERE receiver = '$username'");
            $num = $result->num_rows;
            
            $account["waitingInbox"] = 0;
            $account["approvedInbox"] = 0;
            $account["rejectedInbox"] = 0;
            
            if($num > 0)
            {
                while($row = $result->fetch_assoc())
                {
                    if(strcmp($row['status'], "waiting") == 0){
                        $account["waitingInbox"]++;
                    }
                    else if(strcmp($row['status'], "approved") == 0){
                        $account["approvedInbox"]++;
                    }
                    else{
                        $account["rejectedInbox"]++;
                    }
                }
            }


            $result = mysqli_query($con, "SELECT status
                                            FROM contracts 
                                            WHERE sender = '$username'");

            $num = $result->num_rows;
            
            $account["waitingOutbox"] = 0;
            $account["approvedOutbox"] = 0;
            $account["rejectedOutbox"] = 0;
            
            if($num > 0)
            {
                while($row = $result->fetch_assoc())
                {
                    if(strcmp($row['status'], "waiting") == 0){
                        $account["waitingOutbox"]++;
                    }
                    else if(strcmp($row['status'], "approved") == 0){
                        $account["approvedOutbox"]++;
                    }
                    else{
                        $account["rejectedOutbox"]++;
                    }
                }
            }
            echo json_encode($account); 
        }
        echo mysqli_error($con); 
        mysqli_close($con);
    }
    catch(Exception $e) {
        $errorFile = fopen("log.txt","w");
        fwrite($errorFile, $e);
        fclose($errorFile);
    }
?>