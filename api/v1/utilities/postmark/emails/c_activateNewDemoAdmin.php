<?php 
global $vpath;
global $path;

require_once $vpath.'resources/postmark/c_postmark.php';

class E_ActivateNewAdminDemoAccount {

		public function __construct($to, $firstname, $school, $username, $password)
		{
			//to, subject, tag, track
      $postmark = new Postmark($to, "Oodles Demo", "Oodles Demo", true);
      
      $url = "http://".$_SERVER['SERVER_NAME'];
      
      $content = $postmark->template('new_admin_demo', array("name"=>$firstname, "username" => $username, "password" => $password, "action_url" => $url, 'school' => $school));
      
      $postmark->send("Oodles Demo", $content);
      
		
		}	
  
} 

?>