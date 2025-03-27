<?php
session_start();

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'assets/vendor/phpMailer/src/Exception.php';
require 'assets/vendor/phpMailer/src/PHPMailer.php';
require 'assets/vendor/phpMailer/src/SMTP.php';

if(isset($_POST['submitContact'])) 
{

  $fullname = $_POST['full_name'];
  $email = $_POST['email_address'];
  $subject = $_POST['subject'];
  $message = $_POST['message'];

  //Create an instance; passing `true` enables exceptions
  $mail = new PHPMailer(true);

  try {
      //Server settings
      //$mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
      $mail->isSMTP();                                            //Send using SMTP
      $mail->SMTPAuth   = true;                                   //Enable SMTP authentication

      $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
      $mail->Username   = 'phichuong80@gmail.com';                     //SMTP username
      $mail->Password   = 'Phooilengmun85';                               //SMTP password

      $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;            //ENCRYPTION_SMTPS 465 - Enable implicit TLS encryption
      $mail->Port       = 587;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

      //Recipients
      $mail->setFrom('phichuong80@gmail.com', 'Code Master');
      $mail->addAddress('phichuong80@gmail.com', 'Joe User');     //Add a recipient

      // $mail->addAddress('ellen@example.com');               //Name is optional
      // $mail->addReplyTo('info@example.com', 'Information');
      // $mail->addCC('cc@example.com');
      // $mail->addBCC('bcc@example.com');

      //Attachments
      // $mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
      // $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name

      //Content
      $mail->isHTML(true);                                  //Set email format to HTML
      $mail->Subject = 'New enquiry - Code Master';
      $mail->Body    = '<h3>Hello, you got mail</h3>
        <h4>Fullname: '.$fullname'</h4>
        <h4>Email: '.$email'</h4>
        <h4>Subject: '.$subject'</h4>
        <h4>Message: '.$message'</h4>
      ';

      //$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

      // if($mail->send()) 
      // {
      //   $_SESSION['status'] = "Thank you for contacting us - Team Code Master"
      //   header("Location: {$_SERVER["HTTP_REFERER"]}");
      //   exit(0);
      // }
      // else
      // {
      //   $_SESSION['status'] = "Message could not be sent. Mailer Error: {$mail->ErrorInfo}"
      //   header("Location: {$_SERVER["HTTP_REFERER"]}");
      //   exit(0);
      // }

      $mail->send();
      echo 'Message has been sent';
  } catch (Exception $e) {
      echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
  }

}
else 
{
  header('Location: index.html');
  exit(0);
}

?>
