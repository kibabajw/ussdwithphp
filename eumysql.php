<?php

    header('Content-Type: text/plain');
    
    //========start db logic
    $host = "localhost";
    $username = "root";
    $password = "";

    try {
        $conn = new PDO("mysql:host=$host;dbname=euniversities", $username, $password);
        // set the PDO error mode to exception
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch(PDOException $e) {
        $ussd_text = "System error, try again later.";
        ussd_stop($ussd_text);
    }
    //========end db logic


    $phonenumber = $_GET['phoneNumber'];
    $sessionid = $_GET['sessionId'];
    $servicecode = $_GET['serviceCode'];
    $userresponse = $_GET['text'];

    //global variable to store chosen course
    $chosen_course = '';
    $user_name = '';

    //display level 0 menu if user response is empty
    if ($userresponse == "") {
        display_menu();
    } else{
        //explode user text if it's available
        $ussd_exploded_text = explode("*", $userresponse);
        //count levels
        $level = count($ussd_exploded_text);
        if ($userresponse != "" && $level > 0) {
            switch ($ussd_exploded_text[0]) {
                case 1:
                    //pass $ussd_exploded_text to function Kenya
                    kenya($ussd_exploded_text, $phonenumber, $conn);
                    break;
                case 2:
                    uganda($ussd_exploded_text, $phonenumber, $conn);
                    break;
                case 3:
                    help($ussd_exploded_text);
                    break;
                default:
                    $ussd_text = "Invalid input, please refer from the options given.";
                    ussd_proceed($ussd_text);
                    break;
            }
        }

    }


    function kenya($exp_text, $phonenumber, $conn){
        if (count($exp_text) == 1) {
            $ussd_text = "Welcome to Kenya, Select One. \n 1. Nairobi University. \n 2. Kenyatta University.";
            ussd_proceed($ussd_text);
        }
        if (count($exp_text) == 2) {
            //check which university has been chosen and redirect appropriety;
            switch ($exp_text[1]) {
                case 1:
                    $ussd_text = "Nairobi University offers the following programs.\n 1. Bachelors in Medicine.\n 2. Bachelors in Computer science.\n";
                    ussd_proceed($ussd_text);
                    break;
                case 2:
                    $ussd_text = "Kenyatta University offers the following programs.\n 1. Bachelors in Acturial science.\n 2. Bachelors in Aeronautical engineering.";
                    ussd_proceed($ussd_text);
                    break;
                default:
                    $ussd_text = "Invalid input, please refer from the options given.";
                    ussd_proceed($ussd_text);
                    break;
            }
        }
        //Nairobi University
        if (count($exp_text) == 3) {
            if ($exp_text[1] == 1) {
                switch ($exp_text[2]) {
                    case 1:
                        $ussd_text = "Bachelors in Medicine.\nThe cost and duration for Bachelors in Medicine is as follows.\n 1. 150,000 Kshs 1st year.\n 2. 100,050 Kshs 2nd year.\n 3. 101,000 Kshs 3rd year.\n 4. 90,050 Kshs 4th year.\n 5. 100,050 Kshs 5th year.\n 6. 85,050 Kshs final year.\nThe duration for the course is 6 academic years.\n\n Press 1 to apply for this course.";
                        $chosen_course = 'Bachelors in Medicine';
                        ussd_proceed($ussd_text);
                        break;
                    case 2:
                        $ussd_text = "Bachelors in Computer science.\nThe cost and duration for Bachelors in Computer science. is as follows.\n 1. 105,000 Kshs for 1st year.\n 2. 98,000 kshs for 2nd year.\n 3. 100,000 kshs final year.\nThe duration for the course is 3 years.\n\n Press 1 to apply for this course.";
                        $chosen_course = 'Bachelors in Computer science';
                        ussd_proceed($ussd_text);
                        break;
                    default:
                        $ussd_text = "Invalid input, please refer from the options given.";
                        ussd_stop($ussd_text);
                        break;
                }   
            }
        }
        //Kenyatta University
        if (count($exp_text) == 3) {
            if ($exp_text[1] == 2) {
                switch ($exp_text[2]) {
                    case 1:
                        $ussd_text = "Bachelors in Acturial science.\nThe cost and duration for Bachelors in Acturial science is as follows.\n 1. 90,000 Kshs 1st year.\n 2. 70,050 Kshs 2nd year.\n 3. 65,000 Kshs 3rd year.\nThe duration for the course is 3 academic years.\n\n Press 1 to apply for this course.";
                        ussd_proceed($ussd_text);
                        break;
                    case 2:
                        $ussd_text = "Bachelors in Aeronautical engineering.\nThe cost and duration for Bachelors in Aeronautical engineering. is as follows.\n 1. 75,000 Kshs for 1st year.\n 2. 58,000 kshs for 2nd year.\n 3. 46,000 kshs final year.\nThe duration for the course is 3 years.\n\n Press 1 to apply for this course.";
                        ussd_proceed($ussd_text);
                        break;
                    default:
                        $ussd_text = "Invalid input, please refer from the options given.";
                        ussd_stop($ussd_text);
                        break;
                }   
            }
        }
        //check if user wants to register a course
        if (count($exp_text) == 4) {
            $ussd_text = "Enter your full name.";
            ussd_proceed($ussd_text);
        }
        if (count($exp_text) == 5) {
            if ($exp_text[1] == 1) {

                //FOR NAIROBI UNI
                $user_state = array(
                    'lv_one' => $exp_text[0], //country
                    'lv_two' => $exp_text[1], //courses
                    'lv_three' => $exp_text[2], //course details
                    'lv_four' => $exp_text[4] //user's name
                );

                $user_name = $user_state['lv_four'];

                if($user_state['lv_three'] == 1){
                    //Bachelors in Medicine
                    $chosen_course = "Bachelors in Medicine";
                } else if($user_state['lv_three'] == 2){
                    //Bachelors in Computer science
                    $chosen_course = "Bachelors in Computer science";
                }

                register($chosen_course, $phonenumber, $conn, $user_name);
                
            } else if ($exp_text[1] == 2) {
                //FOR KENYATTA UNI
                $user_state = array(
                    'lv_one' => $exp_text[0], //country
                    'lv_two' => $exp_text[1], //courses
                    'lv_three' => $exp_text[2], //course details
                    'lv_four' => $exp_text[4] //user's name
                );

                $user_name = $user_state['lv_four'];

                if($user_state['lv_three'] == 1){
                    //Bachelors in Acturial science
                    $chosen_course = "Bachelors in Acturial science";
                } else if($user_state['lv_three'] == 2){
                    //Bachelors in Aeronautical engineering
                    $chosen_course = "Bachelors in Aeronautical engineering";
                }
                
                register($chosen_course, $phonenumber, $conn, $user_name);

            }
        }
    }

    function uganda($exp_text, $phonenumber, $conn){
        if (count($exp_text) == 1) {
            $ussd_text = "Welcome to Uganda, Select One. \n 1. Makerere University. \n 2. Mbarara University";
            ussd_proceed($ussd_text);
        }
        if (count($exp_text) == 2) {
            switch ($exp_text[1]) {
                case 1:
                    $ussd_text = "Makerere University offers the following programs.\n 1. Bachelors in Education Arts.\n 2. Bachelors in civil engineering.";
                    ussd_proceed($ussd_text);
                    break;
                case 2:
                    $ussd_text ="Mbarara University offers the following programs.\n 1. Bachelors in marine science.\n 2. Bachelors in laws";
                    ussd_proceed($ussd_text);
                    break;
                default:
                    $ussd_text = "Invalid input, please refer from the options given.";
                    ussd_proceed($ussd_text);
                    break;
            }
        }
        // Makerere University
        if (count($exp_text) == 3) {
            if ($exp_text[1] == 1) {
                switch ($exp_text[2]) {
                    case 1:
                        $ussd_text = "Bachelors in Education Arts.\nThe cost and duration for Bachelors in Education Arts is as follows.\n 1. 56,100 Ugshs 1st year.\n 2. 60,150 Ugshs 2nd year.\n 3. 61,000 Ugshs 3rd year.\nThe duration for the course is 3 academic years.\n\n Press 1 to apply for this course.";
                        $chosen_course = 'Bachelors in Education Arts';
                        ussd_proceed($ussd_text);
                        break;
                    case 2:
                        $ussd_text = "Bachelors in civil engineering.\nThe cost and duration for Bachelors in civil engineering. is as follows.\n 1. 95,000 Ugshs for 1st year.\n 2. 68,000 Ugshs for 2nd year.\n 3. 70,000 Ugshs final year.\nThe duration for the course is 3 years.\n\n Press 1 to apply for this course.";
                        $chosen_course = 'Bachelors in civil engineering';
                        ussd_proceed($ussd_text);
                        break;
                    default:
                        $ussd_text = "Invalid input, please refer from the options given.";
                        ussd_stop($ussd_text);
                        break;
                } 
            }
        }
        //Mbarara University
        if (count($exp_text) == 3) {
            if ($exp_text[1] == 2) {
                switch ($exp_text[2]) {
                    case 1:
                        $ussd_text = "Bachelors in marine science.\nThe cost and duration for Bachelors in marine science is as follows.\n 1. 90,000 Ugshs 1st year.\n 2. 70,050 Ugshs 2nd year.\n 3. 65,000 Ugshs 3rd year.\nThe duration for the course is 3 academic years.\n\n Press 1 to apply for this course.";
                        $chosen_course = 'Bachelors in marine science';
                        ussd_proceed($ussd_text);
                        break;
                    case 2:
                        $ussd_text = "Bachelors in laws.\nThe cost and duration for Bachelors in laws. is as follows.\n 1. 75,000 Ugshs for 1st year.\n 2. 58,000 Ugshs for 2nd year.\n 3. 46,000 Ugshs for 3rd year.\n 4. 40,000 Ugshs for 4th year. \nThe duration for the course is 3 years.\n\n Press 1 to apply for this course.";
                        $chosen_course = 'Bachelors in laws';
                        ussd_proceed($ussd_text);
                        break;
                    default:
                        $ussd_text = "Invalid input, please refer from the options given.";
                        ussd_stop($ussd_text);
                        break;
                } 
            }
        }
         //check if user wants to register a course
         if (count($exp_text) == 4) {
            $ussd_text = "Enter your full name.";
            ussd_proceed($ussd_text);
        }
        if (count($exp_text) == 5) {
            if ($exp_text[1] == 1) {

                // Makerere University
                $user_state = array(
                    'lv_one' => $exp_text[0], //country
                    'lv_two' => $exp_text[1], //courses
                    'lv_three' => $exp_text[2], //course details
                    'lv_four' => $exp_text[4] //user's name
                );

                $user_name = $user_state['lv_four'];

                if($user_state['lv_three'] == 1){
                    //Bachelors in Education Arts
                    $chosen_course = "Bachelors in Education Arts";
                } else if($user_state['lv_three'] == 2){
                    //Bachelors in civil engineering
                    $chosen_course = "Bachelors in civil engineering";
                }

                register($chosen_course, $phonenumber, $conn, $user_name);
                
            } else if ($exp_text[1] == 2) {
                //Mbarara University
                $user_state = array(
                    'lv_one' => $exp_text[0], //country
                    'lv_two' => $exp_text[1], //courses
                    'lv_three' => $exp_text[2], //course details
                    'lv_four' => $exp_text[4] //user's name
                );

                $user_name = $user_state['lv_four'];

                if($user_state['lv_three'] == 1){
                    //Bachelors in marine science
                    $chosen_course = "Bachelors in marine science";
                } else if($user_state['lv_three'] == 2){
                    //Bachelors in laws
                    $chosen_course = "Bachelors in laws";
                }
                
                register($chosen_course, $phonenumber, $conn, $user_name);

            }
        }
    }

    function register($chosen_course, $phonenumber, $conn, $user_name){
        $stmt = $conn->prepare("INSERT INTO `student_registration_tbl`(`student_name`, `student_phone_number`, `student_applied_course`) VALUES (:student_name, :student_phone_number, :student_applied_course)");
        $stmt->bindParam(':student_name', $user_name);
        $stmt->bindParam(':student_phone_number', $phonenumber);
        $stmt->bindParam(':student_applied_course', $chosen_course);

        if($stmt->execute()){
            $ussd_text = "Thank you for your application we will get back to you as soon as possible.";
            ussd_stop($ussd_text);
        } else{
            $ussd_text = "We experienced an error, try again.";
            ussd_proceed($ussd_text);
        }
    }

    function help($exp_text){
        $ussd_text = "For further assistance please dial any of the numbers below. \n 254 702 753 197 \n 256 702 753 197 \n 0002 753 197 \n";
        ussd_stop($ussd_text);
    }
    
    //level 0 menu
    function display_menu(){
        $ussd_text = "Welcome to AFRIKA-UNI-E- EA. \nSelect a country. \n 1. Kenya \n 2. Uganda \n 3. Help";
        ussd_proceed($ussd_text);
    }


    //function to proceed with application
    function ussd_proceed($ussd_text){
        echo "CON $ussd_text";
    }
    //function to end the application
    function ussd_stop($ussd_text){
        echo "END $ussd_text";
    }

?>