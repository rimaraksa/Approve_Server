<?php
    require_once 'vendor/autoload.php';

    $myDir = '/Users/rimaraksa/Sites/android_connect/';

    try {
        $data = $_POST['data'];
        $username = $_POST['username'];

        $imageData = base64_decode($data);
        $source = imagecreatefromstring($imageData);

        $t = time();
        $fileName = $username . "_" . date("Y-m-d-G",$t) . "_" . $t . ".jpg";

        // getting target folder of the signature
        $targetPath = "verified_signatures/";
        $targetFileName = $targetPath . $fileName;

        $imageSave = imagejpeg($source, $targetFileName, 50);
        imagedestroy($source);
        
        // final file url that is being uploaded
        $fileUpload = $myDir . $targetPath . $fileName;
        
        // Change X-Mashape-Key, album, and album key
        $X_Mashape_Key = "yxgaLRvhf3msh8lKOCP4kiyng64Np1NlssnjsngXI6UmUTXXBN";
        $album = "Approve";
        $albumkey = "93047593e2f2fb8176be64548e998490f3ee24ec4ce7d091d7082eea18051c5d";
        
        $url = "https://lambda-face-recognition.p.mashape.com/recognize";

        // Recognize the pic in Lambdal server
        $response = Unirest\Request::post($url,
          array(
            "X-Mashape-Key" => $X_Mashape_Key
          ),
          array(
            "album" => $album,
            "albumkey" => $albumkey,
            "files" => Unirest\File::add($fileUpload)
          )
        );

        // Write the respose after training the album
        $file = fopen("log_verify_signature.txt","w");
        fwrite($file, $response->raw_body);
        fclose($file);

        // To decode json response as an array
        $response_array = array();
        $response_array = json_decode($response->raw_body, true);
        
        // Prepare for returning the result to user
        $account = array();
        $account["error"] = false;
        $account["username"] = $response_array["photos"][0]["tags"][0]["uids"][0]["prediction"];
        $account["confidence"] = $response_array["photos"][0]["tags"][0]["uids"][0]["confidence"];

        if(strcmp($username, $account["username"]) == 0 && $account["confidence"] > 0.25){
            $account["matched"] = true;
        }
        else{
            $account["matched"] = false;
        }

        // Write the username of the user that is recognized with the face uploaded
        $file = fopen("matched_username.txt","w");
        fwrite($file, $account["username"]);
        fclose($file);

        echo json_encode($account);
        
    } 
    catch(Exception $e) {
        $errorFile = fopen("log.txt","w");
        fwrite($errorFile, $e);
        fclose($errorFile);

        $account["error"] = true;
    }
    
?>