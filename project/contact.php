<?php
ob_start();
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
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/index.css">
</head>

<body>
    <!-- container -->
    <?php
    session_start();
    if(isset($_SESSION["login"])){
    include 'navtop.php';
    }
    ?>
    <div class="bg-images full_page">
        <div class="bg-color-contact">
            <div class="container text-center py-5">
                <h1 class="fw-bold">Contact Us</h1>
            </div>
        </div>
        <div class="container ">
            <div class="row pb-5">
                <div class="col-12">
                    <h2 class="contact-title fs-3 my-3">Get in Touch</h2>
                </div>
                <div class="col-lg-8">
                    <form class="form-contact contact_form" action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post" id="contactForm" novalidate>
                        <?php


                        use PHPMailer\PHPMailer\PHPMailer;
                        use PHPMailer\PHPMailer\Exception;

                        require '../project/PHPMailer-master/src/Exception.php';
                        require '../project/PHPMailer-master/src/PHPMailer.php';
                        require '../project/PHPMailer-master/src/SMTP.php';

                        if ($_POST) {

                            if ($_POST && isset($_POST['name']) && isset($_POST['message']) && isset($_POST['email']) && isset($_POST['subject'])) {

                                try {
                                    $mail = new PHPMailer(true);

                                    $mail->isSMTP();
                                    $mail->Host = "smtp.gmail.com";
                                    $mail->SMTPAuth = true;
                                    $mail->Username = "tandun788@gmail.com";
                                    $mail->Password = "acjzhzanldljgghm";
                                    $mail->SMTPSecure = "ssl";
                                    $mail->Port = 465;

                                    // set email from where
                                    $mail->setFrom("tandun788@gmail.com");

                                    // set where the email send out 
                                    // why both is our email address, because the google cannot help user send a email to the company email account 
                                    $mail->addAddress('tandun788@gmail.com');

                                    $mail->isHTML(true);

                                    $mail->Subject = $_POST['subject'];

                                    $context = "
      <html>
      <head>
      </head>
      <body>
      <p>sender name:{$_POST['name']}</p>
      <p>context:<br>{$_POST['message']}</p>
      <p>from email:<br>{$_POST['email']}</p>
      </body>
      </html>
      ";

                                    // $mail->Body = $_POST['message']." \n form: ".$_POST['emailAddress'];
                                    $mail->Body = $context;
                                    $msg = wordwrap($mail->Body, 70);

                                    $mail->send();
                                    echo "<div class='alert alert-success'> Successfully Sending.</div>";
                                unset($_POST);
                                } catch (PDOException $exception) {
                                    echo "<div class='alert alert-success'> Some error occurrence.</div>";
                                }

                                // echo "<div class='alert alert-info'> Successfully Sending.</div>";
                            } else {
                                echo "<div class='alert alert-success'>Empty data is not allow</div>";
                            }
                        }
                        ?>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group my-2">
                                    <input class="form-control" name="name" id="name" type="text" value="<?php echo isset($_POST['name']) ? $_POST['name'] : "" ?>" onfocus="this.placeholder = ''" onblur="this.placeholder = 'Enter your name'" placeholder='Enter your name'>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group my-2">
                                    <input class="form-control" name="email" id="email" type="email" value="<?php echo isset($_POST['email']) ? $_POST['name'] : "" ?>" onfocus="this.placeholder = ''" onblur="this.placeholder = 'Enter email address'" placeholder='Enter email address'>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group my-2">
                                    <input class="form-control" name="subject" id="subject" type="text" value="<?php echo isset($_POST['subject']) ? $_POST['name'] : "" ?>" onfocus="this.placeholder = ''" onblur="this.placeholder = 'Enter Subject'" placeholder='Enter Subject'>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group my-2">
                                    <textarea class="form-control w-100" name="message" id="message" value="<?php echo isset($_POST['message']) ? $_POST['name'] : "" ?>" cols="30" rows="9" onfocus="this.placeholder = ''" onblur="this.placeholder = 'Enter Message'" placeholder='Enter Message'></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="form-group mt-3 rounded">
                            <button type="submit" class="btn_3 button-contactForm rounded py-2 px-3 text-decoration-none fw-bold">Send Message</button>
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


    <?php
    if(isset($_SESSION["login"])){
    include 'footer.php';
    }
    ?>
    <!-- end .container -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-OERcA2EqjJCMA+/3y+gxIOqMEjwtxJY7qPCqsdltbNJuaOe923+mo//f6V8Qbsw3" crossorigin="anonymous"></script>
</body>

</html>