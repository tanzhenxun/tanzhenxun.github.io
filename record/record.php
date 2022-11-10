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