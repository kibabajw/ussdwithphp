<?php

    header('Content-Type: text/plain');

    $phoneNumber = $_GET['phoneNumber'];
    $sessionId = $_GET['sessionId'];
    $serviceCode = $_GET['serviceCode'];
    $user_response = $_GET['text'];

    //show first menu if user response is empty
    /**
     * http://localhost/ussd-class/app1.php?phoneNumber=0702753197&sessionId=12345678&serviceCode=1234&text=
     */
    if ($user_response == "") {
        display_menu(); //Level 0
    }

    $ussd_exploded_string = explode("*", $user_response);

    //levels
    $level = count($ussd_exploded_string);
    echo "Level is $level \n";
    #level 1
    if($user_response != "" and $level > 0){
        if ($ussd_exploded_string[0] ==1) {
            banks($ussd_exploded_string); //pass $ussd_explode string to banks function
        } else if($ussd_exploded_string[0] == 2) {
            saccos($ussd_exploded_string); //pass $ussd_explode string to sacco function
        } else if($ussd_exploded_string[0] == 3){
            help($ussd_exploded_string); //pass $ussd_explode string to help function
        } else{
            $ussd_text = "Invalid input";
            ussd_proceed($ussd_text);
        }
    }

    //level 2
    

    //============================start level 1=====================
    function display_menu(){
        $ussd_text = "Welcome to Modcom assistance. \n 1. Banks \n 2. Saccos \n 3. Help \n";
        ussd_proceed($ussd_text);
    }

    function banks($ussd_exploded_string){
        if (count($ussd_exploded_string) == 1) {
            $ussd_text = "Welcome to Banks Section. \n 1. Equity \n 2. Barclays \n 3. KCB \n 4. Family Bank \n 5. Corporative bank \n 6. Standard chartered";
            ussd_proceed($ussd_text);
        }
        if(count($ussd_exploded_string) == 2){
            //check which bank user chose
            if ($ussd_exploded_string[1] == 1) {
                $ussd_text = "Welcome to Equity bank. \n 1. Car loan \n 2. Land loan \n 3. Mortgage";
                ussd_proceed($ussd_text);
            } else if($ussd_exploded_string[1] == 2){
                $ussd_text = "Welcome to Barclays bank. \n 1. Car loan \n 2. Land loan \n 3. Mortgage";
                ussd_proceed($ussd_text);
            } else if($ussd_exploded_string[1] == 3){
                $ussd_text = "Welcome to KCB bank. \n 1. Car loan \n 2. Land loan \n 3. Mortgage";
                ussd_proceed($ussd_text);
            } else if($ussd_exploded_string[1] == 4){
                $ussd_text = "Welcome to Family bank. \n 1. Car loan \n 2. Land loan \n 3. Mortgage";
                ussd_proceed($ussd_text);
            } else if($ussd_exploded_string[1] == 5){
                $ussd_text = "Welcome to Corporative bank bank. \n 1. Car loan \n 2. Land loan \n 3. Mortgage";
                ussd_proceed($ussd_text);
            } else if($ussd_exploded_string[1] == 6){
                $ussd_text = "Welcome to Standard chartered bank. \n 1. Car loan \n 2. Land loan \n 3. Mortgage";
                ussd_proceed($ussd_text);
            } else{
                $ussd_text = "Invalid input";
                ussd_proceed($ussd_text);
            }
        }
    }

    function saccos($ussd_exploded_string){
        if (count($ussd_exploded_string) == 1) {
            $ussd_text = "Welcome to Saccos Section. \n 1. Ukulima Sacco \n 2. Jamii Sacco \n 3. Daktari sacco \n 4. Police Sacco";
            ussd_proceed($ussd_text);
        }
        if (count($ussd_exploded_string) == 2) {
             //check which sacco user chose
            if ($ussd_exploded_string[1] == 1) {
                $ussd_text = "Welcome to Ukulima Sacco. \n 1. Car loan \n 2. Land loan \n 3. Mortgage";
                ussd_proceed($ussd_text);
            } else if($ussd_exploded_string[1] == 2){
                $ussd_text = "Welcome to Jamii sacco. \n 1. Car loan \n 2. Land loan \n 3. Mortgage";
                ussd_proceed($ussd_text);
            } else if($ussd_exploded_string[1] == 3){
                $ussd_text = "Welcome to Daktari sacco. \n 1. Car loan \n 2. Land loan \n 3. Mortgage";
                ussd_proceed($ussd_text);
            } else if($ussd_exploded_string[1] == 4){
                $ussd_text = "Welcome to Police sacco. \n 1. Car loan \n 2. Land loan \n 3. Mortgage";
                ussd_proceed($ussd_text);
            }else{
                $ussd_text = "Invalid input";
                ussd_proceed($ussd_text);
            }
        }
    } //end sacco
    
    function help($ussd_exploded_string){
        $ussd_text = "For help call 254 702 753 197";
        ussd_stop($ussd_text);
    }
    //==========end level 1 functions=========================================

    #proceed and stop
    function ussd_proceed($ussd_text){
        echo "CON $ussd_text";
    }

    function ussd_stop($ussd_text){
        echo "END $ussd_text";
    }

?>
