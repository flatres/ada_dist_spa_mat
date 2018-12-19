<?php
global $vpath;
global $path;

require_once $vpath.'resources/postmark/c_postmark.php';

class E_Report {

		public function __construct($email, $firstname, $bare_path, $reportData)
		{


			$subject = $reportData['name'] . ' Report - ' . ucfirst($reportData['type']);

			$postmark = new Postmark($email, $subject, 'Report', true);

			$reportData['msg'] = $email;

			$postmark->pdf = array('name'=>'report.pdf', 'path'=>$bare_path . $reportData['pdf']);


			$content = $postmark->template('report', array("name"=>$firstname));

			$reportData['msg'] =$postmark->send($subject, $content);



		}

}

?>
