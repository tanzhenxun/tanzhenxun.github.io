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
    <nav class="navbar navbar-expand-lg navbar border-dark border-bottom border-2">
        <div class="container-fluid m-auto nav-size align-items-center justify-content-between">
            <div class="d-flex align-items-center ">
                <a href="home.php"><img src="images/tanzxlogo.png" alt="Tanzx Logo" class="logo al"></a>
                <a class="navbar-brand fw-bold fs-5" href="home.php">TANZX</a>
            </div>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-between align-items-center" id="navbarNavAltMarkup">
                <hr>
                <div class="navbar-nav text-black text-lg-center text-start">
                    <a class="nav-link active" aria-current="page" href="#">Home</a>
                    <a class="nav-link" href="product_create.php">Create Product</a>
                    <a class="nav-link" href="customer_create.php">Create Customer</a>
                    <a class="nav-link" href="#">Create Order</a>
                    <a class="nav-link" href="contact.php">Contact Us</a>
                </div>
                <div class="navbar-brand">
                    <a href="https://github.com/tanzhenxun" class=" text-dark"><i class="fa-brands fa-github fa-2x"></i></a>
                    <a href="https://www.instagram.com/tan315_x18/?hl=en" class=" text-dark"><i class="fa-brands fa-instagram fa-2x"></i></a>
                </div>
            </div>
        </div>
    </nav>
    <!-- container -->
    <div class="container">
        <div class="page-header">
            <h1>Create Order</h1>
        </div>
        <?php

        if ($_POST) {
            include 'config/database.php';
            $customer_id = $_POST['customerSelect'];
            $flag = 0;
            if ($customer_id == "NULL") {
                echo "<div class='alert alert-danger'>Please make sure all fields are not emplty!</div>";
                $flag = 1;
            } else {
                $order_id = 0;

                if ($flag == 0) {

                    $query_summary = "SELECT MAX(order_summary_id) from order_summary";
                    $stmt_summary = $con->prepare($query_summary);
                    $stmt_summary->execute();
                    $num = $stmt_summary->rowCount();

                    if ($num > 0) {
                        $row = $stmt_summary->fetch(PDO::FETCH_ASSOC);
                        $order_id = $row['MAX(order_summary_id)'];
                    }
                    $order_id++;

                    $query = "INSERT INTO order_summary SET customer_id=:customer_id, order_date=:order_date";

                    $stmt = $con->prepare($query);

                    $stmt->bindParam(':customer_id', $customer_id);
                    $order_date = date('Y-m-d H:i:s');
                    $stmt->bindParam(':order_date', $order_date);

                    if ($stmt->execute()) {

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
                                        $flag = 0;
                                    } else {
                                        echo "<div class='alert alert-danger'>Unable to save product.</div>";
                                    }
                                }
                            }
                            if ($flag == 0) {
                                echo "<div class='alert alert-success'>Product was saved.</div>";
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
        <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="POST">
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

                                echo "<option value={$id}>{$username}</option>";
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
                    $stmt->execute();

                    $num = $stmt->rowCount();

                    //for ($pdct_tbl = 1; $pdct_tbl  <= 1; $pdct_tbl ++) {
                    //    $stmt->execute();
                        echo "<tr class = \"pRow\">";
                        echo "<th scope=\"row\">1</th>";
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

                                echo "<option value={$id}>{$name}</option>";
                            }
                        } else {
                            echo "<option selected value=-1>No records found</option>";
                        }

                        echo "</select>";
                        echo "</td>";

                        echo "<td>";
                        echo "<div class=\"my-2 col\">";
                        echo "<input type=\"number\" class=\"form-control\" id=\"InputOrderQuantity\" name=\"InputOrderQuantity[]\">";
                        echo "</div>";
                        echo "</td>";

                        echo "</tr>";
                    //}
                    ?>
                </tbody>
            </table>
            <div class="row text-center d-flex justify-content-between">
                <div class="d-flex row col-6">
                <input type="button" value="Add More Product" class="add_one btn btn-outline-primary mb-3 col-3 mx-2" />
                <input type="button" value="Delete" class="delete_one btn btn-outline-danger mb-3 col-2 mx-2" />
                </div>
                <button type="submit" class="btn btn-secondary mb-3 col-3">Submit</button>
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
    </script>
</body>

</html>