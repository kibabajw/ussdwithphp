<?php
   $dsn = 'mysql:dbname=ussd_db; host=localhost;';
   $user = 'root';
   $password = '';

try{
   $conn = new PDO($dsn, $user, $password);
}
catch(PDOException $e) {
echo "END Contact Admin";
}

$sql = "SELECT * FROM user_sessions WHERE session_body = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute(['1']);
        $row = $stmt->fetch();

        $count = $stmt->rowCount();
// $sql = "SELECT * FROM users_sessions WHERE phoneNumber = ?";
// $stmt = $conn->prepare($sql);
// $stmt->execute(['725455']);
// $row = $stmt->fetch();
// $count = $stmt->rowCount();
echo $count;
// echo $phoneNumber;
if ($count == 0) {
    // $ussd_text = "Session Expired^";
    // ussd_stop($ussd_text);
} 