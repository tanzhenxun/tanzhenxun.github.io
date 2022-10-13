<!DOCTYPE html>
<html>

<head>
    <title>Random Number</title>
    <link rel="stylesheet" href="css/random_num.css">

</head>

<body>

    <?php
    $num1 = rand(100,200);
    $num2 = rand(100,200);

        echo "<p class= 'num1'>". $num1 ."</p> ";
        echo "<p class= 'num2'>". $num2 ."</p> ";
        echo "<p class= 'num3'>". ($num1 + $num2) ."</p> ";
        echo "<p class= 'num4'>". ($num1 * $num2) ."</p> ";
    ?>


    

</body>

</html>