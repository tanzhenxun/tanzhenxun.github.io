<?php
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
        <div class="page-header my-3">
            <h1>Read Customers</h1>
        </div>

        <!-- PHP code to read records will be here -->
        <?php
        // include database connection
        include 'config/database.php';

        // delete message prompt will be here
        $action = isset($_GET['action']) ? $_GET['action'] : "";

        // if it was redirected from delete.php
        if ($action == 'deleted') {
            echo "<div class='alert alert-success'>Record was deleted.</div>";
        }

        if ($action == 'cancel') {
            echo "<div class='alert alert-danger'>This data is used in other sections.</div>";
        }

        if($action=='sucessful'){
            echo "<div class='alert alert-success'>Record was updated.</div>";
        }


        // select all data
        $query = "SELECT id, username, register_date, image_cus, account_status FROM customers ORDER BY id DESC";
        $stmt = $con->prepare($query);
        $stmt->execute();

        // this is how to get number of rows returned
        $num = $stmt->rowCount();

        // link to create record form
        echo "<a href='customer_create.php' class='btn btn-primary mb-3 '>Create New Customers</a>";

        //check if more than 0 record found
        if ($num > 0) {

            // data from database will be here
            echo "<table class='table table-hover table-responsive table-bordered'>"; //start table

            //creating our table heading
            echo "<tr>";
            echo "<th>ID</th>";
            echo "<th>Photo</th>";
            echo "<th>Username</th>";
            echo "<th>Register Date</th>";
            echo "<th>Account Status</th>";
            echo "<th>Action</th>";
            echo "</tr>";

            // table body will be here
            // retrieve our table contents
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                // extract row
                // this will make $row['firstname'] to just $firstname only
                extract($row);
                if (empty($image_cus) || $image_cus == "NULL") {
                    $image_cus = "no_image.jpg";
                }
                $store_pro_img = "upload_customer/";
                // creating new table row per record
                $image_profile = $store_pro_img . $image_cus;
                
                echo "<tr>";
                echo "<td>{$id}</td>";
                echo "<td><img src=\"{$image_profile}\" alt=\"$username\" width=\"50\" height=\"auto\"></td>";
                echo "<td>{$username}</td>";
                echo "<td>{$register_date}</td>";
                echo "<td>{$account_status}</td>";
                echo "<td class\"\">";
                // read one record
                echo "<a href='customer_read_one.php?id={$id}' class='btn btn-info me-1'>Read</a>";

                // we will use this links on next part of this post
                echo "<a href='customer_update.php?id={$id}' class='btn btn-primary me-1'>Edit</a>";

                // we will use this links on next part of this post
                echo "<button onclick='delete_user($id);'  class='btn btn-danger'>Delete</button>";
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
    <footer class="container-fluid py-3 bg-dark">
        <div class="m-auto foot-size d-sm-flex d-block justify-content-between text-white">
            <div class="text-sm-start text-center">Copyright @ 2022 TANZX</div>
            <div class="d-flex justify-content-evenly">
                <div class="mx-3">Terms of Use</div>
                <div class="mx-3">Privacy Policy</div>
            </div>
        </div>
    </footer>
    <script>
        function delete_user(id) {

            if (confirm('Are you sure?')) {
                // if user clicked ok,
                // pass the id to delete.php and execute the delete query
                window.location = 'customer_delete.php?id=' + id;
            }
        }
    </script>
    <!-- confirm delete record will be here -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-OERcA2EqjJCMA+/3y+gxIOqMEjwtxJY7qPCqsdltbNJuaOe923+mo//f6V8Qbsw3" crossorigin="anonymous"></script>
</body>

</html>