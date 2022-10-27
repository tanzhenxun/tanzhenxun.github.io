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
        <div class="page-header my-3">
            <h1>Read Products</h1>
        </div>

        <!-- PHP code to read records will be here -->
        <?php
        // include database connection
        include 'config/database.php';

        // delete message prompt will be here

        // select all data
        $query = "SELECT id, name, description, price FROM products ORDER BY id DESC";
        $stmt = $con->prepare($query);
        $stmt->execute();

        // this is how to get number of rows returned
        $num = $stmt->rowCount();

        // link to create record form
        echo "<a href='product_create.php' class='btn btn-primary mb-3 '>Create New Product</a>";

        //check if more than 0 record found
        if ($num > 0) {

            // data from database will be here
            echo "<table class='table table-hover table-responsive table-bordered'>"; //start table

            //creating our table heading
            echo "<tr>";
            echo "<th>ID</th>";
            echo "<th>Name</th>";
            echo "<th>Description</th>";
            echo "<th>Price</th>";
            echo "<th>Action</th>";
            echo "</tr>";

            // table body will be here
            // retrieve our table contents
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                // extract row
                // this will make $row['firstname'] to just $firstname only
                extract($row);
                // creating new table row per record
                echo "<tr>";
                echo "<td>{$id}</td>";
                echo "<td>{$name}</td>";
                echo "<td>{$description}</td>";
                echo "<td>{$price}</td>";
                echo "<td class\"\">";
                // read one record
                echo "<a href='product_read_one.php?id={$id}' class='btn btn-info me-1'>Read</a>";

                // we will use this links on next part of this post
                echo "<a href='product_update.php?id={$id}' class='btn btn-primary me-1'>Edit</a>";

                // we will use this links on next part of this post
                echo "<a href='#' onclick='delete_product({$id});'  class='btn btn-danger'>Delete</a>";
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

    <!-- confirm delete record will be here -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-OERcA2EqjJCMA+/3y+gxIOqMEjwtxJY7qPCqsdltbNJuaOe923+mo//f6V8Qbsw3" crossorigin="anonymous"></script>
</body>

</html>