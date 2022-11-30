<?php 
$web_name = basename($_SERVER["PHP_SELF"]);
?>
<nav class="navbar navbar-expand-lg navbar border-dark border-bottom border-2">
    <div class="container-fluid m-auto nav-size align-items-center justify-content-between">
        <div class="d-flex align-items-center ">
            <a href="home.php"><img src="images/tanzxlogo.png" alt="Tanzx Logo" class="logo al"></a>
            <a class="navbar-brand fw-bold fs-5" href="home.php">TANZX</a>
        </div>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse justify-content-between align-items-center" id="navbarNavAltMarkup">
            <hr>
            <div class="navbar-nav text-black text-lg-center text-start">
                <a class="nav-link <?php if($web_name =="home.php"){echo "active";}?>" aria-current="page" href="home.php">Home</a>
                
                <div class="dropdown">
                    <a class="text-decoration-none nav-link dropdown-toggle <?php if($web_name == "product_create.php"|| $web_name == "product_read.php" || $web_name == "product_read_one.php"){echo "active";}?>" href="product_create.php" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Product
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item <?php if($web_name=="product_create.php"){echo "active";}?>" href="product_create.php">Create Product</a></li>
                        <li><a class="dropdown-item <?php if($web_name=="product_read.php"){echo "active";}?>" href="product_read">Product List</a></li>
                    </ul>
                </div>
                <div class="dropdown">
                    <a class="text-decoration-none nav-link dropdown-toggle <?php if($web_name== "customer_create.php" || $web_name == "customer_read.php" || $web_name == "customer_read_one.php"){echo "active";}?>" href="customer_create.php" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Customer
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item <?php if($web_name=="customer_create.php"){echo "active";}?>" href="customer_create.php">Create Customer</a></li>
                        <li><a class="dropdown-item <?php if($web_name=="customer_read.php"){echo "active";}?>" href="customer_read.php">Customer Detail</a></li>
                    </ul>
                </div>
                <div class="dropdown">
                    <a class="text-decoration-none nav-link dropdown-toggle <?php if($web_name=="order_create.php" || $web_name == "order_read.php" || $web_name == "order_read_one.php"){echo "active";}?>" href="order_create.php" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Order
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item <?php if($web_name=="order_create.php"){echo "active";}?>" href="order_create.php">Create Order</a></li>
                        <li><a class="dropdown-item <?php if($web_name=="order_read.php"){echo "active";}?>" href="order_read.php">Order List</a></li>
                    </ul>
                </div>
                
                <a class="nav-link <?php if($web_name=="contact.php"){echo "active";}?>" href="contact.php">Contact Us</a>
            </div>
            <div class="navbar-brand d-flex align-center">
                <a href="https://github.com/tanzhenxun" class=" text-dark me-3"><i class="fa-brands fa-github fa-2x"></i></a>
                <a href="https://www.instagram.com/tan315_x18/?hl=en" class=" text-dark me-3"><i class="fa-brands fa-instagram fa-2x"></i></a>
                <a href="logout.php" class="btn btn-dark">Logout</a>
            </div>
        </div>
    </div>
</nav>