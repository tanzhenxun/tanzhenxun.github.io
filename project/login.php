<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors">
    <meta name="generator" content="Hugo 0.104.2">
    <title>Signin Template · Bootstrap v5.2</title>

    <script src="https://kit.fontawesome.com/f9f6f2f33c.js" crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
    <link rel="stylesheet" href="css/index.css">

    <!-- Custom styles for this template -->
    <link href="css/index.css" rel="stylesheet">
</head>

<body>
    <div class="vh-100 login-bg">
        <div class="container py-5 h-100">
            <div class="row d-flex justify-content-center align-items-center h-100">
                <div class="col-12 col-md-8 col-lg-6 col-xl-5">
                    <?php

                    if ($_POST) {
                        include 'config/database.php';
                        $username = $_POST['username'];
                        $password = $_POST['password'];
                        $user_correct = true;
                        $pass_correct = true;
                        $account_check = true;

                        if (empty($username)) {
                            header("Location: login.php?error=User Name is required");
                            $user_correct = false;
                        }

                        if (empty($password)) {
                            header("Location: login.php?error=Password is required");
                            $pass_correct = false;
                        }

                        if ($user_correct == true || $pass_correct == true) {
                            $query = "SELECT username, password, account_status FROM customers where username=:username AND password=:password";
                            $stmt = $con->prepare($query);

                            $stmt->bindParam(":username", $username);
                            $stmt->bindParam(":password", $password);

                            $stmt->execute();

                            $row = $stmt->fetch(PDO::FETCH_ASSOC);

                            $username = $row['username'];
                            $password = $row['password'];
                            $account_status = $row['account_status'];

                            if ($account_status === "active"){
                                $account_check = true;
                            }else{
                                $account_check = false;
                                header("Location: login.php?error=Your Account is suspended");
                            }

                            if ($username && $password) {
                                if($account_check == true){
                                    header("Location: home.php");
                                }
                            } else {
                                header("Location: login.php?error=Invaild Username or password");
                            }
                        }
                    }
                    ?>
                    <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="POST">
                        <div class="card shadow-2-strong shadow" style="border-radius: 1rem;">
                            <div class="card-body p-5 text-center">

                                <h3 class="mb-5">Sign in</h3>

                                <?php if (isset($_GET['error'])) { ?>

                                    <div class='alert alert-danger'><?php echo $_GET['error']; ?></div>

                                <?php } ?>
                                <div class="form-outline mb-4">
                                    <input type="type" id="username" name="username" class="form-control form-control-lg" placeholder="Username" />
                                </div>

                                <div class="form-outline mb-4">
                                    <input type="password" id="typePasswordX-2" name="password" class="form-control form-control-lg" placeholder="Password" />
                                </div>

                                <!-- Checkbox -->
                                <div class="form-check d-flex justify-content-start mb-4">
                                    <input class="form-check-input me-2" type="checkbox" value="" id="form1Example3" />
                                    <label class="form-check-label" for="form1Example3"> Remember password </label>
                                </div>

                                <button type="submit" class="btn btn-secondary btn-lg btn-block">Submit</button>

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