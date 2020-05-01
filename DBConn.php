<?php
$servername = "localhost";
$username = "root";
$password = "";

    try {
        $conn = new PDO("mysql:host=$servername;dbname=euniversities", $username, $password);
        // set the PDO error mode to exception
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch(PDOException $e) {
        $ussd_text = "System error, try again later.";
        ussd_stop($ussd_text);
    }
?> 