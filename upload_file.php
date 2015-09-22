<?php
    try {

        $con = mysqli_connect('localhost','root','8994','contractManager');

        if (mysqli_connect_errno($con))
        {
           echo "Failed to connect to MySQL: " . mysqli_connect_error();
        }
        else{
            $targetPath = $_POST['targetPath'];

            if(strcmp($targetPath, "pictures/") == 0){
                $fileType = 0;   //picture
            }
            else if(strcmp($targetPath, "videos/") == 0){
                $fileType = 1;   //video
            }
            else{
                $fileType = 2;   //signature
            }
            $key = $_POST['key'];
             
            // array for final json respone
            $response = array();
             
            // getting server ip address
            $serverIP = gethostbyname(gethostname());
             
            // final file url that is being uploaded
            $fileUploadURL = 'http://' . $serverIP . '/' . '~rimaraksa/android_connect' . '/' . $targetPath;

             
            if (isset($_FILES['data']['name'])) {

                if($fileType == 2){
                    $targetPath_1 = $targetPath . basename($_FILES['data']['name']);
                    $targetPath_2 = "pictures/" . basename($_FILES['data']['name']);
                    $fileUploadURL_2 = 'http://' . $serverIP . '/' . '~rimaraksa/android_connect' . '/' . $targetPath_2;
                    $fileName = basename($_FILES['data']['name']);
                    $response['fileName'] = $fileName;
                 
                    try {
                        // Throws exception incase file is not being moved
                        if (!move_uploaded_file($_FILES['data']['tmp_name'], $targetPath_1)) {
                            // make error flag true
                            $response['error'] = true;
                            $response['message'] = 'Could not move the file!';
                        }
                        else{
                            copy($targetPath_1, $targetPath_2);
                            // File successfully uploaded
                            $response['message'] = 'File uploaded successfully!';
                            $response['error'] = false;
                            $response['filePath'] = $fileUploadURL . basename($_FILES['data']['name']) . ', ' . $fileUploadURL_2;    
                        }

                        
                    } 
                    catch (Exception $e) {
                        // Exception occurred. Make error flag true
                        $response['error'] = true;
                        $response['message'] = $e->getMessage();
                    }
                }
                else{

                    $targetPath = $targetPath . basename($_FILES['data']['name']);
                    $fileName = basename($_FILES['data']['name']);
                    $response['fileName'] = $fileName;
                 
                    try {
                        // Throws exception incase file is not being moved
                        if (!move_uploaded_file($_FILES['data']['tmp_name'], $targetPath)) {
                            // make error flag true
                            $response['error'] = true;
                            $response['message'] = 'Could not move the file!';
                        }
                        else{      
                            // File successfully uploaded
                            $response['message'] = 'File uploaded successfully!';
                            $response['error'] = false;
                            $response['filePath'] = $fileUploadURL . basename($_FILES['data']['name']);
                        }

                    } 
                    catch (Exception $e) {
                        // Exception occurred. Make error flag true
                        $response['error'] = true;
                        $response['message'] = $e->getMessage();
                    }
                }

                
            } 
            else {
                // File parameter is missing
                $response['error'] = true;
                $response['message'] = 'No file is received!';
            }

            if($fileType == 1)
            {   
                $dateAppOrReject = $_POST['date'];
                $result = mysqli_query($con, "UPDATE contracts SET status = 'approved', dateAppOrReject = '$dateAppOrReject', video = '$fileName' WHERE contractKey = '$key'");
                $response["exists"] = true;
                if(!$result)
                {
                    $response["exists"] = false;
                }

            }
            else if($fileType == 0){
                $result = mysqli_query($con, "UPDATE accounts SET profpic = '$fileName' WHERE username = '$key'");
                $response["exists"] = true;
                if(!$result)
                {
                    $response["exists"] = false;
                }

            echo json_encode($response);

            }
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