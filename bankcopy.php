<?php
    header('Content-type: text/plain');
    // include 'DBConnect.php';
    $dsn = 'mysql:dbname=ussd_db; host=localhost;';
    $user = 'root';
    $password = '';

try{
    $conn = new PDO($dsn, $user, $password);
}
catch(PDOException $e) {
echo "END Contact Admin";
}

$phoneNumber = $_GET['phoneNumber'];
$sessionId = $_GET['sessionId'];
$serviceCode = $_GET['serviceCode'];
$user_response = $_GET['text'];

//show first menu if user_response is empty
//localhost/ussd_class/app1.php?phoneNumber=0725455&sessionId=646464646&serviceCode=123&text=1*1*1
if ($user_response=="") {
display_menu(); //level 0
}


$ussd_exploded_string = explode("*", $user_response);
$level = count($ussd_exploded_string);
echo "level is $level\n";

if ($user_response!="" and $level > 0) {
switch ($ussd_exploded_string[0]) {
    case 1:
        register($ussd_exploded_string, $conn);
        break;
    case 2:
        login($ussd_exploded_string, $conn);
        break;    
    //totdo more cases
    default:
        $ussd_text = "Invalid. Try Again";
        ussd_stop($ussd_text);
        break;
}//end

}//end

//handle deposit
//we need to check if user is logged in before proceeding
if (count($ussd_exploded_string) > 2 and count($ussd_exploded_string) < 4) {
    session_start();
    if (!isset($_SESSION['user_id'])) {
        $ussd_text = "Session expired";
        ussd_stop($ussd_text);
    } else{
        switch ($ussd_exploded_string[2]) {
            case 1:
                # deposit works...
                if (count($ussd_exploded_string) == 3) {
                    $ussd_text = "Enter amount to deposit";
                    ussd_proceed($ussd_text);
                }
                if (count($ussd_exploded_string) == 4) {
                    $deposit_amount = $ussd_exploded_string[3];
                    $sql = "INSERT INTO deposits (user_id, deposit_amount) VALUES (?,?)";
                    $stmt = $conn->prepare($sql);
                    $stmt->execute([$_SESSION['user_id'], $deposit_amount]);

                    if ($stmt->errorCode()==0) {
                        $ussd_text = "You have deposited ".$deposit_amount."KSHS, thank you for banking with us.";
                        ussd_stop($ussd_text);
                    } else {
                        $ussd_text = "Error Occured During Registration. Tray Again later";
                        ussd_proceed($ussd_text);
                    }
                } //end if
                break;
            case 2:
                # withdraw...
                $ussd_text = "Enter amount to withdraw.";
                ussd_proceed($ussd_text);
                break;
            case 3:
                # check balance...
                $sql = "SELECT SUM(deposit_amount) FROM deposits WHERE user_id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->execute([$_SESSION['user_id']]);
                $row = $stmt->fetch();                
                
                $ussd_text = "You balance is ".$row[0]." KSHS, thank you for banking with us.";
                ussd_stop($ussd_text);
                break;    
            default:
                # code...
                break;
        }
    }
}

if (count($ussd_exploded_string) == 4) {
    session_start();
    if (!isset($_SESSION['user_id'])) {
        $ussd_text = "Session expired";
        ussd_stop($ussd_text);
    } else{
        $amount_withdrawable = $ussd_exploded_string[3];

        $sql = "INSERT INTO withdrawals (account_id, withdraw_amount) VALUES (?,?)";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$_SESSION['user_id'], $amount_withdrawable]);

        if ($stmt->errorCode() == 0) {
            $ussd_text = "You have withdrawn $amount_withdrawable";
            ussd_stop($ussd_text);
        } else {
            $ussd_text = "Error Occured During withdrawing. Tray Again later";
            ussd_proceed($ussd_text);
        }
    }
}

function register($ussd_exploded_string, $conn){
//reg code here
//connect to database
//request inputs from user
//trigger SQL to save.
if (count($ussd_exploded_string) == 1) {
$ussd_text = "Enter your Surname";
ussd_proceed($ussd_text);
}
if (count($ussd_exploded_string) == 2) {
$ussd_text = "Enter your Phone";
ussd_proceed($ussd_text);
}

if (count($ussd_exploded_string) == 3) {
$ussd_text = "Enter your Email";
ussd_proceed($ussd_text);
}

if (count($ussd_exploded_string) == 4) {
$ussd_text = "Enter 4-Digit PIN";
ussd_proceed($ussd_text);
}

if (count($ussd_exploded_string) == 5) {
//save to mysql
//mysqli, PDO.. PHP Daya Objects
$surname = $ussd_exploded_string[1];
$phoneNumber = $ussd_exploded_string[2];
$email = $ussd_exploded_string[3];
$pin_code = $ussd_exploded_string[4];

$sql = "INSERT INTO users_tbl (surname, phoneNumber,email,pin_code) VALUES (?,?,?,?)";

$stmt = $conn->prepare($sql);
$stmt->execute([$surname, $phoneNumber,$email, $pin_code]);

if ($stmt->errorCode()==0) {
    $ussd_text = "Thank you ".$surname." Your details have been saved";
    ussd_stop($ussd_text);
} else {
    $ussd_text = "Error Occured During Registration. Tray Again later";
    ussd_proceed($ussd_text);
}
}//end
}//end

function login($ussd_exploded_string, $conn){
    //reg code here
    //connect to database
    //request inputs from user
    //trigger SQL to save.
    if (count($ussd_exploded_string) == 1) {
        $ussd_text = "Enter your pin";
        ussd_proceed($ussd_text);
    }
    if (count($ussd_exploded_string) == 2) {
        $pin_code = $ussd_exploded_string[1];
        $sql = "SELECT * FROM users_tbl WHERE pin_code = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$pin_code]);
        $row = $stmt->fetch();

        $count = $stmt->rowCount();
        if ($count == 0) {
            $ussd_text = "Login Failed";
            ussd_stop($ussd_text);
        } else if ($count == 1) {
            // main_menu($ussd_exploded_string, $conn);
            $user_id = $row['user_id'];
            session_start();
            $_SESSION['user_id'] = $user_id;
            $ussd_text = "Welcome user ".$user_id." XYZee\n1. Deposit.\n2. Withdraw.\n3. Check balance.";
            ussd_proceed($ussd_text);
           
        } else {
            $ussd_text = "Contact admin";
            ussd_stop($ussd_text);
        }
    }
}//end

function display_menu(){
    $ussd_text = "Welcome to XYZee\n1.Register\n2.Login\n3.Help";
    ussd_proceed($ussd_text);
}


# proceed and stop
function ussd_proceed($ussd_text){
echo "CON $ussd_text";
}


function ussd_stop($ussd_text){
echo "END $ussd_text";
}


?>