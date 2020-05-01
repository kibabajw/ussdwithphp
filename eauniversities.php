<?php

    header('Content-Type: text/plain');

    $phonenumber = $_POST['phoneNumber'];
    $sessionid = $_POST['sessionId'];
    $servicecode = $_POST['serviceCode'];
    $userresponse = $_POST['text'];

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
                    kenya($ussd_exploded_text);
                    break;
                case 2:
                    uganda($ussd_exploded_text);
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


    function kenya($exp_text){
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
                        $ussd_text = "Bachelors in Medicine.\nThe cost and duration for Bachelors in Medicine is as follows.\n 1. 150,000 Kshs 1st year.\n 2. 100,050 Kshs 2nd year.\n 3. 101,000 Kshs 3rd year.\n 4. 90,050 Kshs 4th year.\n 5. 100,050 Kshs 5th year.\n 6. 85,050 Kshs final year.\nThe duration for the course is 6 academic years.";
                        ussd_stop($ussd_text);
                        break;
                    case 2:
                        $ussd_text = "Bachelors in Computer science.\nThe cost and duration for Bachelors in Computer science. is as follows.\n 1. 105,000 Kshs for 1st year.\n 2. 98,000 kshs for 2nd year.\n 3. 100,000 kshs final year.\nThe duration for the course is 3 years.";
                        ussd_stop($ussd_text);
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
                        $ussd_text = "Bachelors in Acturial science.\nThe cost and duration for Bachelors in Acturial science is as follows.\n 1. 90,000 Kshs 1st year.\n 2. 70,050 Kshs 2nd year.\n 3. 65,000 Kshs 3rd year.\nThe duration for the course is 3 academic years.";
                        ussd_stop($ussd_text);
                        break;
                    case 2:
                        $ussd_text = "Bachelors in Aeronautical engineering.\nThe cost and duration for Bachelors in Aeronautical engineering. is as follows.\n 1. 75,000 Kshs for 1st year.\n 2. 58,000 kshs for 2nd year.\n 3. 46,000 kshs final year.\nThe duration for the course is 3 years.";
                        ussd_stop($ussd_text);
                        break;
                    default:
                        $ussd_text = "Invalid input, please refer from the options given.";
                        ussd_stop($ussd_text);
                        break;
                }   
            }
        }
    }

    function uganda($exp_text){
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
                        $ussd_text = "Bachelors in Education Arts.\nThe cost and duration for Bachelors in Education Arts is as follows.\n 1. 56,100 Ugshs 1st year.\n 2. 60,150 Ugshs 2nd year.\n 3. 61,000 Ugshs 3rd year.\nThe duration for the course is 3 academic years.";
                        ussd_stop($ussd_text);
                        break;
                    case 2:
                        $ussd_text = "Bachelors in civil engineering.\nThe cost and duration for Bachelors in civil engineering. is as follows.\n 1. 95,000 Ugshs for 1st year.\n 2. 68,000 Ugshs for 2nd year.\n 3. 70,000 Ugshs final year.\nThe duration for the course is 3 years.";
                        ussd_stop($ussd_text);
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
                        $ussd_text = "Bachelors in marine science.\nThe cost and duration for Bachelors in marine science is as follows.\n 1. 90,000 Ugshs 1st year.\n 2. 70,050 Ugshs 2nd year.\n 3. 65,000 Ugshs 3rd year.\nThe duration for the course is 3 academic years.";
                        ussd_stop($ussd_text);
                        break;
                    case 2:
                        $ussd_text = "Bachelors in laws.\nThe cost and duration for Bachelors in laws. is as follows.\n 1. 75,000 Ugshs for 1st year.\n 2. 58,000 Ugshs for 2nd year.\n 3. 46,000 Ugshs for 3rd year.\n 4. 40,000 Ugshs for 4th year. \nThe duration for the course is 3 years.";
                        ussd_stop($ussd_text);
                        break;
                    default:
                        $ussd_text = "Invalid input, please refer from the options given.";
                        ussd_stop($ussd_text);
                        break;
                } 
            }
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