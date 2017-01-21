<?php
  require 'PHPMailer/PHPMailerAutoload.php';

//
// config
// ---------------------------------------------------------

  // your email address
  $EMAIL   = 'email@example.com';

  // your name
  $NAME    = 'YOUR_NAME';

  // subject line
  $SUBJECT = 'Website Contact Message';

  // email content
  $CONTENT = '
    <html>
      <head>
        <title>' . $SUBJECT . '</title>
      </head>
      <body>
        <p><strong style="width: 80px;">Name: </strong>' . $_POST['name'] . '</p>
        <p><strong style="width: 80px;">Email: </strong>' . $_POST['email'] . '</p>
        <p><strong style="width: 80px;">Message: </strong>' . $_POST['message'] . '</p>
      </body>
    </html>
  ';

//
// script
// --------------------------------------------------
//

  if($_SERVER['REQUEST_METHOD'] != 'POST') {
    die('An error occurred. Please try again later.');
  } else {
    if (empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest') {
      die('Only allow access via AJAX');
    } else {
      if (empty($EMAIL) || empty($NAME) || empty($SUBJECT) || empty($CONTENT)) {
        die(json_encode(array('type' => 'error', 'msg' => 'An error occurred. Please check php config field.')));
      } else {
        extract($_POST, EXTR_PREFIX_ALL, 'form');

        $mail = new PHPMailer(true);
        $mail->CharSet = 'UTF-8';
        $mail->setFrom($form_email, $form_name);
        $mail->addReplyTo($form_email, $form_name);
        $mail->addAddress($EMAIL, $NAME);
        $mail->Subject = $SUBJECT;
        $mail->msgHTML($CONTENT);

        if (!$mail->send()) {
          die(json_encode(array('type' => 'error', 'msg' => 'An error occurred. Please try again later.')));
        } else {
          die(json_encode(array('type' => 'success', 'msg' => 'Your message has been sent.')));
        }
      }
    }
  }
?>
