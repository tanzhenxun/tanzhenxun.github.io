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
            $password_ = $row['password'];
            $firstname = $row['firstname'];
            $lastname = $row['lastname'];
            $gender = $row['gender'];
            $date_of_birth =$row['date_of_birth'];
            $account_status = $row['account_status'];
        }

        // show error
        catch (PDOException $exception) {
            die('ERROR: ' . $exception->getMessage());
        }
        ?>


        <!-- PHP post to update record will be here -->
        <?php
            if ($username == "" || $password == "" || $confirm_password == "" || $firstname == "" || $lastname == "" || $account_status == "" || $gender == "") {
                $flag = 1;
                echo "<div class='alert alert-danger'>Please make sure all fields are not emplty!</div>";
            } 

        // check if form was submitted
        if ($_POST) {
            try {
                // write update query
                // in this case, it seemed like we have so many fields to pass and
                // it is better to label them and not use question marks
                $query = "UPDATE customer
                SET username=:username, password=:password,
                firstname=:firstname, lastname=:lastname, 
                gender=:gender, date_of_birth=:date_of_birth,
                account_status=:account_status WHERE id=:id";
                // prepare query for excecution
                $stmt = $con->prepare($query);
                // posted values
                $username = htmlspecialchars(strip_tags($_POST['username']));
                $firstname = htmlspecialchars(strip_tags($_POST['firstname']));
                $lastname = htmlspecialchars(strip_tags($_POST['lastname']));
                $gender = htmlspecialchars(strip_tags($_POST['gender']));
                $date_of_birth = htmlspecialchars(strip_tags($_POST['date_of_birth']));


                // bind the parameters
                $stmt->bindParam(':name', $name);
                $stmt->bindParam(':description', $description);
                $stmt->bindParam(':price', $price);
                $stmt->bindParam(':id', $id);
                // Execute the query
                if ($stmt->execute()) {
                    echo "<div class='alert alert-success'>Record was updated.</div>";
                } else {
                    echo "<div class='alert alert-danger'>Unable to update record. Please try again.</div>";
                }
            }
            // show errors
            catch (PDOException $exception) {
                die('ERROR: ' . $exception->getMessage());
            }
        } ?>

        <!-- HTML form to update record will be here -->
        <!--we have our html form here where new record information can be updated-->
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"] . "?id={$id}"); ?>" method="post">
            <table class='table table-hover table-responsive table-bordered'>
                <tr>
                    <td>Name</td>
                    <td><input type='text' name='username' value="<?php echo htmlspecialchars($username, ENT_QUOTES);  ?>" class='form-control' /></td>
                </tr>
                <tr>
                    <td>Old password</td>
                    <td><input name='old_pass' class='form-control'></input></td>
                </tr>
                <tr>
                    <td>New password</td>
                    <td><input name='new_pass' class='form-control'></input></td>
                </tr>
                <tr>
                    <td>Confirm password</td>
                    <td><input name='con_pass' class='form-control'></input></td>
                </tr>
                <tr>
                    <td>First Name</td>
                    <td><input type='text' name='firstname' class='form-control' value="<?php echo htmlspecialchars($firstname, ENT_QUOTES);  ?>"/></td>
                </tr>
                <tr>
                    <td>Last Name</td>
                    <td><input type='text' name='lastname' class='form-control' value="<?php echo htmlspecialchars($lastname, ENT_QUOTES);  ?>" /></td>
                </tr>
                <tr>
                    <td>Gender</td>
                    <td class="d-flex align-item-center">
                        <input type="radio" name="gender" value="<?php $mactive = 0; if( htmlspecialchars($gender, ENT_QUOTES) == "male"){$mactive = 1; echo"male";} ?>" class="ms-1 mx-2" <?php if($mactive == 1){echo"checked";} ?>>
                        <label for="gender" class="me-4">Male</label>
                        <input type="radio" name="gender" value="<?php $factive = 0; echo htmlspecialchars($gender, ENT_QUOTES); if($gender == "female"){$factive = 1;echo"female";} ?>" class="ms-1 mx-2" <?php if(htmlspecialchars($factive, ENT_QUOTES) == 1){echo"checked";} ?>>
                        <label for="gender">Female</label>
                    </td>
                </tr>
                <tr>
                    <td>Date of Birth</td>
                    <td><input type='date' name='date_of_birth' class='form-control' value="<?php echo htmlspecialchars($date_of_birth, ENT_QUOTES);  ?>"/></td>
                </tr>
                <tr>
                    <td>Account Status</td>
                    <td>
                        <select class="form-select" aria-label="Default select example" name="account_status">
                            <option value="active" <?php if( htmlspecialchars($account_status, ENT_QUOTES) == "active"){echo"selected";} ?>>Active</option>
                            <option value="inactive" <?php if( htmlspecialchars($account_status, ENT_QUOTES) == "inactive"){echo"selected";} ?>>Inactive</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td>
                        <input type='submit' value='Save Changes' class='btn btn-primary' />
                        <a href='product_read.php' class='btn btn-danger'>Back to read customers</a>
                    </td>
                </tr>
            </table>
        </form>

    </div>
    <!-- end .container -->
</body>

</html>