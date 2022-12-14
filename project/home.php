<?php
include 'logincheck.php';
?>

<!DOCTYPE HTML>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>TANZX's Home</title>
    <!-- Latest compiled and minified Bootstrap CSS -->
    <script src="https://kit.fontawesome.com/f9f6f2f33c.js" crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
    <link href="css/index.css" rel="stylesheet">
</head>

<body>
    <!-- container -->
    <?php
    include 'navtop.php';
    ?>
    <div class="bg-light bg-images py-1 full_page">
        <div class="d-flex container align-items-center justify-content-center mt-3">
            <!--<div class="header_images">
                <img src="images/Web-Development-Illustration.svg" alt="" >
            </div>-->
            <div class="header-text text-center">
                <h1>Welcome Page</h1>
            </div>
        </div>
        <?php
        include 'config/database.php';

        // select all data
        $query = "SELECT(SELECT COUNT(*)FROM customers) AS total_customer,
                (SELECT COUNT(*) FROM order_summary) AS total_order,
                (SELECT COUNT(*) FROM products) AS total_product,
                order_summary.order_summary_id, customers.username, order_summary.order_date, SUM(quantity*price) AS total_amount,
                (SELECT SUM(quantity*price) AS total_high_amount FROM order_detail Inner Join products ON order_detail.product_id = products.id GROUP BY order_detail.order_summary_id ORDER BY SUM(quantity*price) DESC Limit 1)AS highest_amount
                FROM order_summary
                INNER JOIN customers
                ON order_summary.customer_id = customers.id
                Inner Join order_detail
                ON order_detail.order_summary_id = order_summary.order_summary_id
                Inner Join products
                ON order_detail.product_id = products.id
                GROUP BY order_summary.order_summary_id
                Order by order_summary_id DESC limit 1";
        $query_top_sell = "SELECT id,products.name ,SUM(order_detail.quantity) AS total_quantity FROM order_detail Inner Join products ON order_detail.product_id = products.id GROUP by products.name order by total_quantity DESC LIMIT 5";
        $query_no_sell = "SELECT * FROM products left JOIN order_detail ON order_detail.product_id = products.id WHERE product_id is NULL";

        $stmt = $con->prepare($query);
        $stmt_top_sell = $con->prepare($query_top_sell);
        $stmt_no_sell = $con->prepare($query_no_sell);
        $stmt->execute();
        $stmt_top_sell->execute();
        $stmt_no_sell->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        extract($row);
        ?>
        <div class="container align-items-center justify-content-center mt-3">
            <div class="row">
                <div class="col-4">
                    <table class='table table-responsive table-borderless table-dark'>
                        <tr>
                            <th>Total Customer</th>
                            <td class="table-light fw-bold"><?php echo $total_customer ?> person</td>
                        </tr>
                        <tr>
                            <th>Total Product</th>
                            <td class="table-light fw-bold"><?php echo $total_product ?> products</td>
                        </tr>
                        <tr>
                            <th>Total Order</th>
                            <td class="table-light fw-bold"><?php echo $total_order ?> orders</td>
                        </tr>
                    </table>
                </div>
                <div class="col-8">
                    <table class='table table-responsive table-striped table-light table-bordered'>
                        <thead>
                            <tr>
                                <th colspan='4' class="text-center table-dark">Latest Order Information</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <th>ID</th>
                                <th>Customer Name</th>
                                <th>Transaction Date</th>
                                <th>Purchase Amount (RM)</th>
                            </tr>
                            <tr>
                                <th><?php echo $order_summary_id ?></th>
                                <td><?php echo $username ?></td>
                                <td><?php echo $order_date ?></td>
                                <td><?php echo number_format(round($total_amount, 1), 2) ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="col-4">
                    <table class='table table-responsive table-striped table-bordered border-secondary text-center'>
                        <tr class="table-dark">
                            <th>Our order highest purchased amount is...</th>
                        </tr>
                        <tr class="table-light">
                            <th>RM <?php echo number_format(round($highest_amount, 1), 2) ?></th>
                        </tr>
                    </table>
                    <table class='table table-responsive table-striped table-bordered border-secondary text-center'>
                        <tr class="table-dark">
                            <th colspan="2">Products that never purchased by any customer is...</th>
                        </tr>
                        <?php
                        $stmt_no_sell->execute();
                        $num = $stmt_no_sell->rowCount();
                        $count = 1;
                        if ($num > 0) {
                            while ($row_nosell = $stmt_no_sell->fetch(PDO::FETCH_ASSOC)) {
                                extract($row_nosell);
                                echo "<tr class=\"table-light\">";
                                echo "<th class=\"text-center\">$count</th>";
                                echo "<th>$name</th>";
                                echo "</tr>";
                                $count ++;
                            }
                        }else{
                            echo "<tr class=\"table-light\">";
                            echo "<th class=\"text-center\"></th>";
                            echo "</tr>";
                        }
                        ?>
                    </table>
                </div>
                <div class="col-8">
                    <table class='table table-responsive table-striped table-bordered table-light'>
                    <thead>
                            <tr>
                                <th colspan="3" class="text-center table-dark">Top 5 selling Products</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                        $stmt_top_sell->execute();
                        $num = $stmt_top_sell->rowCount();
                        echo "<tr>";
                        echo "<th class=\"text-center\">No</th>";
                        echo "<th>Product Name</th>";
                        echo "<th>Total Quantity</th>";
                        echo "</tr>";
                        $count = 1;
                        if ($num > 0) {
                            while ($row_topsell = $stmt_top_sell->fetch(PDO::FETCH_ASSOC)) {
                                extract($row_topsell);
                                echo "<tr>";
                                echo "<th class=\"text-center\">$count</th>";
                                echo "<th>$name</th>";
                                echo "<th>$total_quantity</th>";
                                echo "</tr>";
                                $count ++;
                            }
                        }
                        ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>


    <footer class="container-fluid py-3 bg-dark">
        <div class="m-auto foot-size d-sm-flex d-block justify-content-between text-white">
            <div class="text-sm-start text-center">Copyright @ 2022 TANZX</div>
            <div class="d-flex justify-content-evenly">
                <div class="mx-3">Terms of Use</div>
                <div class="mx-3">Privacy Policy</div>
            </div>
        </div>
    </footer>
    <!-- end .container -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-OERcA2EqjJCMA+/3y+gxIOqMEjwtxJY7qPCqsdltbNJuaOe923+mo//f6V8Qbsw3" crossorigin="anonymous"></script>
</body>

</html>