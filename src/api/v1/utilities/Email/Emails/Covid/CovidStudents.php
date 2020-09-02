<?php
namespace Utilities\Email\Emails\Covid;

class CovidStudents {

		public function __construct($to, $name, $hash)
		{
			//to, subject, tag, track
      $to = 'sdf@marlboroughcollege.org';
      $email = new \Utilities\Email\Email($to, "MC Covid-19 Check In");

      $url = "http://".$_SERVER['SERVER_NAME']."/aux/covid/students?h=" . $hash;

      $content = $email->template('covid.students', array("name"=>$name, "action_url" => $url));

      $email->send($content);

		}

}

?>
