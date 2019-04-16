<?php 
global $vpath;
global $path;

require_once $vpath.'resources/postmark/c_postmark.php';

class E_ActivateNewAdminAccount {

		public function __construct($to, $firstname, $school, $key)
		{
			//to, subject, tag, track
      $postmark = new Postmark($to, "Activate Account", "Activate", true);
      
      $url = "http://".$_SERVER['SERVER_NAME']."/_activate/index.html?k=" . $key;
      
      $content = $postmark->template('new_admin_signup', array("name"=>$firstname, "action_url" => $url, 'school' => $school));
      
      $postmark->send("Activate Oodles", $content);
      
		
		}	
  
} 

?>