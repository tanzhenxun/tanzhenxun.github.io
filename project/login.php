<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors">
    <meta name="generator" content="Hugo 0.104.2">
    <title>Login</title>

    <script src="https://kit.fontawesome.com/f9f6f2f33c.js" crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
    <link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/index.css">
</head>

<body>
    <div class="vh-100 login-bg">
        <div class="container py-5 h-100">
            <div class="row d-flex justify-content-center align-items-center h-100">
                <div class="col-12 col-md-8 col-lg-6 col-xl-5">
                    <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="POST">
                        <div class="card shadow-2-strong shadow" style="border-radius: 1rem;">
                            <div class="card-body p-5 text-center">
                                <a href="home.php"><img src="images/tanzxlogo.png" alt="Tanzx Logo" class="logo al"></a>
                                <h3 class="mb-3">Sign in</h3>
                                <?php
                                session_start();
                                $action = isset($_GET['action']) ? $_GET['action']  : "";

                                if($action =="logout"){
                                    echo "<div class='alert alert-success'>You have been logged out successfully.</div>";
                                }
                                if($action =="logincheck"){
                                    echo "<div class='alert alert-danger'>Please login again!</div>";
                                }

                                if($action =="sucessful"){
                                    echo "<div class='alert alert-success'>You account has been successfully created!</div>";
                                }
                                
                                
                               // $error = isset($_GET['error']) ? $_GET['error'] : NULL;
                               // if ($error == Please login again ){echo "<div class='alert alert-dark'>Please login again</div>"}
                                if ($_POST) {
                                    include 'config/database.php';
                                    $username = $_POST['username'];
                                    $pass = $_POST['password'];
                                    

                                    // Check username and password have empty or not 
                                    if (empty($_POST['username']) && empty($_POST['password'])) {
                                        echo "<div class='alert alert-danger'>Username and password make sure are not emplty!</div>";
                                    } else { // Run and check the username, password and account status correct or active.
                                        // select all data
                                        // username must correct then check the password
                                        $query = "SELECT * FROM customers where username=:username";
                                        $stmt = $con->prepare($query);
                                        // bind the parameters
                                        $stmt->bindParam(":username", $username);
                                        $stmt->execute();

                                        // store retrieved row to a variable
                                        $num = $stmt->rowCount();

                                        if ($num > 0) {
                                            $row = $stmt->fetch(PDO::FETCH_ASSOC);
                                            extract($row);
                                            if (md5($pass) == $password) {
                                                if ($account_status == "active") { // === check the datatype and value like "1" check string(datatype) and 1(value) (== check value like check "1" just 1 (value))
                                                    header("Location: home.php");
                                                    $_SESSION['login'] = true;
                                                } else {
                                                    echo "<div class='alert alert-danger'>Your Account is suspended</div>";
                                                }
                                            } else {
                                                echo "<div class='alert alert-danger'>Incorrect Password</div>";
                                            }
                                        } else {
                                            echo "<div class='alert alert-danger'>User not found (Invalid Account)!</div>";
                                        }
                                    }
                                }
                                ?>

                                <div class="form-outline mb-4 pt-3">
                                    <input type="type" id="username" name="username" class="form-control form-control-lg" placeholder="Username" />
                                </div>

                                <div class="form-outline mb-4">
                                    <input type="password" id="typePasswordX-2" name="password" class="form-control form-control-lg" placeholder="Password" />
                                </div>

                                <button type="submit" class="btn btn-secondary btn-lg btn-block">Sign in</button>
                                
                                <div class="mt-3 mb-3 text-muted">Don't have an account? <a href="register.php" class=" text-dark">Signup now</a></div>

                                <p class="mt-3 mb-3 text-muted">&copy; 2022 TANZX</p>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-OERcA2EqjJCMA+/3y+gxIOqMEjwtxJY7qPCqsdltbNJuaOe923+mo//f6V8Qbsw3" crossorigin="anonymous"></script>
</body>

</html>