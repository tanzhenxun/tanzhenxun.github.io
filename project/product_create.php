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

    <div class="container full_page">
        <div class="page-header my-3">
            <h3>Create Product</h3>
        </div>

        <!-- html form to create product will be here -->
        <!-- PHP insert code will be here -->
        <?php
        if ($_POST) {
            include 'config/database.php';
            // new 'image' field
            $image = !empty($_FILES["image"]["name"])
                ? sha1_file($_FILES['image']['tmp_name']) . "-" . basename($_FILES["image"]["name"])
                : "NULL";
            $image = htmlspecialchars(strip_tags($image));

            $name = $_POST['name'];
            $description = $_POST['description'];
            $price = $_POST['price'];
            $promotion_price = $_POST['promotion_price'];
            $manufacture_date = $_POST['manufacture_date'];
            $expired_date = $_POST['expired_date'];

            // include database connection
            if ($name == "" || $description == "" || $price == "" || $manufacture_date == "") {
                echo "<div class='alert alert-danger'>Please make sure all fields are not emplty!</div>";
            } else if ($promotion_price > $price) {
                echo "<div class='alert alert-danger'>Please correctly your promotion price need cheaper than original price!</div>";
            } else if ($manufacture_date <= $expired_date || $expired_date == "") {
                if ($expired_date == "") {
                    $expired_date = NULL;
                }

                if ($promotion_price == "") {
                    $promotion_price = NULL;
                }

                // now, if image is not empty, try to upload the image
                if ($image && $image != "NULL") {

                    // upload to file to folder
                    $target_directory = "uploads/";
                    $target_file = $target_directory . $image;
                    $file_type = pathinfo($target_file, PATHINFO_EXTENSION);

                    // error message is empty
                    $file_upload_error_messages = "";
                    $image_error = true;
                    // make sure that file is a real image
                    $check = getimagesize($_FILES["image"]["tmp_name"]);
                    if ($check !== false) {
                        // submitted file is an image
                        // make sure certain file types are allowed
                        $allowed_file_types = array("jpg", "jpeg", "png", "gif");
                        if (!in_array($file_type, $allowed_file_types)) {
                            $file_upload_error_messages .= "<div>Only JPG, JPEG, PNG, GIF files are allowed.</div>";
                            $image_error = false;
                        }
                        // make sure file does not exist
                        if (file_exists($target_file)) {
                            $file_upload_error_messages .= "<div>Image already exists. Try to change file name.</div>";
                            $image_error = false;
                        }
                        // make sure submitted file is not too large, can't be larger than 1 MB
                        if ($_FILES['image']['size'] > (1024000)) {
                            $file_upload_error_messages .= "<div>Image must be less than 1 MB in size.</div>";
                            $image_error = false;
                        }
                        // make sure the 'uploads' folder exists
                        // if not, create it
                        if (!is_dir($target_directory)) {
                            mkdir($target_directory, 0777, true);
                        }
                    } else {
                        $file_upload_error_messages .= "<div>Submitted file is not an image.</div>";
                        $image_error = false;
                    }
                    // if $file_upload_error_messages is still empty
                    if (empty($file_upload_error_messages)) {
                        // it means there are no errors, so try to upload the file
                        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                            // it means photo was uploaded
                        } else {
                            echo "<div class='alert alert-danger'>";
                            echo "<div>Unable to upload photo.</div>";
                            echo "<div>Update the record to upload photo.</div>";
                            echo "</div>";
                            $image_error = false;
                        }
                    }

                    // if $file_upload_error_messages is NOT empty
                    else {
                        // it means there are some errors, so show them to user
                        echo "<div class='alert alert-danger'>";
                        echo "<div>{$file_upload_error_messages}</div>";
                        echo "<div>Update the record to upload photo.</div>";
                        echo "</div>";
                        $image_error = false;
                    }
                }
                if ($image_error == true) {
                    try {
                        // insert query
                        $query = "INSERT INTO products SET name=:name, description=:description, price=:price, created=:created, promotion_price=:promotion_price, manufacture_date=:manufacture_date, image=:image, expired_date=:expired_date";

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
                        $stmt->bindParam(':image', $image);

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
                }
            } else {
                echo "<div class='alert alert-danger'>Your manufacture date no longer than expired date!</div>";
            }
        }

        ?>


        <!-- html form here where the product information will be entered -->
        <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="POST" enctype="multipart/form-data">
            <table class='table table-hover table-responsive table-bordered '>
                <tr>
                    <td>Photo</td>
                    <td><input type="file" name="image" /></td>
                </tr>
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