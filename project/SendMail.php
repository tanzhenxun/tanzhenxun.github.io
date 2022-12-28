<?php 
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'phpMailer/src/Exception.php';
require 'phpMailer/src/PHPMailer.php';
require 'phpMailer/src/SMTP.php';

if ($_POST && isset($_POST['name']) && isset($_POST['message']) && isset($_POST['emailAddress'])) {

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
    $mail->addAddress("tandun788@gmail.com");

    $mail->isHTML(true);

    $mail->Subject = "Contact Us";

    $context = "
      <html>
      <head>
      </head>
      <body>
      <p>sender name:{$_POST['name']}</p>
      <p>context:<br>{$_POST['message']}</p>
      <p>from email:<br>{$_POST['emailAddress']}</p>
      </body>
      </html>
      ";

    // $mail->Body = $_POST['message']." \n form: ".$_POST['emailAddress'];
    $mail->Body = $context;
    $msg = wordwrap($mail->Body, 70);

    $mail->send();

    header("Location: contact.php?action=success_mail&message=Successfully Sending");
    die();
  } catch (PDOException $exception) {
    header("Location: contact.php?action=fail_mail&message=Some error occurrence");
    die();
  }

  // echo "<div class='alert alert-info'> Successfully Sending.</div>";
}else{
  header("Location: contact.php?action=error&message=Empty data is not allow");
  die();
}
?>