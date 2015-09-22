<?php
    try {

        $con = mysqli_connect('localhost','root','8994','contractManager');

        if (mysqli_connect_errno($con))
        {
           echo "Failed to connect to MySQL: " . mysqli_connect_error();
        }
        else{
            $contractKey = $_POST['contractKey'];
            $reasonForRejection = $_POST['reasonForRejection'];
            $dateAppOrReject = $_POST['date'];
             
            // array for final json respone
            $response = array();
            $result = mysqli_query($con, "UPDATE contracts SET status = 'rejected', dateAppOrReject = '$dateAppOrReject', reasonForRejection = '$reasonForRejection' WHERE contractKey = '$contractKey'");
            $response["exists"] = true;
            if(!$result)
            {
                $response["exists"] = false;
            }

            // Echo final json response to client
            echo json_encode($response);    
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