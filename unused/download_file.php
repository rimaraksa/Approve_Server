<?php

    $con = mysqli_connect('localhost','root','8994','contractManager');

    if (mysqli_connect_errno($con))
    {
       echo "Failed to connect to MySQL: " . mysqli_connect_error();
    }
    else{
        $contractKey = $_POST['contractKey'];
        $fileType = $_POST['fileType'];
        $targetPath = $_POST['targetPath'];
         
        if(strcmp($targetPath, "pictures/") == 0){
            $fileType = 0;   //picture
        }
        else{
            $fileType = 1;   //video
        }

        // array for final json respone
        $response = array();
         
        
        if($fileType == 1)
        {   
            $result = mysqli_query($con, "SELECT * FROM contracts WHERE contractKey = '$contractKey'");
            $response["exists"] = true;
            if(!$result)
            {
                $response["exists"] = false;
            }
            else{
                $data = mysqli_fetch_array($result);
                $response["video"] = data["video"];
            }
        }
        else{
            $result = mysqli_query($con, "SELECT * FROM accounts WHERE username = '$username'");
            $response["exists"] = true;
            if(!$result)
            {
                $response["exists"] = false;
            }
            else{
                $data = mysqli_fetch_array($result);
                $response["picture"] = data["picture"];
            }
        }
        // Echo final json response to client
        echo json_encode($response);
        mysqli_close($con);
        
    }

    
?>