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
    <link rel="stylesheet" href="css/index.css">
</head>

<body>
    <!-- container -->
   <?php 
   include 'navtop.php';
   ?>
    <div class="bg-images full_page">
        <div class="bg-color-contact">
            <div class="container text-center py-5">
                <h1 class="fw-bold">Contact Us</h1>
            </div>
        </div>
        <div class="container">
            <div class="row pb-5">
                <div class="col-12">
                    <h2 class="contact-title fs-3 my-3">Get in Touch</h2>
                </div>
                <div class="col-lg-8">
                    <form class="form-contact contact_form" action="contact_process.php" method="post" id="contactForm" novalidate>
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group my-2">
                                    <textarea class="form-control w-100" name="message" id="message" cols="30" rows="9" onfocus="this.placeholder = ''" onblur="this.placeholder = 'Enter Message'" placeholder='Enter Message'></textarea>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group my-2">
                                    <input class="form-control" name="name" id="name" type="text" onfocus="this.placeholder = ''" onblur="this.placeholder = 'Enter your name'" placeholder='Enter your name'>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group my-2">
                                    <input class="form-control" name="email" id="email" type="email" onfocus="this.placeholder = ''" onblur="this.placeholder = 'Enter email address'" placeholder='Enter email address'>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group my-2">
                                    <input class="form-control" name="subject" id="subject" type="text" onfocus="this.placeholder = ''" onblur="this.placeholder = 'Enter Subject'" placeholder='Enter Subject'>
                                </div>
                            </div>
                        </div>
                        <div class="form-group mt-3 rounded">
                            <a href="#" class="btn_3 button-contactForm rounded py-2 px-3 text-decoration-none fw-bold">Send Message</a>
                        </div>
                    </form>
                </div>
                <div class="col-lg-4">
                    <div class="card mt-lg-2 mt-4 px-3 pb-lg-4 pt-3 pb-3">
                        <div class="media contact-info d-flex align-item-center">
                            <span class="contact-info__icon mt-2 me-2"><i class="fa-solid fa-building fa-2x"></i></span>
                            <div class="media-body">
                                <h3 class="fs-5">New Era University</h3>
                                <p>43000 Kajang, Selangor</p>
                            </div>
                        </div>
                        <div class="media contact-infod-flex d-flex align-item-center">
                            <span class="contact-info__icon mt-2 me-2"><i class="fa-solid fa-mobile-screen-button fa-2x"></i></span>
                            <div class="media-body">
                                <a href="tel:+60 16-3729603" class="text-decoration-none">
                                    <h3 class="fs-5 text-dark">016-7329603</h3>
                                </a>
                                <p>Mon to Fri 8:30am to 5:30pm</p>
                            </div>
                        </div>
                        <div class="media contact-info d-flex align-item-center">
                            <span class="contact-info__icon mt-2 me-2 email-contact"><i class="fa-solid fa-paper-plane"></i></span>
                            <div class="media-body ">
                                <a href="mailto:tanzhenxun1118@e.newera.edu.my" class="text-decoration-none">
                                    <h3 class="fs-5 text-dark text-break">tanzhenxun1118@e.newera.edu.my</h3>
                                </a>
                                <p>Send us your query anytime!</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <footer class="container-fluid py-3 bg-dark">
        <div class="m-auto foot-size d-sm-flex d-block justify-content-between text-white">
            <div>Copyright @ 2022 TANZX</div>
            <div class="d-flex">
                <div class="mx-3">Terms of Use</div>
                <div class="mx-3">Privacy Policy</div>
            </div>
        </div>
    </footer>
    <!-- end .container -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-OERcA2EqjJCMA+/3y+gxIOqMEjwtxJY7qPCqsdltbNJuaOe923+mo//f6V8Qbsw3" crossorigin="anonymous"></script>
</body>

</html>