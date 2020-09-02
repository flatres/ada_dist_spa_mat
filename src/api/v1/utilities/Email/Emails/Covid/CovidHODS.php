<?php
namespace Utilities\Email\Emails\Covid;

class CovidHODS {

		public function __construct($to, $name, array $alertNames, array $notAnsweredNames, array $notInWorkNames)
		{
			//to, subject, tag, track
      $to = 'sdf@marlboroughcollege.org';
			$prefix = 'All Clear:';
			$msg = 'All clear. No members of staff have reported Covid-19 symptoms today';

			$alertHTML = '';
			$notAnsweredHTML = '';
			$notInWorkHTML = '';

			if (count($notAnsweredNames) > 0) {
				$prefix ='Attention: ';
				$msg = 'All clear so far but the following members of staff have not checked in today.';
				$notAnsweredHTML = "<p style='margin-top:0; margin-bottom: 10px; color:#74787E;font-size:13px;line-height:1.5em;text-align:left;font-weight:bold;' >Not Checked In</p>";
				foreach($notAnsweredNames as $n) $notAnsweredHTML .= "<p style='margin-top:0;color:#74787E;font-size:13px;line-height:1.5em;text-align:left;font-weight:normal;' >$n</p>";
			}

			if (count($alertNames) > 0) {
				$prefix = "!!! Alert !!! ";
				$msg = 'The following member(s) of staff have reported possible Covid-19 symptoms.';
				$alertHTML = "<p style='margin-top:0; margin-bottom: 10px; color:#74787E;font-size:13px;line-height:1.5em;text-align:left;font-weight:bold;' >Possible Covid-19 Symptoms</p>";
				foreach($alertNames as $n) $alertHTML .= "<p style='margin-top:0;color:#74787E;font-size:13px;line-height:1.5em;text-align:left;font-weight:normal;' >$n</p>";
			}

			if (count($notInWorkNames) > 0) {
				$notInWorkHTML = "<p style='margin-top:0; margin-bottom: 10px; color:#74787E;font-size:13px;line-height:1.5em;text-align:left;font-weight:bold;' >Not In School Today</p>";
				foreach($notInWork as $n) $notInWorkHTML .= "<p style='margin-top:0;color:#74787E;font-size:13px;line-height:1.5em;text-align:left;font-weight:normal;' >$n</p>";
			}

      $email = new \Utilities\Email\Email($to, "$prefix HOD Covid-19 Check-In Summary");


      $content = $email->template('covid.hods', array("name"=>$name, "notAnswered" => $notAnsweredHTML));

      // $email->send($content);
			return [
				'alert' => $alertNames,
				'notAnswered' => $notAnsweredNames,
				'notInWorkNames' => $notInWorkNames
			];
		}

}

?>
