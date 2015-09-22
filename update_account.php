<?php
    try {
        $con = mysqli_connect('localhost','root','8994','contractManager');

        if (mysqli_connect_errno($con))
        {
           echo "Failed to connect to MySQL: " . mysqli_connect_error();
        }
        else{
            $username = $_POST['username'];
            $column = $_POST['column'];
            $content = $_POST['content'];
            $result = mysqli_query($con, "UPDATE accounts SET $column = '$content' WHERE username = '$username'");
            
            $response["exists"] = true;
            
            if(!$result)
            {
                $response["exists"] = false;
            }

            // Echo final json response to client
            echo json_encode($response);

        } 
        echo mysqli_error();
        mysqli_close($con);
    }
    catch(Exception $e) {
        $errorFile = fopen("log.txt","w");
        fwrite($errorFile, $e);
        fclose($errorFile);
    }
    
?>