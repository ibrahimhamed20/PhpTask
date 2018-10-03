<!DOCTYPE html>
<html lang="en" dir="ltr">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>PHP Task</title>
        <link rel="stylesheet" href="style.css">
    </head>
    <body>
<?php
session_start(); 
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "task";

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);
// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
ob_start();
if(isset($_POST["FName"])) {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $fname          = filter_var(@$_POST['FName'], FILTER_SANITIZE_STRING);
        $lname          = filter_var(@$_POST['LName'], FILTER_SANITIZE_STRING);
        $phone          = filter_var(@$_POST['Telephone'], FILTER_SANITIZE_NUMBER_INT);
        $street         = filter_var(@$_POST['Street'], FILTER_SANITIZE_STRING);
        $house          = filter_var(@$_POST['HouseNumber'], FILTER_SANITIZE_NUMBER_INT);
        $zip            = filter_var(@$_POST['ZipCode'], FILTER_SANITIZE_NUMBER_INT);
        $city           = filter_var(@$_POST['City'], FILTER_SANITIZE_STRING);
        $accountowner   = filter_var(@$_POST['AccountOwner'], FILTER_SANITIZE_STRING);
        $iban           = filter_var(@$_POST['IBAN'], FILTER_SANITIZE_STRING);
        $formErrors     = array();
        
        if (empty($fname)) {
                $formErrors[] = 'Fisrt Name is required!';
            }

        if (empty($lname)) {
            $formErrors[] = 'Last Name is required!';
        }

        if (empty($phone)) {
            $formErrors[] = 'Phone Number is required!';
        }

        if (empty($street)) {
            $formErrors[] = 'Street Name is required!';
        }

        if (empty($house)) {
            $formErrors[] = 'House Number is required!';
        }

        if (empty($zip)) {
            $formErrors[] = 'Zip Number is required!';
        }

        if (empty($city)) {
            $formErrors[] = 'City is required!';
        }

        if (empty($accountowner)) {
            $formErrors[] = 'Account Owner is required!';
        }

        // Check If There's No Error Proceed The Update Operation

        if (empty($formErrors)) {

            $sql = "INSERT INTO users (FName, LName, Telephone, Street, HouseNumber, ZipCode, City, AccountOwner, IBAN) VALUES ('$fname', '$lname', '$phone', '$street', '$house', '$zip', '$city', '$accountowner', '$iban')";

            if (mysqli_query($conn, $sql)) {
                echo '<div id="myForm"><h1>New user created successfully</h1></div>';
                $customerId = $conn->insert_id;
            } else {
                echo "<div id='myForm'><h1>Error: " . $sql . "<br>" . mysqli_error($conn).'</h1></div>';
            }
        }
    }
    if (! empty($formErrors)) {
        foreach ($formErrors as $error) {
            echo '<h3>' . $error . '</h3>';
        }
    }
    if (isset($succesMsg)) {
        echo '<h3>' . $succesMsg . '</h3>';
    }
    try {
        //API Url
        $url = 'https://37f32cl571.execute-api.eu-central-1.amazonaws.com/default/wunderfleet-recruiting-backend-dev-save-payment-data';

        //Initiate cURL.
        $ch = curl_init($url);

        //The JSON data.
        $jsonData = array(
            'customerId' => $customerId,
            'iban' => $iban,
            'owner' => $accountowner
        );

        //Encode the array into JSON.
        $jsonDataEncoded = json_encode($jsonData);

        //Tell cURL that we want to send a POST request.
        curl_setopt($ch, CURLOPT_POST, 1);

        //Attach our encoded JSON string to the POST fields.
        curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonDataEncoded);

        //Set the content type to application/json
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json')); 

        //Execute the request
        @$result = curl_exec($ch);
        
        //close cURL resource
        curl_close($ch);
        $data = json_decode(trim($result), true);
        if (is_array($data) || is_object($data)) {
            foreach ($data as $dt){
                var_dump($dt['paymentDataId']);
            }
        }
        //$arr = json_decode($result,true);
        //echo '<br>'.$arr['paymentDataId']->paymentDataId;
        
        //$paymentDataId = json_decode($result, true);
        //echo '<br>'. $paymentDataId->paymentDataId;
    } catch(Exception $e) {
        echo "API Connection failed: " . $e->getMessage();
    }
}
ob_end_flush();
mysqli_close($conn);
session_destroy();
?>
    </body>
</html>
