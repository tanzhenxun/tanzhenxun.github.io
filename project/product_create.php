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
    <nav class="navbar navbar-expand-lg navbar border-dark border-bottom border-2">
        <div class="container-fluid m-auto nav-size align-items-center">
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
                    <a class="nav-link" aria-current="page" href="home.php">Home</a>
                    <a class="nav-link active" href="#">Create Product</a>
                    <a class="nav-link" href="customer_create.php">Create Customer</a>
                    <a class="nav-link" href="create_order.php">Create Order</a>
                    <a class="nav-link" href="contact.php">Contact Us</a>
                </div>
                <div class="navbar-brand d-flex align-center">
                    <a href="https://github.com/tanzhenxun" class=" text-dark me-3"><i class="fa-brands fa-github fa-2x"></i></a>
                    <a href="https://www.instagram.com/tan315_x18/?hl=en" class=" text-dark me-3"><i class="fa-brands fa-instagram fa-2x"></i></a>
                    <a href="logout.php" class="btn btn-dark">Logout</a>
                </div>
            </div>
        </div>
    </nav>

    <div class="container">
        <div class="page-header my-3">
            <h3>Create Product</h3>
        </div>

        <!-- html form to create product will be here -->
        <!-- PHP insert code will be here -->
        <?php
        if ($_POST) {
            $name = $_POST['name'];
            $description = $_POST['description'];
            $price = $_POST['price'];
            $promotion_price = $_POST['promotion_price'];
            $manufacture_date = $_POST['manufacture_date'];
            $expired_date = $_POST['expired_date'];

            if ($expired_date == "") {
                $expired_date = NULL;
            }

            if($promotion_price == ""){
                $promotion_price = NULL;
            }

            // include database connection
            if ($name == "" || $description == "" || $price == "" || $manufacture_date == "") {
                echo "<div class='alert alert-danger'>Please make sure all fields are not emplty!</div>";
            } else if ($promotion_price > $price) {
                echo "<div class='alert alert-danger'>Please correctly your promotion price need cheaper than original price!</div>";
            } else if ($manufacture_date <= $expired_date || $expired_date == "") {
                include 'config/database.php';
                try {
                    // insert query
                    $query = "INSERT INTO products SET name=:name, description=:description, price=:price, created=:created, promotion_price=:promotion_price, manufacture_date=:manufacture_date, expired_date=:expired_date";
                    // prepare query for execution
                    $stmt = $con->prepare($query);
                    // bind the parameters
                    $stmt->bindParam(':name', $name);
                    $stmt->bindParam(':description', $description);
                    $stmt->bindParam(':price', $price);
                    $created = date('Y-m-d H:i:s'); // get the current date and time
                    $stmt->bindParam(':created', $created);
                    $stmt->bindParam(':promotion_price', $promotion_price);
                    $stmt->bindParam(':manufacture_date', $manufacture_date);
                    $stmt->bindParam(':expired_date', $expired_date);
                    // Execute the query
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
            } else {
                echo "<div class='alert alert-danger'>Your manufacture date no longer than expired date!</div>";
            }
        }

        ?>


        <!-- html form here where the product information will be entered -->
        <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="POST">
            <table class='table table-hover table-responsive table-bordered '>
                <tr>
                    <td>Name</td>
                    <td><input type='text' name='name' class='form-control' /></td>
                </tr>
                <tr>
                    <td>Description</td>
                    <td><textarea rows="5" cols="33" name='description' class='form-control'></textarea></td>
                </tr>
                <tr>
                    <td>Price</td>
                    <td><input type='text' name='price' class='form-control' /></td>
                </tr>
                <tr>
                    <td>Promotion Price</td>
                    <td><input type='text' name='promotion_price' class='form-control' /></td>
                </tr>
                <tr>
                    <td>Manufacture Date</td>
                    <td><input type='date' name='manufacture_date' class='form-control' /></td>
                </tr>
                <tr>
                    <td>Expired Date</td>
                    <td><input type='date' name='expired_date' class='form-control' /></td>
                </tr>
                <tr>

                    <td colspan="2" class="text-center">
                        <input type='submit' value='Save' class='btn btn-primary' />
                        <a href='index.php' class='btn btn-danger'>Back to read products</a>
                    </td>
                </tr>
            </table>
        </form>
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