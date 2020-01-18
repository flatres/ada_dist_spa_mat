<?php
// global $path;
// require_once($path. "tools/vendor/autoload.php");
namespace Utilities\Email;

use \PHPMailer\PHPMailer\PHPMailer;
use \PHPMailer\PHPMailer\SMTP;
use \PHPMailer\PHPMailer\Exception;

class Email {

		private $to;
		private $subject;
		private $from;
		private $bcc;
		private $htmlbody, $textbody, $tag, $track;

		private $debug = false;
		private $userEmail;

		public function __construct($to = null, $subject = null, $from = 'noreply@marlboroughcollege.org', array $cc = [], array $bcc = [])
		{
			$this->to = $to;
			$this->subject = $subject;
			$this->from = $from;
			$this->cc = $cc;
			$this->bcc = $bcc;

			global $userId;
			$ada = new \Dependency\Databases\Ada();
			$this->user = new \Entities\People\User($ada, $userId);
			$this->userEmail = $this->user->email ? $this->user->email : 'flatres@gmail.com';

		}


  public function send($htmlcontent, $title = ''){

		  $htmlbody = $this->template('skeleton', array('title' => $title, 'content'=>$htmlcontent, 'year' => date("Y")));

			// Instantiation and passing `true` enables exceptions
			$mail = new PHPMailer(true);

			try {
				// https://pepipost.com/tutorials/phpmailer-smtp-error-could-not-connect-to-smtp-host/
				$mail->SMTPOptions = array(
					'ssl' => array(
					'verify_peer' => false,
					'verify_peer_name' => false,
					'allow_self_signed' => true
					)
					);
					//Server settings
					// $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      // Enable verbose debug output
					$mail->isSMTP();                                            // Send using SMTP
					$mail->Host = '192.168.2.4';                    // Set the SMTP server to send through
					$mail->Port = 25;

					//Recipients
					$mail->setFrom($this->from);
					$mail->addAddress($this->debug === true ? $this->userEmail : $this->to);               // Name is optional

					foreach ($this->bcc as $bccAddress) {
						$mail->addBCC($this->debug === true ? $this->userEmail : $bccAddress);
					}

					foreach ($this->cc as $ccAddress) {
						$mail->addCC($this->debug === true ? $this->userEmail : $ccAddress);
					}
					// Attachments
					// $mail->addAttachment(dirname(__FILE__). '/img/logo.jpg', 'logo');         // Add attachments

					// Content
					$mail->isHTML(true);                                  // Set email format to HTML
					$mail->Subject = $this->subject;
					$mail->Body = $htmlbody;

					$mail->send();

			} catch (Exception $e) {
					$res = "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
			}

    }

    //variable = key / value array of replacements to make
    public function template($file, $variables){

				$file = str_replace('.html', '', $file);
        $path = dirname(__FILE__)."/templates/$file.html";

        $myfile = fopen($path, "r") or die();
        $data = fread($myfile,filesize($path));
        fclose($myfile);

        foreach($variables as $key => $value)
        {
          $data = str_replace("{{".$key . "}}",$value,$data);
        }

        return $data;

    }

		public function listTemplates()
		{
				$path = dirname(__FILE__)."/templates";
				$files = scandir($path);
				$files = array_diff(scandir($path), array('.', '..'));
				$list = [];
				foreach($files as $file) {
					$item = [
						'filename' => $file
					];
					$path = dirname(__FILE__)."/templates/$file";
	        $myfile = fopen($path, "r") or die();
	        $data = fread($myfile,filesize($path));
	        fclose($myfile);
					$item['data'] = $data;
					$item['vars'] = $this->getContents($data, '{{', '}}');

					$list[] = $item;

				}
				return $list;
		}
		// https://stackoverflow.com/questions/27078259/get-string-between-find-all-occurrences-php/27078384
		private function getContents($str, $startDelimiter, $endDelimiter) {
		  $contents = array();
		  $startDelimiterLength = strlen($startDelimiter);
		  $endDelimiterLength = strlen($endDelimiter);
		  $startFrom = $contentStart = $contentEnd = 0;
		  while (false !== ($contentStart = strpos($str, $startDelimiter, $startFrom))) {
		    $contentStart += $startDelimiterLength;
		    $contentEnd = strpos($str, $endDelimiter, $contentStart);
		    if (false === $contentEnd) {
		      break;
		    }
		    $contents[] = [
					'label' => substr($str, $contentStart, $contentEnd - $contentStart),
					'value' => null
				];
		    $startFrom = $contentEnd + $endDelimiterLength;
		  }

		  return $contents;
		}

		//returns a base64 encode of the image
		private function encodeImg($file){
      $path = dirname(__FILE__) . "/img/$file";
			$data = base64_encode(file_get_contents($path));
			return $data;
		}

}
