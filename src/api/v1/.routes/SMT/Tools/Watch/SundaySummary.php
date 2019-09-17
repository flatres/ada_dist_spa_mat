<?php
namespace SMT\Tools\Watch;
class SundaySummary {

		public function __construct($to, $name, $data)
		{
			// $to = 'sdf@marlboroughcollege.org';
			// $to = 'flatres@gmail.com';
			//to, subject, tag, track
      $postmark = new \Utilities\Postmark\Postmark($to, "Sunday Attendance Summary", "Sunday Attendance Summary", true);
      $content = $postmark->template('SMT.Watch.Chapel.SundaySummary', array("name"=>$name, "data"=>$data));
      $postmark->send("Sunday Attendance Summary", $content);
		}
}

?>
