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
    <link rel="stylesheet" href="css/index.css">
</head>

<body>
    <?php
    include 'navtop.php';
    ?>
    <!-- container -->
    <div class="container full_page">
        <div class="page-header py-3">
            <h3>Order Detail</h3>
        </div>

        <!-- PHP read one record will be here -->
        <?php
        // get passed parameter value, in this case, the record ID
        // isset() is a PHP function used to verify if a value is there or not
        $order_summary_id = isset($_GET['order_summary_id']) ? $_GET['order_summary_id'] : die('ERROR: Record ID not found.');

        //include database connection
        include 'config/database.php';

        // read current record's data
        try {
            // prepare select query
            $query = "SELECT *
            FROM order_detail, customers , order_summary , products
            WHERE order_detail.order_summary_id = order_summary.order_summary_id AND customers.id = order_summary.customer_id AND order_detail.product_id = products.id AND order_summary.order_summary_id=:order_summary_id";



            // $query = "SELECT cus.username, prdt.price, prdt.description
            // FROM order_detail As detail
            // INNER JOIN order_summary AS sumy
            // On detail.order_summary_id = smry.order_summary_id
            // INNER JOIN products AS prdt
            // ON detail.product_id = prdt.id
            // INNER JOIN customers AS cus
            // ON smry.customer_id = cus.id
            // Where order_summary_id=:order_summary_id";
            $stmt = $con->prepare($query);

            // Bind the parameter
            $stmt->bindParam(":order_summary_id", $order_summary_id);

            // execute our query
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $username = $row['username'];
            $username = $row['username'];
            $firstname = $row['firstname'];
            $lastname = $row['lastname'];
            $order_date = $row['order_date'];
            $customer_id = $row['customer_id'];

        ?>


            <!-- HTML read one record table will be here -->
            <!--we have our html table here where the record will be displayed-->
            <table class='table table-hover table-responsive table-bordered'>
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
            <hr class="my-3 ">
            <div>
                <p class="fw-bold text-center">Order Product List</p>
            </div>

            <table class='table table-hover table-responsive table-bordered'>

                <?php
                $stmt->execute();
                $num = $stmt->rowCount();
                echo "<tr>";
                echo "<th>ID</th>";
                echo "<th>Product Name</th>";
                echo "<th>Price</th>";
                echo "<th>Quatity</th>";
                echo "<th>Total</th>";
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
                        echo "<td name=\"price\">RM " . number_format($price, 2) . "</td>";
                        echo "<td name=\"quantity\">$quantity</td>";
                        $total = $quantity * $price;
                        echo "<td name=\"total\">RM " . number_format($total, 2) . "</td>";
                        echo "</tr>";

                        $count++;
                        $totalamount += $quantity * $price;
                    }
                }
                ?>
                <tr>
                    <th colspan="4" class="text-end pe-5">Total Amount</th>
                    <td class=" fw-bold">RM <?php echo htmlspecialchars(number_format($totalamount, 2), ENT_QUOTES);  ?></td>
                </tr>
            </table>
            <a href='order_read.php' class='btn btn-danger'>Back to order list</a>
        <?php
        }

        // show error
        catch (PDOException $exception) {
            die('ERROR: ' . $exception->getMessage());
        }
        ?>

    </div> <!-- end .container -->

    <footer class="container-fluid py-3 bg-dark m-100">
        <div class="m-auto foot-size d-sm-flex d-block justify-content-between text-white">
            <div class="text-sm-start text-center">Copyright @ 2022 TANZX</div>
            <div class="d-flex justify-content-evenly">
                <div class="mx-3">Terms of Use</div>
                <div class="mx-3">Privacy Policy</div>
            </div>
        </div>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-OERcA2EqjJCMA+/3y+gxIOqMEjwtxJY7qPCqsdltbNJuaOe923+mo//f6V8Qbsw3" crossorigin="anonymous"></script>
</body>

</html>