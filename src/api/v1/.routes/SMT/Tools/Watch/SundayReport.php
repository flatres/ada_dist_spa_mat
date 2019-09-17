<?php
namespace SMT\Tools\Watch;

class SundayReport {

		public function __construct($to, $name, $data)
		{
			// $to = 'sdf@marlboroughcollege.org';
			// $to = 'flatres@gmail.com';
			//to, subject, tag, track
      $postmark = new \Utilities\Postmark\Postmark($to, "Sunday Attendance Report", "Sunday Report", true);
      $content = $postmark->template('SMT.Watch.Chapel.SundayReport', array("name"=>$name, "data"=>$data));
      $postmark->send("Sunday Report", $content);
		}
}

?>
