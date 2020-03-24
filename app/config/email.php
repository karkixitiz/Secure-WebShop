<?php
  require_once __DIR__ . '/../mailer/class.phpmailer.php';
 $mail = new PHPMailer;
 $mail->IsSMTP();                                      // Set mailer to use SMTP
 $mail->Host = 'smtp.gmail.com';                 // Specify main and backup server
 $mail->Port = 587;                                    // Set the SMTP port
 $mail->SMTPAuth = true;                               // Enable SMTP authentication
 $mail->Username = '';                // SMTP username. give dummy gmail accout and password to send gmail from this accout
 $mail->Password = '';                  // SMTP password
 $mail->SMTPSecure = 'tls';
 $mail->FromName = 'Web Security';        // Name is optional
 $mail->IsHTML(true);
  ?>
