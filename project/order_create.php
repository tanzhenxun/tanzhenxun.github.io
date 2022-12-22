<?php
ob_start();
include 'logincheck.php';
?>

<!DOCTYPE HTML>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>PDO - Create a Record - PHP CRUD Tutorial</title>
    <!-- Latest compiled and minified Bootstrap CSS -->
    <script src="https://kit.fontawesome.com/f9f6f2f33c.js" crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
    <link rel="stylesheet" href="css/index.css">
</head>

<body>
    <!-- container -->
    <?php 
   include 'navtop.php';
   ?>
    <!-- container -->
    <div class="container full_page">
        <div class="page-header">
            <h1>Create Order</h1>
        </div>
        <?php

        if ($_POST) {
            include 'config/database.php';
            $customer_id = $_POST['customerSelect'];
            $flag = 0;
            if ($customer_id == -1) {
                echo "<div class='alert alert-danger'>Please make sure all fields are not emplty!</div>";
                $flag = 1;
            }
            for ($check = 0; $check < count($_POST['ProductSelect']); $check++) {
                if ($_POST['ProductSelect'][$check] == -1) {
                    echo "<div class='alert alert-danger'>Please make sure your product are not emplty!</div>";
                    $flag = 1;
                }
                if ($_POST["InputOrderQuantity"][$check] == 0) {
                    echo "<div class='alert alert-danger'>Please make sure your quantity are not emplty!</div>";
                    $flag = 1;
                }
            }

            if ($flag == 0) {

                $order_id = 0;

                if ($flag == 0) {
                    $query = "INSERT INTO order_summary SET customer_id=:customer_id, order_date=:order_date";

                    $stmt = $con->prepare($query);

                    $stmt->bindParam(':customer_id', $customer_id);
                    $order_date = date('Y-m-d H:i:s');
                    $stmt->bindParam(':order_date', $order_date);

                    if ($stmt->execute()) {      
                        $query_summary = "SELECT MAX(order_summary_id) from order_summary";
                        $stmt_summary = $con->prepare($query_summary);
                        $stmt_summary->execute();
                        $num = $stmt_summary->rowCount();
    
                        if ($num > 0) {
                            $row = $stmt_summary->fetch(PDO::FETCH_ASSOC);
                            $order_id = $row['MAX(order_summary_id)'];
                        }
                        try {

                            for ($loop = 0; $loop < count($_POST['ProductSelect']); $loop++) {
                                if ($_POST["ProductSelect"][$loop] != -1) {
                                    $product_id = $_POST['ProductSelect'][$loop];
                                    $quantity = $_POST['InputOrderQuantity'][$loop];

                                    // insert query
                                    $query_order_detail = "INSERT INTO order_detail SET order_summary_id=:order_summary_id, product_id=:product_id, quantity=:quantity";
                                    // prepare query for execution
                                    $stmt_order_detail = $con->prepare($query_order_detail);
                                    // bind the parameters
                                    $stmt_order_detail->bindParam(':product_id', $product_id);
                                    $stmt_order_detail->bindParam(':quantity', $quantity);
                                    $stmt_order_detail->bindParam(':order_summary_id', $order_id);
                                    // Execute the query
                                    if ($stmt_order_detail->execute()) {
                                        header('Location: order_read_one.php?order_summary_id='.$order_id.'&& action=sucessful');
                                    } else {
                                        echo "<div class='alert alert-danger'>Unable to save product.</div>";
                                    }
                                }
                            }
                        }
                        // show error
                        catch (PDOException $exception) {
                            die('ERROR: ' . $exception->getMessage());
                        }
                    } else {
                        echo "<div class='alert alert-danger'>Unable to save record by total price.</div>";
                    }
                }
            }
        }
        ?>
        <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="POST" id="myFrom">
            <div class="mb-3 row">
                <div class="mab-3 col">
                    <label for="customerSelect" class="form-label">Customer</label>
                    <select class="form-select" id="customerSelect" name="customerSelect">
                        <?php

                        include 'config/database.php';

                        // delete message prompt will be here

                        // select all data
                        $query = "SELECT id, username FROM customers ORDER BY id DESC";
                        $stmt = $con->prepare($query);
                        $stmt->execute();

                        // this is how to get number of rows returned
                        $num = $stmt->rowCount();

                        //check if more than 0 record found
                        if ($num > 0) {
                            echo "<option selected value=-1>--Option--</option>";

                            // retrieve our table contents
                            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                // extract row
                                // this will make $row['firstname'] to just $firstname only
                                extract($row);

                                echo "<option value={$id} ";

                                // if the option is selected then auto select it
                                if (isset($_POST["customerSelect"]) && $_POST["customerSelect"] == $id) {
                                    echo "selected";
                                }
    
                                echo ">{$username}</option>";

                                
                            }
                        } else {
                            echo "<option selected value=-1>No records found</option>";
                        }

                        
                        ?>
                    </select>
                </div>
            </div>
            <table class="table" id="order">
                <thead>
                    <tr>
                        <th scope="col">No</th>
                        <th scope="col">Product name</th>
                        <th scope="col">Quantity</th>
                    </tr>
                </thead>
                <tbody>
                    <?php

                    include 'config/database.php';
                    // select all data
                    $query = "SELECT id, name, price FROM products ORDER BY id DESC";
                    $stmt = $con->prepare($query);
                    
                    $count = 0;
                    $productSelectedCount = 0;

                    if (isset($_POST["productSelect"])) {
                        $productSelectedCount = count($_POST['productSelect']);
                    }
                    do{
                    $stmt->execute();
                    $num = $stmt->rowCount();

                    //for ($pdct_tbl = 1; $pdct_tbl  <= 1; $pdct_tbl ++) {
                    //    $stmt->execute();
                    echo "<tr class = \"pRow\">";
                    echo "<th scope=\"row\">";
                        echo ($count +1);
                        echo "</th>";
                    echo "<td>";
                    echo "<div class=\"my-2 col\">";

                    echo "<select class=\"form-select\" id=\"ProductSelect\" name=\"ProductSelect[]\">";
                    //check if more than 0 record found
                    if ($num > 0) {
                        echo "<option selected value=-1>--Option--</option>";

                        // retrieve our table contents
                        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                            // extract row
                            // this will make $row['firstname'] to just $firstname only
                            extract($row);
                            
                            echo "<option value={$id} ";

                                // if the option is selected then auto select it
                                if (isset($_POST["ProductSelect"]) && $_POST["ProductSelect"][$count] == $id) {
                                    echo "selected";
                                }
                                echo ">{$name}</option>";
                        }
                    } else {
                        echo "<option selected value=-1>No records found</option>";
                    }

                    echo "</select>";
                    echo "</td>";

                    echo "<td>";
                    echo "<div class=\"my-2 col\">";
                    echo "<input type=\"number\" class=\"form-control\" id=\"InputOrderQuantity\" name=\"InputOrderQuantity[]\" value=\"";
                    if (isset($_POST["InputOrderQuantity"])) {
                        echo $_POST["InputOrderQuantity"][$count];
                    }
                    echo "\">";
                    echo "</div>";
                    echo "</td>";

                    echo "</tr>";
                    $count++;
                } while ($productSelectedCount > $count);
                    //}
                    ?>
                </tbody>
            </table>
            <div class="row text-center d-flex justify-content-between">
                <div class="d-flex row col-6">
                    <input type="button" value="Add More Product" class="add_one btn btn-outline-primary mb-3 col-3 mx-2" />
                    <input type="button" value="Delete" class="delete_one btn btn-outline-danger mb-3 col-2 mx-2" />
                </div>
                <button type="button" onclick="checkDuplicate()" class="btn btn-secondary mb-3 col-3">Submit</button>
            </div>
        </form>
    </div> <!-- end .container -->
    <footer class="container-fluid py-3 bg-dark">
        <div class="m-auto foot-size d-sm-flex d-block justify-content-between text-white">
            <div class="text-sm-start text-center">Copyright @ 2022 TANZX</div>
            <div class="d-flex justify-content-evenly">
                <div class="mx-3">Terms of Use</div>
                <div class="mx-3">Privacy Policy</div>
            </div>
        </div>
    </footer>
    <!-- confirm delete record will be here -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-OERcA2EqjJCMA+/3y+gxIOqMEjwtxJY7qPCqsdltbNJuaOe923+mo//f6V8Qbsw3" crossorigin="anonymous"></script>
    <script>
        document.addEventListener('click', function(event) {
            if (event.target.matches('.add_one')) {
                var table = document.querySelectorAll('.pRow');
                var rowCount = table.length;
                var clone = table[rowCount - 1].cloneNode(true);
                table[rowCount - 1].after(clone);
            }
            if (event.target.matches('.delete_one')) {
                var total = document.querySelectorAll('.pRow').length;
                if (total > 1) {
                    var element = document.querySelector('.pRow');
                    element.remove(element);
                }
            }
            var total = document.querySelectorAll('.pRow').length;

            var row = document.getElementById('order').rows;
            for (var i = 1; i <= total; i++) {
                row[i].cells[0].innerHTML = i;

            }
        }, false);

        function checkDuplicate(){
            var newarray = [];
            var selects = document.getElementsByTagName('select');
            for(var i = 0; i<selects.length; i++){
                newarray.push(selects[i].value);
            }

            if(newarray.length !== new Set(newarray).size){
                alert("There are duplicate items in the array");
            }else{
                document.getElementById("myFrom").submit();
            }
        }
    </script>
</body>

</html>