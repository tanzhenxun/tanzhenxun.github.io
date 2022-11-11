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
    <link href="css/index.css" rel="stylesheet">
</head>

<body>
    <!-- container -->
    <?php
    include 'navtop.php';
    ?>
    <div class="bg-light bg-images py-5 full_page">
        <div class="d-flex container align-items-center justify-content-center py-5 mt-5">
            <!--<div class="header_images">
                <img src="images/Web-Development-Illustration.svg" alt="" >
            </div>-->
            <div class="header-text text-center">                
                <h1>Sample Product</h1>
                <p>Please fill in product or customer information.</p>                
            </div>
        </div>
        <div class="d-sm-flex container align-items-center justify-content-evenly py-sm-5 py-2 mb-sm-5 mb-2 flex-sm-row flex-column text-center">
        <a href="product_create" class="text-decoration-none buttoncolor text-dark"><button class="button-30 m-sm-0 m-2" role="button">Create Product</button></a>
        <a href="customer_create" class="text-decoration-none buttoncolor text-dark"><button class="button-30 m-sm-0 m-2" role="button">Create Customer</button></a>
        </div>
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