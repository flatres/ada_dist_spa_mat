<?php
namespace Auth;
class Bug
{
    protected $container;

    public function __construct(\Slim\Container $container)
    {
       $this->sql= $container->mysql;
       $this->ada = $container->ada;
       $this->isams = $container->isams;
    }


    public function report($request, $response, $args)
    {
      global $userId;
      $data = $request->getParsedBody();
      $staff = new \Entities\People\Staff($this->ada, $userId);
      $data['email'] = $staff->email;
      
      $cc = [
        $data['email'],
        'mc@fire.fundersclub.com'
      ];
      
      $email = new \Utilities\Email\Email('sdf@marlboroughcollege.org', 'ISSUE: ' . $data['subject'], $data['email'], $cc);

      $content = $email->template('Auth.BugReport', $data);
      $email->send($content);
  
      return emit($response, $data);

   }


}

 ?>
