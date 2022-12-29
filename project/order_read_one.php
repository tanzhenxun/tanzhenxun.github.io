<?php
include 'logincheck.php';
?>
<!DOCTYPE HTML>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Order Detail</title>
    <!-- Latest compiled and minified Bootstrap CSS -->
    <script src="https://kit.fontawesome.com/f9f6f2f33c.js" crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/index.css">
</head>

<body>
    <?php
    include 'navtop.php';
    ?>
    <!-- container -->
    <div class="container full_page mb-3">
        <div class="page-header py-3">
            <h3>Order Detail</h3>
        </div>

        <!-- PHP read one record will be here -->
        <?php
        // get passed parameter value, in this case, the record ID
        // isset() is a PHP function used to verify if a value is there or not
        $order_summary_id = isset($_GET['order_summary_id']) ? $_GET['order_summary_id'] : die('ERROR: Record ID not found.');
        // delete message prompt will be here
        $action = isset($_GET['action']) ? $_GET['action'] : "";

        // if it was redirected from delete.php
        if ($action == 'deleted') {
            echo "<div class='alert alert-success'>Record was deleted.<</div>";
        }

        if ($action == 'sucessful') {
            echo "<div class='alert alert-success'>Record was updated.</div>";
        }


        //include database connection
        include 'config/database.php';

        // read current record's data
        try {
            // prepare select query
            // $query = "SELECT *
            // FROM order_detail, customers , order_summary , products
            // WHERE order_detail.order_summary_id = order_summary.order_summary_id AND customers.id = order_summary.customer_id AND order_detail.product_id = products.id AND order_summary.order_summary_id=:order_summary_id";



            $query = "SELECT *
            FROM order_detail
            INNER JOIN order_summary
            On order_detail.order_summary_id = order_summary.order_summary_id
            INNER JOIN products
            ON order_detail.product_id = products.id
            INNER JOIN customers 
            ON order_summary.customer_id = customers.id
            Where order_summary.order_summary_id=:order_summary_id";
            $stmt = $con->prepare($query);

            // Bind the parameter
            $stmt->bindParam(":order_summary_id", $order_summary_id);

            // execute our query
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $username = $row['username'];
            $firstname = $row['firstname'];
            $lastname = $row['lastname'];
            $order_date = $row['order_date'];
            $customer_id = $row['customer_id'];

        ?>


            <!-- HTML read one record table will be here -->
            <!--we have our html table here where the record will be displayed-->
            <div class="overflow-auto">
                <table class='table table-hover table-bordered'>
                    <tr>
                        <th>ID</th>
                        <td><?php echo htmlspecialchars($customer_id, ENT_QUOTES); ?></td>
                    </tr>
                    <tr>
                        <th>Username</th>
                        <td><?php echo htmlspecialchars($username, ENT_QUOTES);  ?></td>
                    </tr>
                    <tr>
                        <th>Order Date</th>
                        <td><?php echo htmlspecialchars($order_date, ENT_QUOTES);  ?></td>
                    </tr>
                </table>
            </div>
            <hr class="my-3 ">
            <div>
                <p class="fw-bold text-center">Order Product List</p>
            </div>
            <div class="table-responsive">
                <table class='table table-hover table-bordered'>

                    <?php
                    $stmt->execute();
                    $num = $stmt->rowCount();
                    echo "<tr>";
                    echo "<th>ID</th>";
                    echo "<th>Product Name</th>";
                    echo "<th>Price</th>";
                    echo "<th>Quatity</th>";
                    echo "<th>Total (RM)</th>";
                    echo "</tr>";
                    $count = 1;
                    $totalamount = 0;
                    if ($num > 0) {
                        while ($row_p = $stmt->fetch(PDO::FETCH_ASSOC)) {
                            extract($row_p);
                            number_format($price, 2);
                            echo "<tr>";
                            echo "<th scope=\"row\">$count</th>";
                            echo "<td name=\"name\">$name</td>";
                            echo "<td name=\"price\" class=\"text-end\">" . number_format($price, 2) . "</td>";
                            echo "<td name=\"quantity\">$quantity</td>";
                            $total = $quantity * $price;
                            echo "<td name=\"total\" class=\"text-end\">" . number_format($total, 2) . "</td>";
                            echo "</tr>";

                            $count++;
                            $totalamount += $quantity * $price;
                        }
                    }
                    ?>
                    <tr>
                        <th colspan="4" class="text-end pe-5">Sub Total</th>
                        <td class=" fw-bold text-end"><?php echo htmlspecialchars(number_format($totalamount, 2), ENT_QUOTES);  ?></td>
                    </tr>
                    <tr>
                        <th colspan="4" class="text-end pe-5">TOTAL</th>
                        <td class=" fw-bold text-end"><?php echo htmlspecialchars(number_format(round($totalamount, 1), 2), ENT_QUOTES);  ?></td>
                    </tr>
                   
                </table>
            </div>
            <a href='<?php echo "order_update.php?order_summary_id={$order_summary_id}" ?>' class='btn btn-primary me-1 '>Edit</a>
            <a href='order_read.php' class='btn btn-danger'>Back to order list</a>
        <?php
        }

        // show error
        catch (PDOException $exception) {
            die('ERROR: ' . $exception->getMessage());
        }
        ?>

    </div> <!-- end .container -->

    <?php
    include 'footer.php';
    ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-OERcA2EqjJCMA+/3y+gxIOqMEjwtxJY7qPCqsdltbNJuaOe923+mo//f6V8Qbsw3" crossorigin="anonymous"></script>
</body>

</html>