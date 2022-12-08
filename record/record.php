<?php
        // if ($_POST) {
        //     include 'config/database.php';
        //     $customer_id = $_POST['customerSelect'];
        //     $flag = 0;
        //     if ($customer_id == "NULL") {
        //         $flag = 1;
        //     } else {

        //         $query = "SELECT order_summary_id From order_summary Order by order_summary_id desc limit 1";
        //         $stmt = $con->prepare($query);
        //         $stmt->execute();
        //         $row = $stmt->fetch(PDO::FETCH_ASSOC);
        //         $order_summary_id = $row['order_summary_id'];
        //         echo $order_summary_id;


        //         if ($flag == 0) {


        //             $query = "SELECT  *
        //             FROM order_detail
        //             LEFT JOIN products
        //             ON order_detail.product_id = products.id
        //         UNION ALL
        //             SELECT *
        //             FROM products
        //             RIGHT JOIN order_detail
        //             ON products.id = order_detail.product_id
        //             Order by order_detail_id desc limit 3";
        //             //Select Max(orderid) From orders

        //             $stmt = $con->prepare($query);
        //             $stmt->execute();

        //             $num = $stmt->rowCount();

        //             echo "<thead>";
        //             echo "<tr>";
        //             echo "<th scope=\"col\">No</th>";
        //             echo "<th scope=\"col\">Product name</th>";
        //             echo "<th scope=\"col\">Quantity</th>";
        //             echo "<th scope=\"col\">Price</th>";
        //             echo "<th scope=\"col\">Total</th>";
        //             echo "</tr>";
        //             echo "</thead>";
        //             echo "<tbody>";
        //             $count = 1;
        //             if ($num > 0) {
        //                 while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        //                     // show product 1
        //                     echo "<tr>";
        //                     echo "<th scope=\"row\">$count</th>";
        //                     // show product name
        //                     echo "<td>";
        //                     echo "<div class=\"mb-3 col\">";
        //                     echo $row["name"];
        //                     echo "</div>";
        //                     echo "</td>";
        //                     // show product quantity
        //                     echo "<td>";
        //                     echo "<div class=\"mb-3 col\">";
        //                     echo $row["quantity"];
        //                     echo "</div>";
        //                     echo "</td>";
        //                     //show product price
        //                     echo "<td>";
        //                     echo "<div class=\"mb-3 col\">";
        //                     echo $row["price"];
        //                     echo "</div>";
        //                     echo "</td>";
        //                     //show total
        //                     echo "<td>";
        //                     echo "<div class=\"mb-3 col\">";
        //                     $total = $row["quantity"] * $row["price"];
        //                     echo $total;
        //                     echo "</div>";
        //                     echo "</td>";
        //                     $count++;
        //                 }
        //             }
        //             echo "</tbody>";
        //             echo "</table>";
        //         }
        //     }
        // }
        ?>
        <!-- </table> -->

        //record login page
        https://www.simplilearn.com/tutorials/php-tutorial/php-login-form


        <?php
        //sessions // connect muti page same information 
        
        ?>

        <?php 
        // full join
        // $query = "SELECT smry.order_summary_id, cus.username, smry.order_date
        // FROM order_summary AS smry
        // LEFT JOIN customers AS cus
        // ON smry.customer_id = cus.id
        // UNION 
        // SELECT smry.order_summary_id, cus.username, smry.order_date
        // FROM customers AS cus
        // RIGHT JOIN order_summary AS smry
        // ON cus.id = smry.customer_id
        // Order by order_summary_id DESC";
        ?>

<div class="d-sm-flex container align-items-center justify-content-evenly py-sm-5 py-2 mb-sm-5 mb-2 flex-sm-row flex-column text-center">
        <a href="product_create" class="text-decoration-none buttoncolor text-dark"><button class="button-30 m-sm-0 m-2" role="button">Create Product</button></a>
        <a href="customer_create" class="text-decoration-none buttoncolor text-dark"><button class="button-30 m-sm-0 m-2" role="button">Create Customer</button></a>
        </div>


        <?php
        
        
        
        $path_parts = pathinfo('/www/htdocs/inc/create.php');

        echo $path_parts['dirname'], "\n";// echo /www/htdocs/inc //directory name
        echo $path_parts['basename'], "\n";// echo create.php
        echo $path_parts['extension'], "\n";// echo php
        echo $path_parts['filename'], "\n";// echo create
        // Answer
        // /www/htdocs/inc
        // lib.inc.php
        // php
        // lib.inc
       // array 排屋 里面有什么
       

        
        ?>