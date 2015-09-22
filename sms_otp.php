<?php

    try {
        $return = array();
        $name = $_POST['name'];
        $phone = $_POST['phone'];
        $otp = $_POST['otp'];

        $senderid = "ApproveInc";
        $message = "From $senderid. Hi $name, your one-time password (OTP) for contract approval is $otp.";

        // Approve App
        $Authorization = "Basic cmltYXJha3NhOkQ0OEQzNEVGLUEyOTMtQUI3Ri00Q0UwLTI1MkQyMkZBN0IxMg==";
        $X_Mashape_Key = "aeACg2lKGXmshviH0uqTeUN3U9Llp10lnKLjsnPAuKUX36qdjn";
        $key = "D48D34EF-A293-AB7F-4CE0-252D22FA7B12";
        $usernameAPI = "rimaraksa";
        $url = "https://rest.clicksend.com/v3/sms/send";

        // Request for an SMS API service

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, "{
          \"messages\": [
            {
              \"source\": \"php\",
              \"from\": \"$senderid\",
              \"body\": \"$message\",
              \"to\": \"$phone\"
            }
          ]
        }");

        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
          "Content-Type: application/json",
          "Authorization: $Authorization"
        ));

        $response = curl_exec($ch);
        curl_close($ch);

        $file = fopen("log_sms_otp.txt", "w");
        fwrite($file, $response);
        fclose($file);

        $response_array = array();
        $response_array = json_decode($response, true);


        $return = array();
        $return["smsOTPSuccess"] = (strcmp($response_array["response_code"], "SUCCESS") == 0);
        echo json_encode($return);
    } 
    catch(Exception $e) {
        $errorFile = fopen("log.txt","w");
        fwrite($errorFile, $e);
        fclose($errorFile);
    }
?>