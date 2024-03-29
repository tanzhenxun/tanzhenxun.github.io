<?php
// include database connection
include 'config/database.php';
try {     
    // get record ID
    // isset() is a PHP function used to verify if a value is there or not
    $order_summary_id=isset($_GET['order_summary_id']) ? $_GET['order_summary_id'] :  die('ERROR: Record ID not found.');

    // delete query
    $query = "DELETE order_summary, order_detail FROM order_summary Inner join order_detail On order_detail.order_summary_id = order_summary.order_summary_id WHERE order_detail.order_summary_id =?";
    
    $stmt = $con->prepare($query);
    $stmt->bindParam(1, $order_summary_id);
     
    if($stmt->execute()){
        // redirect to read records page and
        // tell the user record was deleted
        header('Location: order_read.php?action=deleted');
    }else{
        die('Unable to delete record.');
    }
}
// show error
catch(PDOException $exception){
    die('ERROR: ' . $exception->getMessage());
}
?>