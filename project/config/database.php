<?php
// used to connect to the database
$host = "localhost";
$db_name = "tanzhenxun";
$db_username = "tanzhenxun";
$db_password = "CB5IlUaIKf)2UJkB";
  
try {
    $con = new PDO("mysql:host={$host};dbname={$db_name}", $db_username, $db_password);
    $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // show error
    //echo "Connected successfully"; 
}  
// show error
catch(PDOException $exception){
    echo "Connection error: ".$exception->getMessage();
}
?>

