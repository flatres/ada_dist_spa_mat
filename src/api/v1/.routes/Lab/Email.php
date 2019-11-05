<?php

/**
 * Description

 * Usage:

 */
namespace Lab;

use \PHPMailer\PHPMailer\PHPMailer;
use \PHPMailer\PHPMailer\SMTP;
use \PHPMailer\PHPMailer\Exception;

// define('ZMQ_SERVER', getenv("ZMQ_SERVER"));

class Email
{
    protected $container;

    public function __construct(\Slim\Container $container)
    {
       $this->ada = $container->ada;
       $this->adaModules = $container->adaModules;
    }

// ROUTE -----------------------------------------------------------------------------

    public function emailPost($request, $response, $args)
    {
      $data = $request->getParsedBody();
      $postmark = new \Utilities\Postmark\Postmark($data['to'], $data['subject'], "Lab", true);
      $content = $postmark->template('Lab.Email', array("body"=>$data['body']));
      
      
      // $postmark->send("Ada Lab", $content);
      // Instantiation and passing `true` enables exceptions
        $mail = new PHPMailer(true);

        try {
            //Server settings
            $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      // Enable verbose debug output
            $mail->isSMTP();                                            // Send using SMTP
            $mail->Host       = '192.168.2.4';                    // Set the SMTP server to send through
            // $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
            // $mail->Username   = 'user@example.com';                     // SMTP username
            // $mail->Password   = 'secret';                               // SMTP password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` also accepted
            $mail->Port       = 587;                                    // TCP port to connect to
            // $mail->Port = 25;
            //Recipients
            $mail->setFrom('sdf@marlboroughcollege.org', 'Mailer');
            // $mail->addAddress('joe@example.net', 'Joe User');     // Add a recipient
            $mail->addAddress('ellen@example.com');               // Name is optional
            // $mail->addReplyTo('info@example.com', 'Information');
            // $mail->addCC('cc@example.com');
            // $mail->addBCC('bcc@example.com');

            // Attachments
            // $mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
            // $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name

            // Content
            $mail->isHTML(true);                                  // Set email format to HTML
            $mail->Subject = 'Here is the subject';
            $mail->Body    = 'This is the HTML message body <b>in bold!</b>';
            $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

            $mail->send();
            $res =  'Message has been sent';
        } catch (Exception $e) {
            $res = "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
      
      return emit($response, $res);
    }

}
