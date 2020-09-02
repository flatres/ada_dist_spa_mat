<?php
namespace Utilities\Email\Emails\Covid;

class CovidStaff {

		public function __construct($to, $name, $hash)
		{
			//to, subject, tag, track
      $to = 'sdf@marlboroughcollege.org';
      $email = new \Utilities\Email\Email($to, "MC Covid-19 Check In");

      $url = "https://ada.marlboroughcollege.org/aux/covid/staff?h=" . $hash;

      $content = $email->template('covid.staff', array("name"=>$name, "action_url" => $url));

      $email->send($content);

		}

}

?>
