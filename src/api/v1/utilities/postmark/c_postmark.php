<?php
global $path;
require_once($path. "tools/vendor/autoload.php");

use Postmark\PostmarkClient;
use Postmark\Models\PostmarkException;
use Postmark\Models\PostmarkAttachment;


class Postmark {

		private $to;
		private $subject;
		private $htmlbody, $textbody, $tag, $track;

		public function __construct($to, $subject, $tag, $track = true)
		{

			$this->to = $to;
			$this->subject = $subject;
      $this->tag = $tag;
      $this->track = $track;


		}


  public function send($title, $htmlcontent, $textcontent = ""){

		  $htmlbody = $this->template('skeleton', array('title' => $title, 'content'=>$htmlcontent, 'year' => date("Y")));
			$textbody = $textcontent;

      $attachment = PostmarkAttachment::fromFile(dirname(__FILE__). '/img/logo.jpg', 'logo', 'image/jpg');

			$attachments = array();

			$attachments[] = array(	"Name"=> "logo.jpg",
														 	"Content"=> $this->encodeImg('logo.jpg'),
															"ContentType"=> "image/jpeg",
															"ContentID"=> "cid:logo");

      if(isset($this->pdf)){

				$pdf = $this->pdf;

				$attachments[] = array(	"Name"=> $pdf['name'],
															 	"Content"=> base64_encode(file_get_contents($pdf['path'])),
																"ContentType"=> "application/pdf",
															 );

			}

//       echo $htmlbody;
      $message = [
        'To' => $this->to,
        'From' => "noreply@oodles.me",
				'Bcc' => 'flatres+postmark@gmail.com',
        'TrackOpens' => $this->track,
        'Subject' => $this->subject,
        'TextBody' => $textbody,
        'HtmlBody' => $htmlbody,
        'Tag' => $this->tag,
        'Headers' => [ "X-CUSTOM-HEADER" => "Header content"],
        "Attachments"=> $attachments
      ];

      $client = new PostmarkClient("32e6fcb3-8b39-4fdd-8e6a-36b1187c8490");

      return $client->sendEmailBatch([$message]);


    }


    //variable = key / value array of replacements to make
    public function template($file, $variables){
				global $vpath;

        $path = $vpath."resources/postmark/templates/$file.html";

        $myfile = fopen($path, "r") or die();
        $data = fread($myfile,filesize($path));
        fclose($myfile);

        foreach($variables as $key => $value)
        {
          $data = str_replace("{{".$key . "}}",$value,$data);
        }

        return $data;

    }


		//returns a base64 encode of the image
		private function encodeImg($file){

			global $vpath;
			// First get your image


        $path = $vpath."resources/postmark/img/$file";

				$data = base64_encode(file_get_contents($path));
				return $data;

		}

}
