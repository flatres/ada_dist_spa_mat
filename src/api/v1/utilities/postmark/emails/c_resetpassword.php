<?php 
global $vpath;
global $path;

require_once $vpath.'resources/postmark/c_postmark.php';

class E_ResetPassword {

		public function __construct($to, $firstname, $key)
		{
			//to, subject, tag, track
      $postmark = new Postmark($to, "Reset Password", "Password Reset", true);
      
      $url = "http://".$_SERVER['SERVER_NAME']."/#reset/" . $key;
      
      $content = $postmark->template('reset_password', array("name"=>$firstname, "action_url" => $url));
      
      $postmark->send("Password Reset", $content);
      
		
		}	
  
} 

?>