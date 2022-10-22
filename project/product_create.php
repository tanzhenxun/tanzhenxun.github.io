<!DOCTYPE HTML>
<html>
<head>
    <title>PDO - Create a Record - PHP CRUD Tutorial</title>
    <!-- Latest compiled and minified Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
</head>
<body>  
    <!-- container -->
    <div class="container">
        <div class="page-header">
            <h1>Create Product</h1>
        </div>
      
        <!-- html form to create product will be here -->
        <!-- PHP insert code will be here -->
        <?php
        if($_POST){
            $name=$_POST['name'];
            $description=$_POST['description'];
            $price=$_POST['price'];
            $promotion_price=$_POST['promotion_price'];
            $manufacture_date=$_POST['manufacture_date'];
            $expired_date = $_POST['expired_date'];

           

            // include database connection
            if($name=="" || $description==""|| $price=="" || $manufacture_date=="" ){
                echo "<div class='alert alert-danger'>Please make sure all fields are not emplty!</div>";
            }else if($promotion_price > $price ){
                echo "<div class='alert alert-danger'>Please correctly your promotion price need cheaper than original price!</div>";
            }else if($manufacture_date <= $expired_date || $expired_date == ""){
                $expired_date = NULL;
                include 'config/database.php';
                try{
                    // insert query
                    $query = "INSERT INTO products SET name=:name, description=:description, price=:price, created=:created, promotion_price=:promotion_price, manufacture_date=:manufacture_date, expired_date=:expired_date" ;
                    // prepare query for execution
                    $stmt = $con->prepare($query);
                    // bind the parameters
                    $stmt->bindParam(':name', $name);
                    $stmt->bindParam(':description', $description);
                    $stmt->bindParam(':price', $price);
                    $created=date('Y-m-d H:i:s'); // get the current date and time
                    $stmt->bindParam(':created', $created);
                    $stmt->bindParam(':promotion_price', $promotion_price);
                    $stmt->bindParam(':manufacture_date', $manufacture_date);
                    $stmt->bindParam(':expired_date', $expired_date);
                    // Execute the query
                    if($stmt->execute()){
                        echo "<div class='alert alert-success'>Record was saved.</div>";
                    }else{
                        echo "<div class='alert alert-danger'>Unable to save record.</div>";
                    } 
                }
                // show error
                catch(PDOException $exception){
                    die('ERROR: ' . $exception->getMessage());
                }
            }else{
                echo "<div class='alert alert-danger'>Your manufacture date no longer than expired date!</div>";
            }
        }

        ?>
        
 
        <!-- html form here where the product information will be entered -->
        <form action="<?php echo $_SERVER["PHP_SELF"];?>" method="POST">
            <table class='table table-hover table-responsive table-bordered'>
                <tr>
                    <td>Name</td>
                    <td><input type='text' name='name' class='form-control' /></td>
                </tr>
                <tr>
                    <td>Description</td>
                    <td><textarea rows="5" cols="33" name='description' class='form-control'></textarea></td>
                </tr>
                <tr>
                    <td>Price</td>
                    <td><input type='text' name='price' class='form-control' /></td>
                </tr>
                <tr>
                    <td>Promotion Price</td>
                    <td><input type='text' name='promotion_price' class='form-control' /></td>
                </tr>
                <tr>
                    <td>Manufacture Date</td>
                    <td><input type='date' name='manufacture_date' class='form-control' /></td>
                </tr>
                <tr>
                    <td>Expired Date</td>
                    <td><input type='date' name='expired_date' class='form-control' /></td>
                </tr>
                <tr>
                    <td></td>
                    <td>
                        <input type='submit' value='Save' class='btn btn-primary' />
                        <a href='index.php' class='btn btn-danger'>Back to read products</a>
                    </td>
                </tr>
            </table>
        </form>
    </div> 
    <!-- end .container -->  
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>
</body>
</html>
