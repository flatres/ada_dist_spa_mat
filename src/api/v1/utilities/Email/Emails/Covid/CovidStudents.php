<?php
namespace Utilities\Email\Emails\Covid;

class CovidStaff {

		public function __construct($to, $name, $hash)
		{
			//to, subject, tag, track
      $to = 'flatres@gmail.com';
      $email = new \Utilities\Email\Email($to, "MC Covid-19 Check In");

      $url = "http://".$_SERVER['SERVER_NAME']."/aux/c19students?h=" . $hash;

      $content = $email->template('covid.students', array("name"=>$name, "action_url" => $url));

      $email->send("MC Covid-19 Check In", $content);

		}

}

?>
