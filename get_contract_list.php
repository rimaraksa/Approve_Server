<?php

    try {

        $con = mysqli_connect('localhost','root','8994','contractManager');
        $myDir = '/Users/rimaraksa/Sites/android_connect/';

        if (mysqli_connect_errno($con))
        {
           echo "Failed to connect to MySQL: " . mysqli_connect_error();
        }
        else
        {
            $account_id = intval($_POST['account_id']);
            $role = $_POST['role'];
            $target = $_POST['target'];
            $status = $_POST['status'];

            $role_id = $role . "_id";
            $target_id = $target . "_id";

            // find the contract ID depending on the status of requestor
            if(strcmp($status, "waiting") == 0){
                $order_by = "dateRequest";
            }
            else{
                $order_by = "dateAppOrReject";
            }
            

            $result = mysqli_query($con, "SELECT contractKey, sender.username AS sender, receiver.username AS receiver,
                                            sender.name AS senderName, receiver.name AS receiverName, sender.phone AS senderPhone, receiver.phone AS receiverPhone, subject, body, location, status,
                                            dateRequest, dateAppOrReject, video, reasonForRejection, sender.profpic AS senderProfpic, 
                                            receiver.profpic AS receiverProfpic 
                                            
                                            FROM account_contract AS ac, contracts AS c, accounts AS sender, accounts AS receiver 
                                            
                                            WHERE ac.contract_id = c.contract_id AND ac.sender_id = sender.account_id AND 
                                            ac.receiver_id = receiver.account_id AND $role_id = $account_id AND c.status = '$status'
                                            ORDER BY $order_by");
            $num = $result->num_rows;

            $contracts = array();
            // $contracts["isEmpty"] = false;
            if($num > 0)
            {
                while($row = $result->fetch_assoc())
                {
                    $profpicTarget = $row[($target . 'Profpic')];

                    $targetPath = "pictures/";
                    $fileUpload = $myDir . $targetPath . $profpicTarget;

                    $data = file_get_contents($fileUpload);
                    $base64 = base64_encode($data);

                    $contracts[] = array(
                        'contractKey' => $row['contractKey'],
                        'sender' => $row['sender'],
                        'receiver' => $row['receiver'],
                        'senderName' => $row['senderName'],
                        'receiverName' => $row['receiverName'],
                        'senderPhone' => $row['senderPhone'],
                        'receiverPhone' => $row['receiverPhone'],
                        'subject' => $row['subject'],
                        'body' => $row['body'],
                        'location' => $row['location'],
                        'status' => $row['status'],
                        'dateRequest' => $row['dateRequest'],
                        'dateAppOrReject' => $row['dateAppOrReject'],
                        'video' => $row['video'],
                        'reasonForRejection' => $row['reasonForRejection'],
                        'encodedProfpicTarget' => $base64
                   );

                    
                }

                $file = fopen("log_get_contract_list.txt", "w");
                fwrite($file, print_r($result, true));
                fclose($file);
            }
            echo json_encode($contracts);
        
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