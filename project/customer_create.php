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

            $uppercase = preg_match('@[A-Z]@', $password);
            $lowercase = preg_match('@[a-z]@', $password);
            $number    = preg_match('@[0-9]@', $password);
            $flag = 0;

            if ($username == "" || $password == "" || $confirm_password == "" || $firstname == "" || $lastname == "" || $account_status == "") {
                $flag = 1;
                echo "<div class='alert alert-danger'>Please make sure all fields are not emplty!</div>";
            } else {
                if (strlen($username) >= 6) {
                    if (strpos(trim($username), ' ')) {
                        $flag = 1;
                        echo "<div class='alert alert-danger'>Username should not contain whitespace!</div>";
                    }else{
                        $flag = 0;
                        $username = $_POST['username'];
                    }
                } else {
                    $flag = 1;
                    echo "<div class='alert alert-danger'>Your username must contain at least 6 characters!</div>";
                }

                if (strlen($password) >= 8) {
                    if ($uppercase || $lowercase || $number) {
                        if ($password !== $confirm_password) {
                            $flag = 1;
                            echo "<div class='alert alert-danger'>Passwords do not match, please type again.</div>";
                        } else{
                            $password = md5($_POST['password']);
                        }
                    }else{
                        $flag = 1;
                        echo "<div class='alert alert-danger'>Your password must contain at least one uppercase, one lowercase and one number!</div>";
                    }
                }else{
                    $flag = 1;
                    echo "<div class='alert alert-danger'>Your password must contain at least 8 characters!</div>";
                }

                

                $now_date = date('Y-m-d');
                $diff = date_diff(date_create($now_date),date_create($date_of_birth));
                $year = (int)$diff->format("%R%y");

                if ($year >= -18){
                    $flag = 1;
                    echo "<div class='alert alert-danger'>You must be above 18 age old!</div>";
                } else{
                    $flag = 0;
                    $date_of_birth = $_POST['date_of_birth'];
                }


                if ($flag == 0) {
                    // include database connection
                    try {
                        // insert query
                        $query = "INSERT INTO customers SET username=:username, password=:password, firstname=:firstname, lastname=:lastname, gender=:gender, date_of_birth=:date_of_birth, account_status=:account_status, register_date=:register_date";
                        // prepare query for execution
                        $stmt = $con->prepare($query);
                        $gender = $_POST['gender'];
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
        <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="POST">
            <table class='table table-hover table-responsive table-bordered'>
                <tr>
                    <td>Username</td>
                    <td><input type='text' name='username' class='form-control' /></td>
                </tr>
                <tr>
                    <td>First Name</td>
                    <td><input type='text' name='firstname' class='form-control' /></td>
                </tr>
                <tr>
                    <td>Last Name</td>
                    <td><input type='text' name='lastname' class='form-control' /></td>
                </tr>
                <tr>
                    <td>Password</td>
                    <td><input type='password' name='password' class='form-control' /></td>
                </tr>
                <tr>
                    <td>Confirm Password</td>
                    <td><input type='password' name='confirm_password' class='form-control' /></td>
                </tr>
                <tr>
                    <td>Gender</td>
                    <td class="d-flex align-item-center">
                        <input type="radio" name="gender" value="male" class="ms-1 mx-2">
                        <label for="gender" class="me-4">Male</label>
                        <input type="radio" name="gender" value="female" class="ms-1 mx-2">
                        <label for="gender">Female</label>
                    </td>
                </tr>
                <tr>
                    <td>Date of Birth</td>
                    <td><input type='date' name='date_of_birth' class='form-control' /></td>
                </tr>
                <tr>
                    <td>Account Status</td>
                    <td>
                        <select class="form-select" aria-label="Default select example" name="account_status">
                            <option value="active" selected>Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" class="text-center">
                        <input type='submit' value='Save' class='btn btn-primary' />
                        <a href='index.php' class='btn btn-danger'>Back to read customer</a>
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