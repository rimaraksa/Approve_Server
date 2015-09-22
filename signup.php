<?php
    require_once 'vendor/autoload.php';

    try {
        $con = mysqli_connect('localhost','root','8994','contractManager');

        if (mysqli_connect_errno($con))
        {
           echo "Failed to connect to MySQL: " . mysqli_connect_error();
        }
        else{
            $name = $_POST['name'];
            $nric = $_POST['nric'];
            $phone = $_POST['phone'];
            $username = $_POST['username'];
            $password = $_POST['password'];
            $profpic = $_POST['profpic'];
            $signature = $_POST['signature'];

            $account = array();

            // Getting server ip address
            $serverIP = gethostbyname(gethostname());
            // Getting target folder of the signature
            $targetPath = "signatures/";
            // Final file url that is being uploaded
            $fileUpload = '/Users/rimaraksa/Sites/android_connect' . '/' . $targetPath . $signature;

            // Change X-Mashape-Key, album, and album key
            $X_Mashape_Key = "yxgaLRvhf3msh8lKOCP4kiyng64Np1NlssnjsngXI6UmUTXXBN";
            $album = "Approve";
            $albumkey = "93047593e2f2fb8176be64548e998490f3ee24ec4ce7d091d7082eea18051c5d";
            $url = "https://lambda-face-recognition.p.mashape.com/album_train";

            // Train the album in Lambdal server
            $response = Unirest\Request::post($url,
              array(
                "X-Mashape-Key" => $X_Mashape_Key
              ),
              array(
                "album" => $album,
                "albumkey" => $albumkey,
                "entryid" => $username,
                "files" => Unirest\File::add($fileUpload)
              )
            );

            // Write the respose after training the album
            $file = fopen("log_album_train.txt", "w");
            fwrite($file, $response->raw_body);
            fclose($file);

            // URL for rebuilding an album
            $url = "https://lambda-face-recognition.p.mashape.com/album_rebuild?album=" . $album . "&albumkey=" . $albumkey;

            // Rebuild the album in Lambdal server
            $response = Unirest\Request::get($url,
              array(
                "X-Mashape-Key" => $X_Mashape_Key,
                "Accept" => "application/json"
              )
            );

            // Write the respose from rebuilding the album
            $file = fopen("log_album_rebuild.txt", "w");
            fwrite($file, $response->raw_body);
            fclose($file);

            // Prepare for returning the result to user
            // The file was not uploaded successfully to Lambal server
            if(!$response){
                $account["signatureUploadFails"] = true;
                $account["exists"] = false;
            }
            // The file was uploaded successfully to Lambal server
            else{
                $result = mysqli_query($con, "INSERT INTO accounts(name, nric, phone, username, password, profpic, signature) VALUES('$name', '$nric', '$phone', '$username', '$password', '$profpic', '$signature')");
                
                $file = fopen("log_insert_account.txt","w");
                fwrite($file, $result);
                fclose($file);

                $account["exists"] = true;
                $account["signatureUploadFails"] = false;

                if(!$result)
                {
                    echo json_encode($account);
                }
                else{
                    // user has not existed
                    $account["exists"] = false;

                    $result = mysqli_query($con, "SELECT * FROM accounts WHERE username = '$username' AND password = '$password'");
                    $data = mysqli_fetch_array($result);

                    $account["account_id"] = $data["account_id"];

                    $file = fopen("log_select_account.txt","w");
                    fwrite($file, $data);
                    fclose($file);

                    echo json_encode($account);

                }
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