<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors">
    <meta name="generator" content="Hugo 0.104.2">
    <title>Resgiater</title>

    <script src="https://kit.fontawesome.com/f9f6f2f33c.js" crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/index.css">
</head>

<body>
    <div class="vh-100 login-bg">
        <div class="container-fluid py-5 h-100 ">
            <div class="row d-flex justify-content-center align-items-center h-100">
                <div class="col-12 col-lg-8">
                    <div class="card shadow-2-strong shadow" style="border-radius: 1rem;">
                        <div class="card-body p-sm-5 p-4 ">
                            <div class="text-center">
                                <img src="images/tanzxlogo.png" alt="Tanzx Logo" class="logo text-center">
                                <h5 class="mb-3 fw-bold">Register</h5>
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

                                // store retrieved row to a variable
                                $row = $stmt->fetch(PDO::FETCH_ASSOC);

                                // values to fill up our form
                                $oldusername = $row['username'];
                                $arrayoldusername = array($oldusername);
                            }

                            // show error
                            catch (PDOException $exception) {
                                die('ERROR: ' . $exception->getMessage());
                            }

                            ?>
                            <?php
                            if ($_POST) {
                                include 'config/database.php';
                                $username = $_POST['username'];
                                $password = $_POST['password'];
                                $firstname = $_POST['firstname'];
                                $lastname = $_POST['lastname'];
                                $date_of_birth = $_POST['date_of_birth'];
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

                                if ($username == "" || $password == "" || $confirm_password == "" || $firstname == "" || $lastname == ""  || $date_of_birth == "" || empty($_POST['gender'])) {
                                    echo "<div class='alert alert-danger'>Please make sure have * column are not emplty!</div>";
                                } else {
                                    if (strlen($username) >= 6) {
                                        if (strpos(trim($username), ' ')) {
                                            $file_upload_error_messages .= "<div>Username should not contain whitespace!</div>";
                                        } else {
                                            for ($i = 0; $i < count($arrayoldusername); $i++) {
                                                if ($arrayoldusername[$i] == $username) {
                                                    $file_upload_error_messages .= "<div>Username already exists! Please type again</div>";
                                                } else {
                                                    $username = $_POST['username'];
                                                }
                                            }
                                        }
                                    } else {
                                        $file_upload_error_messages .= "<div>Your username must contain at least 6 characters!</div>";
                                    }

                                    if (strlen($password) >= 8) {
                                        if ($uppercase || $lowercase || $number) {
                                            if ($password !== $confirm_password) {
                                                $file_upload_error_messages .= "<div>Passwords do not match, please type again.</div>";
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
                                            $account_status = 'active';

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
                                                header('Location:login.php?action=sucessful');
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
                            <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="POST" enctype="multipart/form-data">
                                <div class="row mb-3 pt-3">
                                    <div class="form-outline text-start col-sm-6 col-12">
                                        <label for="username" class="fw-semibold"><div class="d-flex">Username <div class="text-danger">*</div></div></label>
                                        <input type="type" id="username" name="username" class="form-control form-control-md" value="<?php if (isset($_POST['username'])) {
                                                                                                                                            echo $_POST['username'];
                                                                                                                                        } else {
                                                                                                                                            echo "";
                                                                                                                                        } ?>" />
                                    </div>
                                    <div class="form-outline text-start col-sm-6 col-12">
                                        <label for="image" class="fw-semibold">Photo</label>
                                        <input type='file' id='image' name='image' class="form-control form-control-md" />
                                    </div>
                                </div>
                                <div class="row mb-3 ">
                                    <div class="form-outline text-start col-sm-6 col-12">
                                        <label for="firstname" class="fw-semibold"><div class="d-flex">First Name <div class="text-danger">*</div></div></label>
                                        <input type="type" id="firstname" name="firstname" class="form-control form-control-md" value="<?php if (isset($_POST['firstname'])) {
                                                                                                                                            echo $_POST['firstname'];
                                                                                                                                        } else {
                                                                                                                                            echo "";
                                                                                                                                        } ?>" />
                                    </div>
                                    <div class="form-outline text-start col-sm-6 col-12 mt-3 mt-sm-0">
                                        <label for="lastname" class="fw-semibold"><div class="d-flex">Last Name <div class="text-danger">*</div></div></label>
                                        <input type="type" id="lastname" name="lastname" class="form-control form-control-md" value="<?php if (isset($_POST['lastname'])) {
                                                                                                                                            echo $_POST['lastname'];
                                                                                                                                        } else {
                                                                                                                                            echo "";
                                                                                                                                        } ?>" />
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="form-outline text-start col-sm-6 col-12">
                                        <label for="password" class="fw-semibold"><div class="d-flex">Psassword <div class="text-danger">*</div></div></label>
                                        <input type="password" id="password" name="password" class="form-control form-control-md" />
                                    </div>
                                    <div class="form-outline  text-start col-sm-6 col-12 mt-3 mt-sm-0">
                                        <label for="confirm_password" class="fw-semibold"><div class="d-flex">Confirm Password <div class="text-danger">*</div></div></label>
                                        <input type="password" id="confirm_password" name="confirm_password" class="form-control form-control-md" />
                                    </div>
                                </div>
                                <div class="row mb-4">
                                    <div class="form-outline text-start col-sm-6 col-12">
                                        <label for="date_of_birth" class="fw-semibold"><div class="d-flex">Date of Birth <div class="text-danger">*</div></div></label>
                                        <input type="date" id="date_of_birth" name="date_of_birth" class="form-control form-control-md" value="<?php if (isset($_POST['date_of_birth'])) {
                                                                                                                                                    echo $_POST['date_of_birth'];
                                                                                                                                                } else {
                                                                                                                                                    echo "";
                                                                                                                                                } ?>" />
                                    </div>
                                    <div class="form-outline  text-start col-sm-6 col-12 mt-3 mt-sm-0">
                                        <div class="fw-semibold"> <div class="d-flex">Gender <div class="text-danger">*</div></div></div>
                                        <div class="mt-2 me-2">
                                            <input type="radio" name="gender" value="male" id="male" <?php
                                                                                                        if (isset($_POST['gender'])) {
                                                                                                            if ($_POST['gender'] == "male") {
                                                                                                                echo "checked";
                                                                                                            }
                                                                                                        }  ?>>
                                            <label for="male" class="me-4">Male</label>
                                            <input type="radio" name="gender" value="female" id="female" <?php
                                                                                                            if (isset($_POST['gender'])) {
                                                                                                                if ($_POST['gender'] == "female") {
                                                                                                                    echo "checked";
                                                                                                                }
                                                                                                            }  ?>>
                                            <label for="female">Female</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="text-center text-sm-start">
                                    <button type="submit" class="btn btn-secondary btn-md btn-block">Register</button>

                                    <div class="mt-3 mb-3 text-muted">Have already an account? <a href="index.php" class=" text-dark">Login here</a></div>

                                    <div class="mt-3 text-muted">&copy; 2022 TANZX</div>
                                </div>
                            </form>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-OERcA2EqjJCMA+/3y+gxIOqMEjwtxJY7qPCqsdltbNJuaOe923+mo//f6V8Qbsw3" crossorigin="anonymous"></script>
</body>

</html>