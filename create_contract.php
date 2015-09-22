<?php

    try {

        $con = mysqli_connect('localhost','root','8994','contractManager');

        if (mysqli_connect_errno($con))
        {
           echo "Failed to connect to MySQL: " . mysqli_connect_error();
        }
        else{
            $contractKey = $_POST['contractKey'];
            $sender = $_POST['sender'];
            $receiver = $_POST['receiver'];
            $subject = $_POST['subject'];
            $body = $_POST['body'];
            $location = $_POST['location'];
            $status = $_POST['status'];
            $dateRequest = $_POST['dateRequest'];

            $result = mysqli_query($con, "SELECT * FROM accounts WHERE username = '$receiver'");

            $data = mysqli_fetch_array($result);

            $contract = array();
            $contract["receiver_exists"] = true;

            if($data)
            {
                $result = mysqli_query($con, "INSERT INTO contracts(contractKey, sender, receiver, subject, body, location, status, dateRequest) VALUES('$contractKey', '$sender', '$receiver', '$subject', '$body', '$location', '$status', '$dateRequest')");

                $contract["contractKey_available"] = true;

                if($result)
                {
                    $result = mysqli_query($con, "SELECT * FROM contracts WHERE contractKey = '$contractKey'");
                    $data = mysqli_fetch_array($result);
                    $contract_id = $data["contract_id"];

                    $result = mysqli_query($con, "SELECT * FROM accounts WHERE username = '$sender'");
                    $data = mysqli_fetch_array($result);
                    $sender_id = $data["account_id"];

                    $result = mysqli_query($con, "SELECT * FROM accounts WHERE username = '$receiver'");
                    $data = mysqli_fetch_array($result);
                    $receiver_id = $data["account_id"];
                    
                    $result = mysqli_query($con, "INSERT INTO account_contract(contract_id, sender_id, receiver_id) VALUES('$contract_id', '$sender_id', '$receiver_id')");


                    echo json_encode($contract);
                    
                }
                else{
                    $contract["contractKey_available"] = false;
                    echo json_encode($contract);
                }
            }
            else{
                $contract["receiver_exists"] = false;
                echo json_encode($contract);
            }
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