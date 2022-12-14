<?php
include 'logincheck.php';
?>

<!DOCTYPE HTML>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Latest compiled and minified Bootstrap CSS -->
    <script src="https://kit.fontawesome.com/f9f6f2f33c.js" crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
    <link rel="stylesheet" href="css/index.css">
    <title>Product Update</title>
    <!-- Latest compiled and minified Bootstrap CSS -->

    <!-- custom css -->
    <style>
        .m-r-1em {
            margin-right: 1em;
        }

        .m-b-1em {
            margin-bottom: 1em;
        }

        .m-l-1em {
            margin-left: 1em;
        }

        .mt0 {
            margin-top: 0;
        }
    </style>
</head>

<body>
    <?php
    include 'navtop.php';
    ?>
    <!-- container -->
    <div class="container full_page">
        <div class="page-header py-3">
            <h3>Edit Product Detail</h3>
        </div>
        <!-- PHP read record by ID will be here -->
        <?php

        // get passed parameter value, in this case, the record ID
        // isset() is a PHP function used to verify if a value is there or not
        $id = isset($_GET['id']) ? $_GET['id'] : die('ERROR: Record ID not found.');

        //include database connection
        include 'config/database.php';

        // read current record's data
        try {
            // prepare select query
            $query = "SELECT * FROM products WHERE id = ? LIMIT 0,1";
            $stmt = $con->prepare($query);

            // this is the first question mark
            $stmt->bindParam(1, $id);

            // execute our query
            $stmt->execute();

            // store retrieved row to a variable
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            // values to fill up our form
            $name = $row['name'];
            $description = $row['description'];
            $price = $row['price'];
            $created = $row['created'];
            $promotion_price = $row['promotion_price'];
            $manufacture_date = $row['manufacture_date'];
            $expired_date = $row['expired_date'];

            $store_image = "upload_products/";
            if (!empty($row['image']) && $row['image'] != "NULL") {
                $old_image = $store_image . $row['image'];
            } else {
                $old_image = $store_image . "no_image.jpg";
            }
        }

        // show error
        catch (PDOException $exception) {
            die('ERROR: ' . $exception->getMessage());
        }
        ?>


        <!-- PHP post to update record will be here -->
        <?php
        // check if form was submitted
        if ($_POST) {

            $image = !empty($_FILES["image"]["name"])
                ? sha1_file($_FILES['image']['tmp_name']) . "-" . basename($_FILES["image"]["name"])
                : NULL; //pathinfo($old_image, PATHINFO_BASENAME);
            $image = htmlspecialchars(strip_tags($image));


            $name = htmlspecialchars(strip_tags($_POST['name']));
            $description = htmlspecialchars(strip_tags($_POST['description']));
            $price = htmlspecialchars(strip_tags($_POST['price']));
            $promotion_price = htmlspecialchars(strip_tags($_POST['promotion_price']));
            $manufacture_date = htmlspecialchars(strip_tags($_POST['manufacture_date']));
            $expired_date = htmlspecialchars(strip_tags($_POST['expired_date']));

            if (empty($image) && $image == "NULL") {
                $image = $store_image . "no_image.jpg";
            }

            // error message is empty
            $file_upload_error_messages = "";

            // include database connection
            if ($name == "" || $description == "" || $price == "" || $manufacture_date == "") {
                echo "<div class='alert alert-danger'>Please make sure all fields are not emplty!</div>";
            } else {

                if ($promotion_price == "") {
                    $promotion_price = NULL;
                } else {
                    if ($promotion_price > $price) {
                        $file_upload_error_messages .= "<div>Please correctly your promotion price need cheaper than original price!</div>";
                    }
                }

                if ($expired_date == "") {
                    $expired_date = NULL;
                } else {
                    $date1 = date_create($manufacture_date);
                    $date2 = date_create($expired_date);
                    $diff = date_diff($date1, $date2);
                    
                    //https://linuxhint.com/calculate-date-difference-php/

                    if(($diff->format("%R%a days")) < 0){
                        $file_upload_error_messages .= "<div>Your manufacture date no longer than expired date!</div>";
                    }

                }

                if ($price >= 10000 || $price < 0) {
                    $file_upload_error_messages .= "<div>Make sure your field in the price blank wouldn't more than 10000 or less than 0 price!</div>";
                }

                if (isset($_POST['images_remove']) && $_POST['images_remove'] != "" && !empty($_FILES['image']['name'])) {
                    $file_upload_error_messages .= "<div>Please note that you cannot select a new image while checking for image deletion, please select one.</div>";
                }

                // now, if image is not empty, try to upload the image
                if (!empty($_FILES["image"]["name"])) {
                    if ($image && $image != "NULL") {
                        // upload to file to folder
                        $target_directory = "upload_products/";
                        $target_file = $target_directory . $image; // uploads/(image name)
                        $file_type = pathinfo($target_file, PATHINFO_EXTENSION); // find the image format like jpg, png ..


                        // make sure that file is a real image
                        $check = getimagesize($_FILES["image"]["tmp_name"]);
                        //echo $target_file;
                        //print_r($check); 
                        //The getimagesize() function will determine the size of any supported given image file and return the dimensions along with the file type and a height/width text string to be used inside a normal HTML IMG tag and the correspondent HTTP content type.
                        //echo $_FILES["image"]["tmp_name"] = C:\wamp64\tmp\php44C0.tmp
                        //$_FILES["file"]["name"] //stores the original filename from the client
                        //$_FILES["file"]["tmp_name"] //stores the name of the temporary file

                        if ($check !== false) {
                            // submitted file is an image
                            // make sure certain file types are allowed
                            $allowed_file_types = array("jpg", "jpeg", "png", "gif");
                            if (!in_array($file_type, $allowed_file_types)) {
                                $file_upload_error_messages .= "<div>Only JPG, JPEG, PNG, GIF files are allowed.</div>";
                            }
                            // make sure file does not exist
                            if (file_exists($target_file)) {
                                $file_upload_error_messages .= "<div>Image already exists. Try to change file name.</div>";
                            }
                            // make sure submitted file is not too large, can't be larger than 1 MB
                            if ($_FILES['image']['size'] > (1024000)) {
                                $file_upload_error_messages .= "<div>Image must be less than 1 MB in size.</div>";
                            }
                            // make sure the 'uploads' folder exists
                            // if not, create it
                            if (!is_dir($target_directory)) {
                                mkdir($target_directory, 0777, true);
                            }
                        } else {
                            $file_upload_error_messages .= "<div>Submitted file is not an image.</div>";
                        }
                        // if $file_upload_error_messages is still empty
                        if (empty($file_upload_error_messages)) {
                            // it means there are no errors, so try to upload the file
                            if (!move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                                // it means photo was uploaded
                                $file_upload_error_messages .= "<div>Unable to upload photo.</div>";
                            }
                        }
                    }
                }


                if (empty($file_upload_error_messages)) {
                    try {
                        // write update query
                        // in this case, it seemed like we have so many fields to pass and
                        // it is better to label them and not use question marks
                        $query = "UPDATE products
                  SET name=:name, description=:description,price=:price, promotion_price=:promotion_price,manufacture_date=:manufacture_date, expired_date=:expired_date, image=:image WHERE id = :id";
                        // prepare query for excecution
                        $stmt = $con->prepare($query);

                        // to record whether same image
                        $flag_same_image = false;

                        // not checked and not upload new image
                        if ($image == "NULL" && empty($_POST['images_remove'])) {
                            $image = pathinfo($old_image, PATHINFO_BASENAME);
                            $flag_same_image = true;
                            // checked and not upload new image
                        } else if ($image == "NULL" && !empty($_POST['images_remove'])) {
                            $image = "NULL";
                        }

                        // bind the parameters
                        $stmt->bindParam(':name', $name);
                        $stmt->bindParam(':description', $description);
                        $stmt->bindParam(':price', $price);
                        $stmt->bindParam(':promotion_price', $promotion_price);
                        $stmt->bindParam(':manufacture_date', $manufacture_date);
                        $stmt->bindParam(':expired_date', $expired_date);
                        $stmt->bindParam(':image', $image);
                        $stmt->bindParam(':id', $id);

                        // Execute the query
                        if ($stmt->execute()) {
                            
                            // if the image not same then remove previous one and not the default one
                            if (!$flag_same_image && !strpos($old_image, "no_image.jpg")) {
                                unlink($old_image);
                            }
                        
                            echo "<script type=\"text/javascript\"> window.location.href='product_read.php?action=sucessful'</script>";
                        } else {
                            echo "<div class='alert alert-danger'>Unable to update record. Please try again.</div>";
                        }
                    }
                    // show errors
                    catch (PDOException $exception) {
                        die('ERROR: ' . $exception->getMessage());
                    }
                } else {
                    echo "<div class='alert alert-danger'>";
                    echo "<div>{$file_upload_error_messages}</div>";
                    echo "</div>";
                }
            }
        } ?>

        <!-- HTML form to update record will be here -->
        <!--we have our html form here where new record information can be updated 
    get method have maximun size-->
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"] . "?id={$id}"); ?>" method="POST" enctype="multipart/form-data">
            <table class='table table-hover table-responsive table-bordered'>
                <tr>
                    <td>Photo</td>
                    <td>
                        <img src="<?php echo htmlspecialchars($old_image, ENT_QUOTES); ?>" width="200" id="delete_images">
                        <br>
                        <input class="mb-3" type="checkbox" id="images_remove" name="images_remove" value="Yes" />
                        <label for="images_remove">Empty/Default Image</label>
                        <br>
                        <input type="file" name='image' class="pt-2">
                    </td>
                </tr>

                <tr>
                    <td>Name<p class="text-danger">*</p></td>
                    <td><input type='text' name='name' value="<?php echo htmlspecialchars($name, ENT_QUOTES);  ?>" class='form-control' /></td>
                </tr>
                <tr>
                    <td>Description<p class="text-danger">*</p></td>
                    <td><textarea name='description' class='form-control'><?php echo htmlspecialchars($description, ENT_QUOTES);  ?></textarea></td>
                </tr>
                <tr>
                    <td>Price<p class="text-danger">*</p></td>
                    <td><input type='text' name='price' value="<?php echo htmlspecialchars($price, ENT_QUOTES);  ?>" class='form-control' /></td>
                </tr>
                <tr>
                    <td>Promotion Price</td>
                    <td><input type='text' name='promotion_price' value="<?php echo htmlspecialchars($promotion_price, ENT_QUOTES);  ?>" class='form-control' /></td>
                </tr>
                <tr>
                    <td>Manufacture Date<p class="text-danger">*</p></td>
                    <td><input type='date' name='manufacture_date' value="<?php echo htmlspecialchars($manufacture_date, ENT_QUOTES);  ?>" class='form-control' /></td>
                </tr>
                <tr>
                    <td>Expired Date</td>
                    <td><input type='date' name='expired_date' value="<?php echo htmlspecialchars($expired_date, ENT_QUOTES);  ?>" class='form-control' /></td>
                </tr>
                <tr>
                    <td></td>
                    <td>
                        <input type='submit' value='Save Changes' class='btn btn-primary' />
                        <a href='product_read.php' class='btn btn-danger'>Back to read products</a>
                    </td>
                </tr>
            </table>
        </form>

    </div>
    <footer class="container-fluid py-3 bg-dark m-100">
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