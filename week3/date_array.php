<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Date of Birth</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-OERcA2EqjJCMA+/3y+gxIOqMEjwtxJY7qPCqsdltbNJuaOe923+mo//f6V8Qbsw3" crossorigin="anonymous"></script>
</head>

<body>
    <h1 class ="fw-bold text-center">What is your date of birth?</h1>
    <div class="row container m-auto">
    <select class="bg-info form-select form-select-md col">
    <option value="Date">Date</option>
        <?php
        for($date = 1; $date <= 31; $date++){
            echo "<option value = $date>$date</option>";
        }
        ?>
    </select>

    <select class="bg-warning form-select form-select-md col">
    <option value="Month">Month</option>
        <?php
        $month = array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");
        for($countmounth = 1; $countmounth <= 12; $countmounth++){
            $count = $countmounth-1;
            echo "<option value = $month>".$month[$count]."</option>";
        }
        ?>
    </select>

    <select class="bg-danger form-select form-select-md col">
    <option value="Year">Years</option>
        <?php
        for($year = 1900; $year <= 2022; $year++){
            echo "<option value = $year>$year</option>";
        }
        ?>
    </select>
    
    </div>

</body>

</html>