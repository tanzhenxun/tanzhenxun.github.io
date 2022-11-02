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
                    <a class="nav-link" href="product_create.php">Create Product</a>
                    <a class="nav-link active" href="#">Create Customer</a>
                    <a class="nav-link" href="create_order.php">Create Order</a>
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
            <h1>Read Product</h1>
        </div>

        <!-- PHP read one record will be here -->
        <?php
        // get passed parameter value, in this case, the record ID
        // isset() is a PHP function used to verify if a value is there or not
        $id = isset($_GET['id']) ? $_GET['id'] : die('ERROR: Record ID not found.');

        //include database connection
        include 'config/database.php';

        // read current record's data
        try {
            // prepare select query
            $query = "SELECT id, name, description, price FROM products WHERE id = :id ";
            $stmt = $con->prepare($query);

            // Bind the parameter
            $stmt->bindParam(":id", $id);

            // execute our query
            $stmt->execute();

            // store retrieved row to a variable
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            // values to fill up our form
            $name = $row['name'];
            $description = $row['description'];
            $price = $row['price'];
            // shorter way to do that is extract($row)
        }

        // show error
        catch (PDOException $exception) {
            die('ERROR: ' . $exception->getMessage());
        }
        ?>


        <!-- HTML read one record table will be here -->
        <!--we have our html table here where the record will be displayed-->
        <table class='table table-hover table-responsive table-bordered'>
            <tr>
                <td>Name</td>
                <td><?php echo htmlspecialchars($name, ENT_QUOTES);  ?></td>
            </tr>
            <tr>
                <td>Description</td>
                <td><?php echo htmlspecialchars($description, ENT_QUOTES);  ?></td>
            </tr>
            <tr>
                <td>Price</td>
                <td><?php echo htmlspecialchars($price, ENT_QUOTES);  ?></td>
            </tr>
            <tr>
                <td></td>
                <td>
                    <a href='product_read.php' class='btn btn-danger'>Back to read products</a>
                </td>
            </tr>
        </table>


    </div> <!-- end .container -->

    <footer class="container-fluid py-3 bg-dark">
        <div class="m-auto foot-size d-sm-flex d-block justify-content-between text-white">
            <div>Copyright @ 2022 TANZX</div>
            <div class="d-flex">
                <div class="mx-3">Terms of Use</div>
                <div class="mx-3">Privacy Policy</div>
            </div>
        </div>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-OERcA2EqjJCMA+/3y+gxIOqMEjwtxJY7qPCqsdltbNJuaOe923+mo//f6V8Qbsw3" crossorigin="anonymous"></script>
</body>

</html>