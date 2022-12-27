<?php
include 'logincheck.php';
?>

<!DOCTYPE HTML>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Latest compiled and minified Bootstrap CSS -->
    <script src="https://kit.fontawesome.com/f9f6f2f33c.js" crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
    <link rel="stylesheet" href="css/index.css">
    <title>Customer Update</title>
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
            <h3>Edit Customer Detail</h3>
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
            $query = "SELECT * FROM customers WHERE id = ? LIMIT 0,1";
            $stmt = $con->prepare($query);

            // this is the first question mark
            $stmt->bindParam(1, $id);

            // execute our query
            $stmt->execute();

            // store retrieved row to a variable
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            // values to fill up our form
            $username = $row['username'];
            $oldpassword = $row['password'];
            $firstname = $row['firstname'];
            $lastname = $row['lastname'];
            $gender = $row['gender'];
            $date_of_birth = $row['date_of_birth'];
            $account_status = $row['account_status'];

            $store_image = "upload_customer/";
            if (!empty($row['image_cus']) && $row['image_cus'] != "NULL") {
                $old_image = $store_image . $row['image_cus'];
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
        if ($_POST) {
            $username = $_POST['username'];
            $uppercase = preg_match('@[A-Z]@', $_POST['new_pass']);
            $lowercase = preg_match('@[a-z]@', $_POST['new_pass']);
            $number    = preg_match('@[0-9]@', $_POST['new_pass']);

            $image = !empty($_FILES["image"]["name"])
                ? sha1_file($_FILES['image']['tmp_name']) . "-" . basename($_FILES["image"]["name"])
                : "NULL"; //pathinfo($old_image, PATHINFO_BASENAME);
            $image = htmlspecialchars(strip_tags($image));

            if (empty($image) && $image == "NULL") {
                $image = $store_image . "no_image.jpg";
            }
            // error message is empty
            $file_upload_error_messages = "";

            if (empty($_POST['username']) || empty($_POST['firstname']) || empty($_POST['lastname']) || empty($_POST['gender']) || empty($_POST['date_of_birth'])) {
                echo "<div class='alert alert-danger'>Please make sure all fields are not emplty!</div>";
            } else {
                if (!empty($_POST['old_pass']) || !empty($_POST['new_pass']) || !empty($_POST['con_pass'])) {
                    if (empty($_POST['old_pass'])) {
                        $file_upload_error_messages .= "<div>If want to change new passward then old password field can not be empty!</div>";
                    }

                    if (empty($_POST['new_pass'])) {
                        $file_upload_error_messages .= "<div>If want to change new passward then new password field can not be empty!</div>";
                    }

                    if (empty($_POST['con_pass'])) {
                        $file_upload_error_messages .= "<div>If want to change new passward then confirm password field can not be empty!</div>";
                    }

                    if (!empty($file_upload_error_messages)) {
                        echo "<div class='alert alert-danger'>";
                        echo "<div>{$file_upload_error_messages}</div>";
                        echo "</div>";
                    }
                }

                if (empty($file_upload_error_messages)) {
                    if (strlen($username) >= 6) {
                        if (strpos(trim($username), ' ')) {
                            $file_upload_error_messages .= "<div>Username should not contain whitespace!</div>";
                        }
                    } else {
                        $file_upload_error_messages .= "<div>Your username must contain at least 6 characters!</div>";
                    }
                    if (!empty($_POST['old_pass']) && md5($_POST['old_pass']) != $oldpassword) {
                        $file_upload_error_messages .= "<div>Wong old password same  your current password!</div>";
                    }

                    if (!empty($_POST['old_pass']) && strlen($_POST['new_pass']) <= 8) {
                        $file_upload_error_messages .= "<div>Your password must contain at least 8 characters!</div>";
                    }

                    if (!empty($_POST['old_pass']) && (!$uppercase || !$lowercase || !$number)) {
                        $file_upload_error_messages .= "<div>Your password must contain at least one uppercase, one lowercase and one number!</div>";
                    }

                    if (!empty($_POST['old_pass']) && $_POST['new_pass'] !== $_POST['con_pass']) {
                        $file_upload_error_messages .= "<div>Passwords do not match, please check again your new password or confirm password!</div>";
                    }

                    if (((int)date_diff(date_create(date('Y-m-d')), date_create($_POST["date_of_birth"]))->format("%R%y")) >= -18) {
                        $file_upload_error_messages .= "<div>You must be above 18 age old!</div>";
                    }

                    if (isset($_POST['images_remove']) && $_POST['images_remove'] != "" && !empty($_FILES['image']['name'])) {
                        //isset vs empty dash
                        $file_upload_error_messages .= "<div>Please note that you cannot select a new image while checking for image deletion, please select one.</div>";
                    }

                    if (!empty($_FILES["image"]["name"])) {
                        if ($image && $image != "NULL") {
                            // upload to file to folder
                            $target_directory = "upload_customer/";
                            $target_file = $target_directory . $image; // uploads/(image name)
                            $file_type = pathinfo($target_file, PATHINFO_EXTENSION); // find the image format like jpg, png ..
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
                    }


                    // check if form was submitted
                    if (!empty($file_upload_error_messages)) {
                        echo "<div class='alert alert-danger'>";
                        echo "<div>{$file_upload_error_messages}</div>";
                        echo "</div>";
                    } else {
                        try {
                            // write update query
                            // in this case, it seemed like we have so many fields to pass and
                            // it is better to label them and not use question marks
                            $query = "UPDATE customers
                        SET username=:username, password=:password,
                        firstname=:firstname, lastname=:lastname, 
                        gender=:gender, date_of_birth=:date_of_birth,
                        account_status=:account_status, image_cus=:image WHERE id=:id";
                            // prepare query for excecution
                            $stmt = $con->prepare($query);
                            // posted values
                            $username = htmlspecialchars(strip_tags($_POST['username']));
                            $firstname = htmlspecialchars(strip_tags($_POST['firstname']));
                            $lastname = htmlspecialchars(strip_tags($_POST['lastname']));
                            $gender = htmlspecialchars(strip_tags($_POST['gender']));
                            $date_of_birth = htmlspecialchars(strip_tags($_POST['date_of_birth']));
                            if (!empty($_POST['new_pass'])) {
                                $oldpassword =  md5(str_replace(" ", "", htmlspecialchars(strip_tags($_POST['new_pass']))));
                            }
                            $account_status = htmlspecialchars(strip_tags($_POST['account_status']));
                            // not checked and not upload new image
                            $flag_same_image = false;
                            if ($image == "NULL" && empty($_POST['images_remove'])) {
                                $image = pathinfo($old_image, PATHINFO_BASENAME);
                                $flag_same_image = true;
                                // checked and not upload new image
                            } else if ($image == "NULL" && !empty($_POST['images_remove'])) {
                                $image = "NULL";
                            }
                            // bind the parameters
                            $stmt->bindParam(':username', $username);
                            $stmt->bindParam(':firstname', $firstname);
                            $stmt->bindParam(':lastname', $lastname);
                            $stmt->bindParam(':gender', $gender);
                            $stmt->bindParam(':date_of_birth', $date_of_birth);
                            $stmt->bindParam(':password', $oldpassword);
                            $stmt->bindParam(':account_status', $account_status);
                            $stmt->bindParam(':id', $id);
                            $stmt->bindParam(':image', $image);
                            // Execute the query
                            if ($stmt->execute()) {
                                // if the image not same then remove previous one and not the default one
                                if (!$flag_same_image && !strpos($old_image, "no_image.jpg")) {
                                    unlink($old_image);
                                }

                                echo "<script type=\"text/javascript\"> window.location.href='customer_read.php?action=sucessful'</script>";
                            } else {
                                echo "<div class='alert alert-danger'>Unable to update record. Please try again.</div>";
                            }
                        }
                        // show errors
                        catch (PDOException $exception) {
                            die('ERROR: ' . $exception->getMessage());
                        }
                    }
                }
            }
        } ?>

        <!-- HTML form to update record will be here -->
        <!--we have our html form here where new record information can be updated-->
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"] . "?id={$id}"); ?>" method="post" enctype="multipart/form-data">
            <div class="table-responvise">
                <table class='table table-hover table-bordered'>
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
                        <td>Name</td>
                        <td><input type='text' name='username' value="<?php if (isset($_POST['username'])) {
                                                                            echo $_POST['username'];
                                                                        } else {
                                                                            echo htmlspecialchars($username, ENT_QUOTES);
                                                                        } ?>" class='form-control' /></td>
                    </tr>
                    <tr>
                        <td>Old password</td>
                        <td><input name='old_pass' class='form-control' type="password" placeholder="Please let it blank if you are not wanted to change a new password"></input></td>
                    </tr>
                    <tr>
                        <td>New password</td>
                        <td><input name='new_pass' class='form-control' type="password" placeholder="Please let it blank if you are not wanted to change a new password"></input></td>
                    </tr>
                    <tr>
                        <td>Confirm password</td>
                        <td><input name='con_pass' class='form-control' type="password" placeholder="Please let it blank if you are not wanted to change a new password"></input></td>
                    </tr>
                    <tr>
                        <td>First Name</td>
                        <td><input type='text' name='firstname' class='form-control' value="<?php if (isset($_POST['firstname'])) {
                                                                                                echo $_POST['firstname'];
                                                                                            } else {
                                                                                                echo htmlspecialchars($firstname, ENT_QUOTES);
                                                                                            }  ?>" /></td>
                    </tr>
                    <tr>
                        <td>Last Name</td>
                        <td><input type='text' name='lastname' class='form-control' value="<?php echo htmlspecialchars($lastname, ENT_QUOTES);  ?>" /></td>
                    </tr>
                    <tr>
                        <td>Gender</td>
                        <td class="d-flex align-item-center">
                            <input type="radio" name="gender" id="gender1" value="male" class="ms-1 mx-2" <?php if ($gender == "male") {
                                                                                                                echo "checked";
                                                                                                            } ?>>
                            <label for="gender1" class="me-4">Male</label>
                            <input type="radio" name="gender" id="gender2" value="female" class="ms-1 mx-2" <?php if ($gender == "female") {
                                                                                                                echo "checked";
                                                                                                            } ?>>
                            <label for="gender2">Female</label>
                        </td>
                    </tr>
                    <tr>
                        <td>Date of Birth</td>
                        <td><input type='date' name='date_of_birth' class='form-control' value="<?php echo htmlspecialchars($date_of_birth, ENT_QUOTES);  ?>" /></td>
                    </tr>
                    <tr>
                        <td>Account Status</td>
                        <td>

                            <input type="radio" name="account_status" value="active" id="active" class="ms-1 mx-2" <?php if (htmlspecialchars($account_status, ENT_QUOTES) == "active") {
                                                                                                                        echo "checked";
                                                                                                                    } ?>>
                            <label for="active" class="me-4">Active</label>
                            <input type="radio" name="account_status" value="inactive" id="inactive" class="ms-1 mx-2" <?php if (htmlspecialchars($account_status, ENT_QUOTES) == "inactive") {
                                                                                                                            echo "checked";
                                                                                                                        } ?>>
                            <label for="inactive">Inactive</label>
                        </td>
                    </tr>
                    <tr>
                        <td></td>
                        <td>
                            <input type='submit' value='Save Changes' class='btn btn-primary' />
                            <a href='customer_read.php' class='btn btn-danger'>Back to read customers</a>
                        </td>
                    </tr>
                </table>
            </div>
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