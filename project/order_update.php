<?php
ob_start();
include 'logincheck.php';
?>

<!DOCTYPE HTML>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Latest compiled and minified Bootstrap CSS -->
    <script src="https://kit.fontawesome.com/f9f6f2f33c.js" crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
    <link rel="stylesheet" href="css/index.css">
    <title>Product Update</title>
    <!-- Latest compiled and minified Bootstrap CSS -->

    <!-- custom css -->
    <style>
        .m-r-1em {
            margin-right: 1em;
        }

        .m-b-1em {
            margin-bottom: 1em;
        }

        .m-l-1em {
            margin-left: 1em;
        }

        .mt0 {
            margin-top: 0;
        }
    </style>
</head>

<body>
    <?php
    include 'navtop.php';
    ?>
    <!-- container -->
    <div class="container full_page">
        <div class="page-header py-3">
            <h3>Edit Order</h3>
        </div>

        <?php
        if ($_GET) {
            // get passed parameter value, in this case, the record ID
            // isset() is a PHP function used to verify if a value is there or not
            $order_summary_id = isset($_GET['order_summary_id']) ? $_GET['order_summary_id'] : die('ERROR: Record ID not found.');

            //include database connection
            include 'config/database.php';

            // read current record's data
            try {
                // prepare select query

                $query = "SELECT * FROM order_summary INNER JOIN order_detail ON order_summary.order_summary_id = order_detail.order_summary_id INNER JOIN products ON products.id = order_detail.product_id WHERE order_summary.order_summary_id=:order_summary_id";

                $stmt = $con->prepare($query);

                // this is the first question mark
                $stmt->bindParam(":order_summary_id", $order_summary_id);

                // execute our query
                $stmt->execute();

                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $order_summary_id = $row['order_summary_id'];

                // values to fill up our form
            }

            // show error
            catch (PDOException $exception) {
                die('ERROR: ' . $exception->getMessage());
            }
        }
        ?>

        <?php

        if ($_POST) {
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
            }

            if ($flag == 0) {
                $query_delete_order_detail = "DELETE FROM order_detail where  order_summary_id = ?";
                $stmt_delete_order_detail = $con->prepare($query_delete_order_detail);
                $stmt_delete_order_detail->bindParam(1, $order_summary_id);


                if ($stmt_delete_order_detail->execute()) {
                    $query = "UPDATE order_summary SET customer_id=:customer_id WHERE order_summary_id = :order_summary_id";

                    $stmt = $con->prepare($query);
                    $stmt->bindParam(':order_summary_id', $order_summary_id);
                    $stmt->bindParam(':customer_id', $customer_id);

                    if ($stmt->execute()) {
                        try {
                            for ($loop = 0; $loop < count($_POST['ProductSelect']); $loop++) {
                                if ($_POST["ProductSelect"][$loop] != -1 && $_POST["InputOrderQuantity"][$loop] != 0) {
                                    $product_id = $_POST['ProductSelect'][$loop];
                                    $quantity = $_POST['InputOrderQuantity'][$loop];

                                    // insert query
                                    $query_order_detail = "INSERT INTO order_detail SET order_summary_id=:order_summary_id, product_id=:product_id, quantity=:quantity";
                                    // prepare query for execution
                                    $stmt_order_detail = $con->prepare($query_order_detail);
                                    // bind the parameters
                                    $stmt_order_detail->bindParam(':product_id', $product_id);
                                    $stmt_order_detail->bindParam(':quantity', $quantity);
                                    $stmt_order_detail->bindParam(':order_summary_id', $order_summary_id);
                                    // Execute the query
                                    if ($stmt_order_detail->execute()) {
                                        header('Location:order_read?action=sucessful');
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
                } else {
                    die('Unable to delete record.');
                }
            }
        }
        ?>

        <form action="<?php echo ($_SERVER["PHP_SELF"] . "?order_summary_id={$order_summary_id}"); ?>" method="POST" id="myFrom">
            <div class="mb-3 row">
                <div class="mab-3 col">
                    <label for="customerSelect" class="form-label">Customer</label>
                    <select class="form-select" id="customerSelect" name="customerSelect">
                        <?php

                        include 'config/database.php';

                        // delete message prompt will be here

                        // select all data
                        $query_cus = "SELECT id, username FROM customers ORDER BY id DESC";
                        $stmt_cus = $con->prepare($query_cus);
                        $stmt_cus->execute();

                        // this is how to get number of rows returned
                        $num_cus = $stmt_cus->rowCount();

                        //check if more than 0 record found
                        if ($num_cus > 0) {
                            echo "<option value=-1>--Option--</option>";

                            // retrieve our table contents
                            while ($row_cus = $stmt_cus->fetch(PDO::FETCH_ASSOC)) {
                                // extract row
                                // this will make $row['firstname'] to just $firstname only
                                extract($row_cus);

                                // if the option is selected then auto select it
                                if (isset($_POST["customerSelect"])) {

                                    if ($_POST["customerSelect"] == $id) {
                                        echo "<option value={$id} ";
                                        echo "selected";
                                        echo ">{$username}</option>";
                                    } else {
                                        echo "<option value={$id}> {$username}</option>";
                                    }
                                } else {
                                    $status = "";
                                    if ($id == $row['customer_id']) {
                                        $status = "selected";
                                    } else {
                                        $status = "";
                                    }
                                    echo "<option value={$id} {$status}>{$username}</option>";
                                }
                            }
                        } else {
                            echo "<option value=-1>No records found</option>";
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
                    try {

                        include 'config/database.php';
                        // select all data
                        $query_pro = "SELECT id, name, price FROM products ORDER BY id DESC";
                        $stmt_pro = $con->prepare($query_pro);
                        if (isset($_POST["ProductSelect"])) {
                            $productSelectedNumber = count($_POST["ProductSelect"]);
                        }
                        $index = 0;
                        //for ($pdct_tbl = 1; $pdct_tbl  <= 1; $pdct_tbl ++) {
                        //    $stmt->execute();
                        do {
                            echo "<tr class = \"pRow\">";
                            echo "<th scope=\"row\">";
                            echo $index + 1;
                            echo "</th>";
                            echo "<td>";
                            echo "<div class=\"my-2 col\">";

                            echo "<select class=\"form-select\" id=\"ProductSelect\" name=\"ProductSelect[]\">";
                            //check if more than 0 record found
                            echo "<option value=-1>--Option--</option>";
                            $stmt_pro->execute();

                            // retrieve our table contents
                            while ($row_pro = $stmt_pro->fetch(PDO::FETCH_ASSOC)) {
                                // extract row
                                // this will make $row['firstname'] to just $firstname only
                                extract($row_pro);

                                if (isset($_POST["ProductSelect"])) {
                                    if ($_POST["ProductSelect"][$index] == $id) {
                                        echo "<option value={$id} ";
                                        echo "selected";
                                        echo ">{$name}</option>";
                                    } else {
                                        echo "<option value={$id}> {$name}</option>";
                                    }
                                } else {
                                    $status = "";
                                    if ($id == $row['product_id']) {
                                        $status = "selected";
                                    } else {
                                        $status = "";
                                    }

                                    echo "<option value={$id} {$status}>{$name}</option>";
                                }
                            }


                            echo "</select>";
                            echo "</td>";

                            echo "<td>";
                            echo "<div class=\"my-2 col\">";
                            echo "<input type=\"number\" class=\"form-control\" id=\"InputOrderQuantity\" name=\"InputOrderQuantity[]\" value = ";
                            if (isset($_POST["InputOrderQuantity"])) {
                                echo $_POST["InputOrderQuantity"][$index];
                            } else {
                                echo $row['quantity'];
                            }

                            echo ">";
                            echo "</div>";
                            echo "</td>";

                            echo "</tr>";
                            $index++;
                            $has_next = "";

                            if (isset($_POST["ProductSelect"])) {
                                if ($productSelectedNumber > $index)
                                    $has_next = true;
                            } else {
                                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                                $has_next = $row;
                            }
                        } while ($has_next);
                    }

                    // show error
                    catch (PDOException $exception) {
                        die('ERROR: ' . $exception->getMessage());
                    }
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
    </div>
    <footer class="container-fluid py-3 bg-dark m-100">
        <div class="m-auto foot-size d-sm-flex d-block justify-content-between text-white">
            <div class="text-sm-start text-center">Copyright @ 2022 TANZX</div>
            <div class="d-flex justify-content-evenly">
                <div class="mx-3">Terms of Use</div>
                <div class="mx-3">Privacy Policy</div>
            </div>
        </div>
    </footer>

    <script>
        document.addEventListener('click', function(event) {
            if (event.target.matches('.add_one')) {
                var element = document.querySelector('.pRow');
                var clone = element.cloneNode(true);
                element.after(clone);
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

        function checkDuplicate() {
            var newarray = [];
            var selects = document.getElementsByTagName('select');// can change table.getElementsByTagName('select');
            for (var i = 1; i < selects.length; i++) {// if i =0; then list all value inclued customer and product select, if i = 1, then check second value.
                newarray.push(selects[i].value);
            }

            if (newarray.length !== new Set(newarray).size) {
                alert("There are duplicate items in the array");
            } else {
                document.getElementById("myFrom").submit();
            }
        }
    </script>


    <!-- end .container -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-OERcA2EqjJCMA+/3y+gxIOqMEjwtxJY7qPCqsdltbNJuaOe923+mo//f6V8Qbsw3" crossorigin="anonymous"></script>
</body>

</html>