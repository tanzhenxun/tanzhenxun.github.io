<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Random Number Color</title>
    <script src="https://kit.fontawesome.com/f9f6f2f33c.js" crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
</head>

<body>      
    <?php
        $num1 = rand(100, 200);
        $num2 = rand(100, 200);

        if ($num1 > $num2) {
            echo "<div class='container bg-primary mt-3'><h1 class= 'font-weight-bold'>" . $num1 . "</h1></div> ";
            echo "<div class='container bg-danger'><p>" . $num2 . "</p></div> ";
        } else if ($num2 > $num1 ){
            echo "<div class='container bg-danger mt-3'><p>" . $num2 . "</p></div> ";
            echo "<div class='container bg-primary'><h1 class= 'font-weight-bold'>" . $num1 . "</h1></div> ";
        } else {
            echo "<div class='container bg-warning mt-3'><p>" . $num2 . "</p></div> ";
            echo "<div class='container bg-warning'><p>" . $num1 . "</p></div> ";
        }
        ?>



    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-OERcA2EqjJCMA+/3y+gxIOqMEjwtxJY7qPCqsdltbNJuaOe923+mo//f6V8Qbsw3" crossorigin="anonymous"></script>
</body>

</html>