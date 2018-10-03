<!--?php
ob_start();
$con = new PDO("mysql:host=localhost;dbname=task", "root", "");
$message = '';
if(isset($_POST["FName"])) {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $formErrors     = array();
        $fname          = filter_var(@$_POST['FName'], FILTER_SANITIZE_STRING);
        $lname          = filter_var(@$_POST['LName'], FILTER_SANITIZE_STRING);
        $phone          = filter_var(@$_POST['Telephone'], FILTER_SANITIZE_NUMBER_INT);
        $street         = filter_var(@$_POST['Street'], FILTER_SANITIZE_STRING);
        $house          = filter_var(@$_POST['HouseNumber'], FILTER_SANITIZE_NUMBER_INT);
        $zip            = filter_var(@$_POST['ZipCode'], FILTER_SANITIZE_NUMBER_INT);
        $city           = filter_var(@$_POST['City'], FILTER_SANITIZE_STRING);
        $accountowner   = filter_var(@$_POST['AccountOwner'], FILTER_SANITIZE_STRING);
        $iban           = filter_var(@$_POST['IBAN'], FILTER_SANITIZE_STRING);

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
        if (!empty($iban || empty($ibar))) {
            $formErrors[] = 'IBAN is not required!';
        }

        // Check If There's No Error Proceed The Update Operation

        if (empty($formErrors)) {

            // Insert Userinfo In Database

            $stmt = $con->prepare("INSERT INTO users (FName, LName, Telephone, Street, HouseNumber, ZipCode, City, AccountOwner, IBAN) VALUES (:zfname, :zlname, :zphone, :zstreet, :zhouse, :zzip, :zcity, :zaccountowner, :ziban)");

            $stmt->execute(array(
                ':zfname'         => $fname,
                ':zlname' 	      => $lname,
                ':zphone' 	      => $phone,
                ':zstreet' 	      => $street,
                ':zhouse' 	      => $house,
                ':zzip'	          => $zip,
                ':zcity'	      => $city,
                ':zaccountowner'  => $accountowner,
                ':ziban'	      => $iban
            ));
            
            if($stmt){
                $message = '<h3>Registration Completed Successfully</h3>';
            } else {
                $message = '<h3>There is an error in Registration</h3>';
            }

            // Echo Success Message
            //$theMsg = "Successfully Saved in Database";
            //if ($stmt) {
             //   $succesMsg = $theMsg;
            //}
        }
    }
?-->
<?php session_start();?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>PHP Task</title>
        <link rel="stylesheet" href="style.css">
    </head>
    <body>
        <form id="myForm" method="POST" action="saveData.php" autocomplete="off">
            <h1>Register:</h1>
            <!-- One "tab" for each step in the form: -->
            <div class="tab">Personal Information:
                <p><input type="text" name="FName" placeholder="First name..." oninput="this.className = ''"></p>
                <p><input type="text" name="LName" placeholder="Last name..." oninput="this.className = ''"></p>
                <p><input type="tel" name="Telephone" placeholder="Telephone..." oninput="this.className = ''"></p>
            </div>

            <div class="tab">Address Information:
                <p><input type="text" name="Street" placeholder="Street..." oninput="this.className = ''"></p>
                <p><input type="number" name="HouseNumber" placeholder="House Number..." oninput="this.className = ''"></p>
                <p><input type="number" name="ZipCode" placeholder="Zip Code..." oninput="this.className = ''"></p>
                <p><input type="text" name="City" placeholder="City..." oninput="this.className = ''"></p>
            </div>

            <div class="tab">Payment Information:
                <p><input type="text" name="AccountOwner" placeholder="Account Owner..." oninput="this.className = ''"></p>
                <p><input type="text" name="IBAN" placeholder="IBAN..." oninput="this.className = ''"></p>
            </div>
            
            <div style="overflow:auto;">
                <div style="float:right;">
                    <button type="button" id="prevBtn" onclick="nextPrev(-1)">Previous</button>
                    <button type="button" name="submit" id="nextBtn" onclick="nextPrev(1)">Next</button>
                    <button type="submit" id="sub" name="submit" style="display:none;">Submit</button>
                </div>
            </div>
            <!-- Circles which indicates the steps of the form: -->
            <div style="text-align:center;margin-top:40px;">
                <span class="step"></span>
                <span class="step"></span>
                <span class="step"></span>
                <span class="step"></span>
            </div>
        </form>
        <script src="script.js"></script>
    </body>
</html>
<?php session_destroy();?>
<!--?php 
    if (! empty($formErrors)) {
        foreach ($formErrors as $error) {
            echo '<h3>' . $error . '</h3>';
        }
    }
    if (isset($succesMsg)) {
        echo '<h3>' . $succesMsg . '</h3>';
    }
}
ob_end_flush();
?-->