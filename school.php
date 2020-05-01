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
//localhost/ussd_class/school.php?phoneNumber=0725455&sessionId=646464646&serviceCode=123&text=1*1*1
if ($user_response=="") {
    display_menu(); //level 0
}


$ussd_exploded_string = explode("*", $user_response);
$level = count($ussd_exploded_string);
// echo "level is $level\n";

if ($user_response!="" and $level > 0) {
switch ($ussd_exploded_string[0]) {
    case 1:
        login($ussd_exploded_string, $conn, $phoneNumber, $sessionId);
        break;
    case 2:
        $ussd_text = "Please select an option by pressing a number of the service you would like to access.";
        ussd_stop($ussd_text);
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
   // session_start();
    //if (!isset($_SESSION['user_id'])) {
        // $ussd_text = "Session expired";
        // ussd_stop($ussd_text);
        $sql = "SELECT student_session_id FROM students_tbl WHERE student_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute(['1']);
        $row = $stmt->fetch();
        $count = $stmt->rowCount();
        
        if ($count == 0) {
            $ussd_text = "Session Expired^";
            ussd_stop($ussd_text);
        } 

    //} 
    else {
 
        $user_id_fromdb = $row['student_session_id'];
        switch ($ussd_exploded_string[2]) {
            case 1:
                # Academic statements...
                $sql = "SELECT * FROM exams_tbl";
                $stmt = $conn->prepare($sql);
                $stmt->execute();
                $row = $stmt->fetchAll(); 
                
                $fTerm = "FIRST TERM EXAMS\n ";
                $fTerm .= "ENGLISH ".$row[0]["ex_english"]."\n ";
                $fTerm .= "MATHS ".$row[0]["ex_maths"]."\n ";
                $fTerm .= "CHEMISTRY ".$row[0]["ex_chemistry"]."\n ";
                $fTerm .= "BIOLOGY ".$row[0]["ex_biology"]."\n ";
                $fTerm .= "KISWAHILI ".$row[0]["ex_kiswahili"]."\n\n ";
                $fTerm .= "TOTAL ".$row[0]["ex_total"]."\n ";
                $fTerm .= $row[0]["ex_term"]."\n\n";

                $secondTerm = "SECOND TERM \n";
                $secondTerm .= "ENGLISH ".$row[1]["ex_english"]."\n ";
                $secondTerm .= "MATHS ".$row[1]["ex_maths"]."\n ";
                $secondTerm .= "CHEMISTRY ".$row[1]["ex_chemistry"]."\n ";
                $secondTerm .= "BIOLOGY ".$row[1]["ex_biology"]."\n ";
                $secondTerm .= "KISWAHILI ".$row[1]["ex_kiswahili"]."\n\n ";
                $secondTerm .= "TOTAL ".$row[1]["ex_total"]."\n ";
                $secondTerm .= $row[1]["ex_term"]."\n"; 
                
                $thirdTerm = "THIRD TERM \n";
                $thirdTerm .= "ENGLISH ".$row[2]["ex_english"]."\n ";
                $thirdTerm .= "MATHS ".$row[2]["ex_maths"]."\n ";
                $thirdTerm .= "CHEMISTRY ".$row[2]["ex_chemistry"]."\n ";
                $thirdTerm .= "BIOLOGY ".$row[2]["ex_biology"]."\n ";
                $thirdTerm .= "KISWAHILI ".$row[2]["ex_kiswahili"]."\n\n ";
                $thirdTerm .= "TOTAL ".$row[2]["ex_total"]."\n ";
                $thirdTerm .= $row[2]["ex_term"]."\n";

                $ussd_text = "$fTerm\n$secondTerm\n$thirdTerm";
                ussd_stop($ussd_text);
                break;
            case 2:
                # Fee statements...
                $sql = "SELECT `fee_amount`, `date_paid` FROM `students_fees_tbl` WHERE `student_id` = ?";
                $stmt = $conn->prepare($sql);
                $stmt->execute([$user_id_fromdb]);
                $row = $stmt->fetch();            
                $ussd_text = "Your fee balance is ".$row[0]." KSHS  to be paid before $row[1].";
                ussd_stop($ussd_text);
                break;
            case 3:
                # check balance...
                $sql = "SELECT event_body FROM events_tbl";
                $stmt = $conn->prepare($sql);
                $stmt->execute();
                $row = $stmt->fetchAll(); 
                
                $event = '';

                foreach($row as $value) {
                    $event = $event.$value["event_body"]."\n";
                }

                $ussd_text = "The following are the school's events for the next term.\n$event";
                ussd_stop($ussd_text);
                break;  
            case 4:
                # Fees structure...
                $sql = "SELECT fee_first_term, fee_second_term, fee_third_term, fee_year FROM fees_structure_tbl";
                $stmt = $conn->prepare($sql);
                $stmt->execute();
                $row = $stmt->fetchAll(); 
                
                $fees = '';

                // foreach($row as $value) {
                //     $fees = "1. FIRST TERM ". $fees.$value["fee_first_term"]."\n";
                //     $fees .= "2. SECOND TERM ". $fees.$value["fee_second_term"]."\n";
                //     $fees .= "3. THIRD TERM ". $fees.$value["fee_third_term"]."\n";
                //     $fees .= "4. ACADEMIC YEAR ". $fees.$value["fee_year"]."\n";
                // }
                $fTerm = "FIRT TERM ".$row[0]["fee_first_term"];
                $sTerm = "SECOND TERM ".$row[0]["fee_second_term"];
                $tTerm = "THIRD TERM ".$row[0]["fee_third_term"];
                $year = "ACADEMIC YEAR ".$row[0]["fee_year"];

                $ussd_text = "This is next year's fee structure.\n1. $fTerm\n2. $sTerm\n3. $tTerm\n4. $year\n";
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

function login($ussd_exploded_string, $conn, $phoneNumber, $sessionId){
    //reg code here
    //connect to database
    //request inputs from user
    //trigger SQL to save.
    if (count($ussd_exploded_string) == 1) {
        $ussd_text = "Enter your admission number";
        ussd_proceed($ussd_text);
    }
    if (count($ussd_exploded_string) == 2) {
        $pin_code = $ussd_exploded_string[1];
        $sql = "SELECT * FROM students_tbl WHERE student_adm_number = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$pin_code]);
        $row = $stmt->fetch();

        $count = $stmt->rowCount();
        if ($count == 0) {
            $ussd_text = "Login Failed";
            ussd_stop($ussd_text);
        } else if ($count == 1) {
                    // main_menu($ussd_exploded_string, $conn);
                    $user_id = $row['student_id'];
                    //$newid = md5($user_id);
                    session_start();
                    $_SESSION['user_id'] = $user_id;

                    $sql = "UPDATE students_tbl SET student_session_id = ? WHERE student_id = ?";
                    $stmt = $conn->prepare($sql);
                    $stmt->execute([$_SESSION['user_id'], $user_id]);

                if ($stmt->errorCode() == 0) {
                    $ussd_text = "Welcome user ".$user_id." to USSD School \n1 Exam Results.\n2 My Fees Balance. \n3 Upcoming Events. \n4 Fees Structure.";
                    ussd_proceed($ussd_text);
                } else {
                    $ussd_text = "Error Occured During login. Tray Again later";
                    ussd_proceed($ussd_text);
                }  
        } else {
            $ussd_text = "Contact admin";
            ussd_stop($ussd_text);
        }
    }
}//end

function display_menu(){
    $ussd_text = "St.Anthony School mobile services \n1.Login \n2.Help";
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