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
    <div class="bg-light bg-images py-2 full_page">
        <div class="d-flex container align-items-center justify-content-center py-2 card bg-color-contact">
            <!--<div class="header_images">
                <img src="images/Web-Development-Illustration.svg" alt="" >
            </div>-->
            <div class="header-text text-center">
                <div class="fw-bold fs-1">Welcome Page</div>
            </div>
        </div>
        <?php
        include 'config/database.php';

        // select all data
        $query = "SELECT(SELECT COUNT(*)FROM customers) AS total_customer,
        (SELECT COUNT(*) FROM order_summary) AS total_order,
        (SELECT COUNT(*) FROM products) AS total_product
        FROM order_summary, products,customers LIMIT 1";
        $stmt = $con->prepare($query);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        extract($row);
        ?>
        <div class="container mt-3">

            <div class="row d-flex justify-content-center  align-items-center">

                <div class="col-lg-3 col-md-4 col-sm-10 col-12 pb-md-0 pb-3">
                    <a href="customer_read" class=" text-decoration-none">
                        <div class="card bg-dark bg-gradient border border-0 hover-blacktogrey">
                            <div class="card-body text-white d-flex align-items-center justify-content-between ">
                                <div>
                                    <h1 class="card-title"><?php echo $total_customer ?></h1>
                                    <p class="card-text">Total Customer</p>
                                </div>
                                <i class="fa-solid fa-users fa-3x"></i>
                            </div>
                        </div>
                    </a>
                </div>

                <div class="col-lg-3 col-md-4 col-sm-10 col-12 pb-md-0 pb-3">
                    <a href="product_read.php" class=" text-decoration-none">
                        <div class="card bg-secondary bg-gradient border border-0 hover-greytoback">
                            <div class="card-body text-white d-flex align-items-center justify-content-between">
                                <div>
                                    <h1 class="card-title"><?php echo $total_product ?></h1>
                                    <p class="card-text">Total Product</p>
                                </div>
                                <i class="fa-solid fa-boxes-stacked fa-3x"></i>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-lg-3 col-md-4 col-sm-10 col-12">
                    <a href="order_read.php" class=" text-decoration-none">
                        <div class="card bg-dark bg-gradient border border-0 hover-blacktogrey">
                            <div class="card-body text-white d-flex align-items-center justify-content-between">
                                <div>
                                    <h1 class="card-title"><?php echo $total_order ?></h1>
                                    <p class="card-text">Total Order</p>
                                </div>
                                <i class="fa-solid fa-handshake fa-3x"></i>
                            </div>
                        </div>
                    </a>
                </div>

                <?php
                $query_total = "SELECT order_summary.order_summary_id, customers.firstname, customers.lastname, order_summary.order_date, SUM(quantity*price) AS total_amount
                FROM order_summary
                INNER JOIN customers
                ON order_summary.customer_id = customers.id
                Inner Join order_detail
                ON order_detail.order_summary_id = order_summary.order_summary_id
                Inner Join products
                ON order_detail.product_id = products.id
                GROUP BY order_summary.order_summary_id
                Order by order_summary_id DESC limit 1";
                $stmt_total = $con->prepare($query_total);
                $stmt_total->execute();
                $row_total = $stmt_total->fetch(PDO::FETCH_ASSOC);
                extract($row_total);
                ?>

                <div class="col-lg-4 col-md-6 col-sm-10 col-12 py-3">
                    <table class='table table-responsive table-striped table-light table-bordered '>
                        <thead>
                            <tr>
                                <th class="text-center table-dark">Latest Order Information</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <th class="d-flex align-items-center justify-content-between flex-wrap">
                                    <div class="col-4">
                                        <?php echo "<a href=\"order_read_one.php?order_summary_id={$order_summary_id}\" class=\"text-black\">Order ID: $order_summary_id</a>" ?>
                                    </div>
                                    <div class="col-7 text-end">
                                        <?php echo $order_date ?>
                                    </div>
                                    <div class="col-4">
                                        <?php echo $firstname . $lastname ?>
                                    </div>
                                    <div class="col-7 text-end">
                                        <?php echo "RM " . number_format(round($total_amount, 1), 2) ?>
                                    </div>
                                </th>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <?php
                $query_higher = "SELECT order_summary.order_summary_id, customers.firstname, customers.lastname, order_summary.order_date, SUM(quantity*price) AS total_high_amount FROM order_summary
                INNER JOIN customers
                ON order_summary.customer_id = customers.id
                Inner Join order_detail
                ON order_detail.order_summary_id = order_summary.order_summary_id
                Inner Join products
                ON order_detail.product_id = products.id GROUP BY order_detail.order_summary_id ORDER BY SUM(quantity*price) DESC Limit 1";
                $stmt_higher = $con->prepare($query_higher);
                $stmt_higher->execute();
                $row_higher = $stmt_higher->fetch(PDO::FETCH_ASSOC);

                ?>
                <div class="col-lg-4 col-md-6 col-sm-10 col-12 pb-sm-0 pb-3">
                    <table class='table table-responsive table-striped table-light table-bordered '>
                        <thead>
                            <tr>
                                <th class="text-center table-dark">Order Highest Purchased Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <th class="d-flex align-items-center justify-content-between flex-wrap">
                                    <div class="col-4">
                                        <?php echo "<a href=\"order_read_one.php?order_summary_id={$row_higher['order_summary_id']}\" class=\"text-black\">Order ID: " . $row_higher['order_summary_id'] . "</a>" ?>
                                    </div>
                                    <div class="col-7 text-end">
                                        <?php echo $row_higher['order_date'] ?>
                                    </div>
                                    <div class="col-4">
                                        <?php echo $row_higher['firstname']  . $row_higher['lastname']  ?>
                                    </div>
                                    <div class="col-7 text-end">
                                        <?php echo "RM " . number_format(round($row_higher['total_high_amount'], 1), 2) ?>
                                    </div>
                                </th>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <?php
                $query_top_sell = "SELECT id,products.name ,SUM(order_detail.quantity) AS total_quantity FROM order_detail Inner Join products ON order_detail.product_id = products.id GROUP by products.name order by total_quantity DESC LIMIT 5";
                $stmt_top_sell = $con->prepare($query_top_sell);
                ?>
                <div class="col-lg-7 col-sm-10 col-12">
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
                                    $count++;
                                }
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
                <?php
                $query_no_sell = "SELECT * FROM products left JOIN order_detail ON order_detail.product_id = products.id WHERE product_id is NULL";
                $stmt_no_sell = $con->prepare($query_no_sell);
                ?>
                <div class="col-lg-6 col-sm-10 col-12">
                    <table class='table table-responsive table-striped table-bordered border-secondary text-center'>
                        <tr class="table-dark">
                            <th colspan="3">Products that never purchased by any customer is...</th>
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
                                echo "<th><a href=\"product_read_one.php?id={$id}\" class=\"text-black\">$name</a></th>";
                                echo "<th class=\"text-end\">RM " . number_format(round($price, 1), 2) . "</th>";
                                echo "</tr>";
                                $count++;
                            }
                        } else {
                            echo "<tr class=\"table-light\">";
                            echo "<th class=\"text-center\"></th>";
                            echo "</tr>";
                        }
                        ?>
                    </table>
                </div>

            </div>
        </div>
    </div>

    <?php
    include 'footer.php';
    ?>

    <!-- end .container -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-OERcA2EqjJCMA+/3y+gxIOqMEjwtxJY7qPCqsdltbNJuaOe923+mo//f6V8Qbsw3" crossorigin="anonymous"></script>
</body>

</html>