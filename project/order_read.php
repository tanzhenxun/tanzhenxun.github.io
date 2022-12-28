<?php
include 'logincheck.php';
?>
<!DOCTYPE HTML>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Order List</title>
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
    <div class="container full_page ">
        <div class="page-header my-3">
            <h1>Order List</h1>
        </div>

        <!-- PHP code to read records will be here -->
        <?php
        // include database connection
        include 'config/database.php';

        // delete message prompt will be here
        $action = isset($_GET['action']) ? $_GET['action'] : "";

        // if it was redirected from delete.php
        if ($action == 'deleted') {
            echo "<div class='alert alert-success'>Record was deleted.<</div>";
        }

        if($action=='sucessful'){
            echo "<div class='alert alert-success'>Record was updated.</div>";
        }

        // select all data

        $query = "SELECT smry.order_summary_id, firstname, username, order_date, lastname, SUM(quantity*price) AS total_amount
        FROM order_summary AS smry
        INNER JOIN customers AS cus
        ON smry.customer_id = cus.id
        Inner Join order_detail AS detail
        ON detail.order_summary_id = smry.order_summary_id
        Inner Join products AS prdt
        ON detail.product_id = prdt.id
        GROUP BY smry.order_summary_id
        Order by order_summary_id DESC";

        $stmt = $con->prepare($query);
        $stmt->execute();

        // this is how to get number of rows returned
        $num = $stmt->rowCount();

        // link to create record form
        echo "<a href='order_create.php' class='btn btn-primary mb-3 '>Create New Order</a>";

        //check if more than 0 record found
        if ($num > 0) {

            // data from database will be here
            echo "<table class='table table-hover table-bordered'>"; //start table

            //creating our table heading
            echo "<tr>";
            echo "<th>ID</th>";
            echo "<th>Username</th>";
            echo "<th>First Name</th>";
            echo "<th>Last Name</th>";
            echo "<th>Order Date</th>";
            echo "<th>Total Amount (RM)</th>";
            echo "<th>Action</th>";
            echo "</tr>";

            // table body will be here
            // retrieve our table contents
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                // extract row
                // this will make $row['firstname'] to just $firstname only //table inside call $_post('username') so $row['username'] if $_post('name') so $row['name'] 
                extract($row);
                // creating new table row per record
                echo "<tr>";
                echo "<td>{$order_summary_id}</td>";
                echo "<td>{$username}</td>";
                echo "<td>{$firstname}</td>";
                echo "<td>{$lastname}</td>";
                echo "<td>{$order_date}</td>";
                echo "<td class=\"text-end\">" . number_format(round($total_amount, 1), 2) . "</td>";
                echo "<td class\"\">";
                // read one record
                echo "<a href='order_read_one.php?order_summary_id={$order_summary_id}' class='btn btn-info me-1'>Read</a>";
                
                // we will use this links on next part of this post
                echo "<a href='order_update.php?order_summary_id={$order_summary_id}' class='btn btn-primary me-1'>Edit</a>";

                // we will use this links on next part of this post
                echo "<button onclick='delete_order($order_summary_id);'  class='btn btn-danger'>Delete</button>";
                echo "</td>";
                echo "</tr>";
            }
            // end table
            echo "</table>";
        } else {
            echo "<div class='alert alert-danger'>No records found.</div>";
        }
        ?>


    </div> <!-- end .container -->
    <?php
    include 'footer.php';
    ?>
    <script>
        function delete_order(order_summary_id) {

            if (confirm('Are you sure delete this order?')) {
                // if user clicked ok,
                // pass the id to delete.php and execute the delete query
                window.location = 'order_delete.php?order_summary_id=' + order_summary_id;
            }
        }
    </script>

    <!-- confirm delete record will be here -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-OERcA2EqjJCMA+/3y+gxIOqMEjwtxJY7qPCqsdltbNJuaOe923+mo//f6V8Qbsw3" crossorigin="anonymous"></script>
</body>

</html>