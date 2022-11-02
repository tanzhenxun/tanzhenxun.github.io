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
            $array_postValues = array();
            include 'config/database.php';
            try {
                $total_amount = 0;
                $order_id = $_POST['inputOrderNumber'];
                $customer_id = $_POST['customerSelect'];

                if ($_POST["firstProductSelect"] != -1) {
                    $product_id_one =  explode("-", $_POST['firstProductSelect'])[0];
                    $price = explode("-", $_POST['firstProductSelect'])[1];
                    $quantity_one = $_POST['firstInputOrderQuantity'];
                    $total_amount += $price * $quantity_one;

                    // insert query
                    $query_order_detail = "INSERT INTO order_detail SET order_summary_id=:order_id, product_id=:product_id, quantity=:quantity";
                    // prepare query for execution
                    $stmt_order_detail = $con->prepare($query_order_detail);
                    // bind the parameters
                    $stmt_order_detail->bindParam(':order_id', $order_id);
                    $stmt_order_detail->bindParam(':product_id', $product_id_one);
                    $stmt_order_detail->bindParam(':quantity', $quantity_one);

                    // Execute the query
                    if ($stmt_order_detail->execute()) {
                        echo "<div class='alert alert-success'>Record was saved.</div>";
                    } else {
                        echo "<div class='alert alert-danger'>Unable to save record.</div>";
                    }
                }

                if ($_POST["secondProductSelect"] != -1) {
                    $product_id_second =  explode("-", $_POST['secondProductSelect'])[0];
                    $price =  explode("-", $_POST['secondProductSelect'])[1];
                    $quantity_second = $_POST['secondInputOrderQuantity'];
                    $total_amount += $price * $quantity_second;

                    $query_order_detail = "INSERT INTO order_detail SET order_summary_id=:order_id, product_id=:product_id, quantity=:quantity";
                    // prepare query for execution
                    $stmt_order_detail = $con->prepare($query_order_detail);
                    $stmt_order_detail->bindParam(':order_id', $order_id);
                    $stmt_order_detail->bindParam(':product_id', $product_id_second);
                    $stmt_order_detail->bindParam(':quantity', $quantity_second);
                    // Execute the query
                    if ($stmt_order_detail->execute()) {
                        echo "<div class='alert alert-success'>Record was saved.</div>";
                    } else {
                        echo "<div class='alert alert-danger'>Unable to save record.</div>";
                    }
                }

                if ($_POST["thirdProductSelect"] != -1) {
                    $product_id_third = explode("-", $_POST['thirdProductSelect'])[0];
                    $price =  explode("-", $_POST['thirdProductSelect'])[1];
                    $quantity_third = $_POST['thirdInputOrderQuantity'];
                    $total_amount += $price * $quantity_third;

                    $query_order_detail = "INSERT INTO order_detail SET order_summary_id=:order_id, product_id=:product_id, quantity=:quantity";
                    // prepare query for execution
                    $stmt_order_detail = $con->prepare($query_order_detail);
                    $stmt_order_detail->bindParam(':order_id', $order_id);
                    $stmt_order_detail->bindParam(':product_id', $product_id_third);
                    $stmt_order_detail->bindParam(':quantity', $quantity_third);
                    // Execute the query
                    if ($stmt_order_detail->execute()) {
                        echo "<div class='alert alert-success'>Record was saved.</div>";
                    } else {
                        echo "<div class='alert alert-danger'>Unable to save record.</div>";
                    }
                }
                
                $query = "INSERT INTO order_summary SET order_summary_id=:order_id, customer_id=:customer_id, total_price=:total_price, order_date=:order_date";

                $stmt = $con->prepare($query);
                
                $stmt->bindParam(':order_id', $order_id);
                $stmt->bindParam(':customer_id', $customer_id);
                $stmt->bindParam(':total_price', $total_amount);
                $order_date = date('Y-m-d H:i:s');
                $stmt->bindParam(':order_date', $order_date);
                if ($stmt->execute()) {
                    echo "<div class='alert alert-success'>Record was saved.</div>";
                } else {
                    echo "<div class='alert alert-danger'>Unable to save record.</div>";
                }

            }
            // show error
            catch (PDOException $exception) {
                die('ERROR: ' . $exception->getMessage());
            }
        }
        ?>
        <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="POST">
            <div class="mb-3 row">
                <div class="mb-3 col">
                    <label for="inputOrderNumber" class="form-label">Order number</label>
                    <input type="text" class="form-control" id="inputOrderNumber" name="inputOrderNumber" placeholder="#000001" aria-labelledby="orderNumberHit">
                </div>
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
            <table class="table">
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

                    // this is how to get number of rows returned
                    $num = $stmt->rowCount();
                    echo "<tr>";
                    echo "<th scope=\"row\">1</th>";
                    echo "<td>";
                    echo "<div class=\"mb-3 col\">";
                    echo "<select class=\"form-select\" id=\"firstProductSelect\" name=\"firstProductSelect\">";

                    //check if more than 0 record found
                    if ($num > 0) {
                        echo "<option selected value=-1>--Option--</option>";

                        // retrieve our table contents
                        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                            // extract row
                            // this will make $row['firstname'] to just $firstname only
                            extract($row);

                            echo "<option value={$id}-{$price}>{$name}</option>";
                            
                        }
                    } else {
                        echo "<option selected value=-1>No records found</option>";
                    }

                    echo "</select>";
                    echo "</td>";

                    echo "<td>";
                    echo "<div class=\"mb-3 col\">";
                    echo "<input type=\"number\" class=\"form-control\" id=\"firstInputOrderQuantity\" name=\"firstInputOrderQuantity\">";
                    echo "</div>";
                    echo "</td>";

                    echo "</tr>";
                    ?>
                    <?php
                    include 'config/database.php';
                    // select all data
                    $query = "SELECT id, name, price FROM products ORDER BY id DESC";
                    $stmt = $con->prepare($query);
                    // Product 2
                    $stmt->execute();
                    $num = $stmt->rowCount();
                    echo "<tr>";
                    echo "<th scope=\"row\">2</th>";
                    echo "<td>";
                    echo "<div class=\"mb-3 col\">";
                    echo "<select class=\"form-select\" id=\"secondProductSelect\" name=\"secondProductSelect\">";

                    //check if more than 0 record found
                    if ($num > 0) {
                        echo "<option selected value=-1>--Option--</option>";

                        // retrieve our table contents
                        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                            // extract row
                            // this will make $row['firstname'] to just $firstname only
                            extract($row);

                            echo "<option value={$id}-{$price}>{$name}</option>";
                        }
                    } else {
                        echo "<option selected value=-1>No records found</option>";
                    }

                    echo "</select>";
                    echo "</td>";

                    echo "<td>";
                    echo "<div class=\"mb-3 col\">";
                    echo "<input type=\"number\" class=\"form-control\" id=\"secondInputOrderQuantity\" name=\"secondInputOrderQuantity\">";
                    echo "</div>";
                    echo "</td>";

                    echo "</tr>";
                    ?>
                    <?php
                    include 'config/database.php';
                    // select all data
                    $query = "SELECT id, name, price FROM products ORDER BY id DESC";
                    $stmt = $con->prepare($query);
                    // Product 2
                    $stmt->execute();
                    $num = $stmt->rowCount();
                    echo "<tr>";
                    echo "<th scope=\"row\">3</th>";
                    echo "<td>";
                    echo "<div class=\"mb-3 col\">";
                    echo "<select class=\"form-select\" id=\"thirdProductSelect\" name=\"thirdProductSelect\">";

                    //check if more than 0 record found
                    if ($num > 0) {
                        echo "<option selected value=-1>--Option--</option>";

                        // retrieve our table contents
                        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                            // extract row
                            // this will make $row['firstname'] to just $firstname only
                            extract($row);

                            echo "<option value={$id}-{$price}>{$name}</option>";
                        }
                    } else {
                        echo "<option selected value=-1>No records found</option>";
                    }

                    echo "</select>";
                    echo "</td>";

                    echo "<td>";
                    echo "<div class=\"mb-3 col\">";
                    echo "<input type=\"number\" class=\"form-control\" id=\"thirdInputOrderQuantity\" name=\"thirdInputOrderQuantity\">";
                    echo "</div>";
                    echo "</td>";

                    echo "</tr>";
                    ?>
                </tbody>
            </table>
            <button type="submit" class="btn btn-primary mb-3">Process</button>
        </form>
        <table class="table">
            <?php
            if ($_POST) {

                include 'config/database.php';

                // $firstProductSelect = $_POST['firstProductSelect'];
                // $firstInputOrderQuantity = $_POST['firstInputOrderQuantity'];


                //this is how to get number of rows returned

                // $query_order_detail_table = "SELECT product_id quantity FROM order_detail";
                // $query_product_table = "SELECT name, price FROM products where id=:product_id";
                $query = "SELECT  *
                FROM order_detail
                LEFT JOIN products
                ON order_detail.product_id = products.id
             UNION ALL
                SELECT  *
                FROM products
                RIGHT JOIN order_detail
                ON products.id = order_detail.product_id
                Order by order_detail_id desc limit 3";



                $stmt = $con->prepare($query);
                $stmt->execute();

                $num = $stmt->rowCount();
                
                echo "<thead>";
                echo "<tr>";
                echo "<th scope=\"col\">No</th>";
                echo "<th scope=\"col\">Product name</th>";
                echo "<th scope=\"col\">Quantity</th>";
                echo "<th scope=\"col\">Price</th>";
                echo "<th scope=\"col\">Total</th>";
                echo "</tr>";
                echo "</thead>";
                echo "<tbody>";
                $count = 1;
                if ($num > 0) {
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        // show product 1
                        echo "<tr>";
                        echo "<th scope=\"row\">$count</th>";
                        // show product name
                        echo "<td>";
                        echo "<div class=\"mb-3 col\">";
                        echo $row["name"];
                        echo "</div>";
                        echo "</td>";
                        // show product quantity
                        echo "<td>";
                        echo "<div class=\"mb-3 col\">";
                        echo $row["quantity"];
                        echo "</div>";
                        echo "</td>";
                        //show product price
                        echo "<td>";
                        echo "<div class=\"mb-3 col\">";
                        echo $row["price"];
                        echo "</div>";
                        echo "</td>";
                        //show total
                        echo "<td>";
                        echo "<div class=\"mb-3 col\">";
                        $total = $row["quantity"] * $row["price"];
                        echo $total;
                        echo "</div>";
                        echo "</td>";
                        $count++;
                    }
                }
                echo "</tbody>";
                echo "</table>";
            }
            ?>
        </table>
    </div>

    </div> <!-- end .container -->
    <script src="javascript.js"></script>
    <!-- confirm delete record will be here -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-OERcA2EqjJCMA+/3y+gxIOqMEjwtxJY7qPCqsdltbNJuaOe923+mo//f6V8Qbsw3" crossorigin="anonymous"></script>
</body>

</html>