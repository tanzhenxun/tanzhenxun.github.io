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
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/index.css">

</head>

<body>
    <!-- container -->
    <?php
    include 'navtop.php';
    ?>
    <div class="container full_page">
        <div class="page-header py-3">
            <h3>Create Customer</h3>
        </div>

        <?php

        //include database connection
        include 'config/database.php';

        // read current record's data
        try {
            // prepare select query
            $query = "SELECT username FROM customers";
            $stmt = $con->prepare($query);

            // execute our query
            $stmt->execute();

            $oldusername = array();
            // store retrieved row to a variable
            $num = $stmt->rowCount();
            if ($num > 0) {
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    // values to fill up our form
                    array_push($oldusername , $row['username']);
                    
                }
            }
        }

        // show error
        catch (PDOException $exception) {
            die('ERROR: ' . $exception->getMessage());
        }

        ?>

        <!-- html form to create product will be here -->
        <!-- PHP insert code will be here -->
        <?php
        if ($_POST) {
            include 'config/database.php';
            $username = $_POST['username'];
            $password = $_POST['password'];
            $firstname = $_POST['firstname'];
            $lastname = $_POST['lastname'];
            $date_of_birth = $_POST['date_of_birth'];
            $account_status = $_POST['account_status'];
            $confirm_password = $_POST['confirm_password'];

            // new 'image' field
            $image = !empty($_FILES["image"]["name"])
                ? sha1_file($_FILES['image']['tmp_name']) . "-" . basename($_FILES["image"]["name"])
                : "NULL";
            $image = htmlspecialchars(strip_tags($image));

            $uppercase = preg_match('@[A-Z]@', $password);
            $lowercase = preg_match('@[a-z]@', $password);
            $number    = preg_match('@[0-9]@', $password);

            // error message is empty
            $file_upload_error_messages = "";

            if ($username == "" || $password == "" || $confirm_password == "" || $firstname == "" || $lastname == "" || $account_status == "" || $date_of_birth == "") {
                echo "<div class='alert alert-danger'>Please make sure have * column are not emplty!</div>";
            } else {
                if (strlen($username) >= 6) {
                    if (strpos(trim($username), ' ')) {
                        $file_upload_error_messages .= "<div>Username should not contain whitespace!</div>";
                    } else {
                        if (in_array($username, $oldusername)) {
                            $file_upload_error_messages .= "<div>Username already exists! Please type again</div>";
                        } else {
                            $username = $_POST['username'];
                        }
                    }
                } else {
                    $file_upload_error_messages .= "<div>Your username must contain at least 6 characters!</div>";
                }

                if (strlen($password) >= 8) {
                    if ($uppercase || $lowercase || $number) {
                        if ($password !== $confirm_password) {
                            $file_upload_error_messages .= "<div>Passwords do not match! Please type again.</div>";
                        } else {
                            $password = md5($_POST['password']);
                        }
                    } else {
                        $file_upload_error_messages .= "<div>Your password must contain at least one uppercase, one lowercase and one number!</div>";
                    }
                } else {
                    $file_upload_error_messages .= "<div>Your password must contain at least 8 characters!</div>";
                }



                $now_date = date('Y-m-d');
                $diff = date_diff(date_create($now_date), date_create($date_of_birth));
                $year = (int)$diff->format("%R%y");

                if ($year >= -18) {
                    $file_upload_error_messages .= "<div>You must be above 18 age old!</div>";
                } else {
                    $date_of_birth = $_POST['date_of_birth'];
                }

                // now, if image is not empty, try to upload the image
                if ($image && $image != "NULL") {

                    // upload to file to folder
                    $target_directory = "upload_customer/";
                    $target_file = $target_directory . $image;
                    $file_type = pathinfo($target_file, PATHINFO_EXTENSION);

                    // make sure that file is a real image
                    $check = getimagesize($_FILES["image"]["tmp_name"]);
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


                if (!empty($file_upload_error_messages)) {
                    echo "<div class='alert alert-danger'>";
                    echo "<div>{$file_upload_error_messages}</div>";
                    echo "</div>";
                } else {
                    try {
                        // insert query
                        $query = "INSERT INTO customers SET username=:username, password=:password, firstname=:firstname, lastname=:lastname, gender=:gender, date_of_birth=:date_of_birth, account_status=:account_status, register_date=:register_date, image_cus=:image_cus";
                        // prepare query for execution
                        $stmt = $con->prepare($query);
                        $gender = $_POST['gender'];
                        // to record whether same image
                        $flag_same_image = false;

                        // bind the parameters
                        $stmt->bindParam(':username', $username);
                        $stmt->bindParam(':password', $password);
                        $stmt->bindParam(':firstname', $firstname);
                        $stmt->bindParam(':lastname', $lastname);
                        $register_date = date('Y-m-d H:i:s'); // get the current date and time
                        $stmt->bindParam(':register_date', $register_date);
                        $stmt->bindParam(':gender', $gender);
                        $stmt->bindParam(':date_of_birth', $date_of_birth);
                        $stmt->bindParam(':account_status', $account_status);
                        $stmt->bindParam(':image_cus', $image);
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
            }
        }

        ?>

        <!-- html form here where the product information will be entered -->

        <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="POST" enctype="multipart/form-data">
            <div class="table-responsive">
                <table class='table table-hover table-bordered'>
                    <tr>
                        <td>Photo</td>
                        <td><input type='file' name='image' /></td>
                    </tr>
                    <tr>
                        <td class="d-flex">Username <p class="text-danger">*</td>
                        <td><input type='text' name='username' class='form-control' value="<?php if (isset($_POST['username'])) {
                                                                                                echo $_POST['username'];
                                                                                            } else {
                                                                                                echo "";
                                                                                            } ?>" /></td>
                    </tr>
                    <tr>
                        <td class="d-flex">First Name <p class="text-danger">*</td>
                        <td><input type='text' name='firstname' class='form-control' value="<?php if (isset($_POST['firstname'])) {
                                                                                                echo $_POST['firstname'];
                                                                                            } else {
                                                                                                echo "";
                                                                                            } ?>" /></td>
                    </tr>
                    <tr>
                        <td class="d-flex">Last Name <p class="text-danger">*</td>
                        <td><input type='text' name='lastname' class='form-control' value="<?php if (isset($_POST['lastname'])) {
                                                                                                echo $_POST['lastname'];
                                                                                            } else {
                                                                                                echo "";
                                                                                            } ?>" /></td>
                    </tr>
                    <tr>
                        <td class="d-flex">Password <p class="text-danger">*</td>
                        <td><input type='password' name='password' class='form-control' /></td>
                    </tr>
                    <tr>
                        <td class="d-flex">Confirm Password <p class="text-danger">*</td>
                        <td><input type='password' name='confirm_password' class='form-control' /></td>
                    </tr>
                    <tr>
                        <td class="d-flex">Gender <p class="text-danger">*</td>
                        <td class="align-item-center">
                            <input type="radio" name="gender" value="male" id="male" class="ms-1 mx-2" <?php
                                                                                                        if (isset($_POST['gender'])) {
                                                                                                            if ($_POST['gender'] == "male") {
                                                                                                                echo "checked";
                                                                                                            }
                                                                                                        } else {
                                                                                                            echo "checked";
                                                                                                        } ?>>
                            <label for="male" class="me-4">Male</label>
                            <input type="radio" name="gender" value="female" id="female" class="ms-1 mx-2" <?php
                                                                                                            if (isset($_POST['gender'])) {
                                                                                                                if ($_POST['gender'] == "female") {
                                                                                                                    echo "checked";
                                                                                                                }
                                                                                                            } ?>>
                            <label for="female">Female</label>
                        </td>
                    </tr>
                    <tr>
                        <td class="d-flex">Date of Birth <p class="text-danger">*</td>
                        <td><input type='date' name='date_of_birth' class='form-control' value="<?php if (isset($_POST['date_of_birth'])) {
                                                                                                    echo $_POST['date_of_birth'];
                                                                                                } else {
                                                                                                    echo "";
                                                                                                } ?>"  onfocus="this.showPicker()"/></td>
                    </tr>
                    <tr>
                        <td class="d-flex">Account Status <p class="text-danger">*</td>
                        <td>
                            <input type="radio" name="account_status" value="active" id="active" class="ms-1 mx-2" <?php
                                                                                                                    if (isset($_POST['account_status'])) {
                                                                                                                        if ($_POST['account_status'] == "active") {
                                                                                                                            echo "checked";
                                                                                                                        }
                                                                                                                    } else {
                                                                                                                        echo "checked";
                                                                                                                    } ?>>
                            <label for="active" class="me-4">Active</label>
                            <input type="radio" name="account_status" value="inactive" id="inactive" class="ms-1 mx-2" <?php
                                                                                                                        if (isset($_POST['account_status'])) {
                                                                                                                            if ($_POST['account_status'] == "inactive") {
                                                                                                                                echo "checked";
                                                                                                                            }
                                                                                                                        } ?>>
                            <label for="inactive">Inactive</label>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" class="text-center">
                            <input type='submit' value='Save' class='btn btn-primary' />
                            <a href='customer_read.php' class='btn btn-danger'>Back to read customer</a>
                        </td>
                    </tr>
                </table>
            </div>
        </form>
    </div>
    <?php
    include 'footer.php';
    ?>
    <!-- end .container -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-OERcA2EqjJCMA+/3y+gxIOqMEjwtxJY7qPCqsdltbNJuaOe923+mo//f6V8Qbsw3" crossorigin="anonymous"></script>
</body>

</html>