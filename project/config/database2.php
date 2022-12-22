<?php
// used to connect to the database
$host = "sql109.epizy.com";
$db_name = "epiz_33245135_eshop";
$db_username = "epiz_33245135";
$db_password = "39HrCYctQth";
  
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

