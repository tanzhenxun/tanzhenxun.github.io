<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sum</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-OERcA2EqjJCMA+/3y+gxIOqMEjwtxJY7qPCqsdltbNJuaOe923+mo//f6V8Qbsw3" crossorigin="anonymous"></script>
</head>

<body>
    <?php

    $sum = 0;

    for ($num = 1; $num <= 100; $num++) {
        if ($num !== 100) {
            if ($num % 2 == 0) {
                echo "<span class=\"fw-bold\">" . $num . "</span>";
            } else {
                echo "<span class=\"\">" . $num . "</span>";
            }
            echo " + ";
        } else {
            echo "<span class=\"fw-bold\">" . $num . "</span>";
            echo " = ";
        }
        $sum += $num;
    }
    echo "<span class=\"h1 text-primary\">" . $sum . "</span>";
    ?>
</body>

</html>