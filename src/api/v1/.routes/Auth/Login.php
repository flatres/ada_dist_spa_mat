<?php
namespace Auth;

/**
 * Manages all aspects of user login. It checks the password, creates a new session and return the authorisatuion key used
 * in subsequent api calls. It also generates the return object which included the user id and permissions object
 */

class Login
{
    /** @var \Slim\Container
     */

    protected $container;
    private $isNewUser = false;

    public function __construct(\Slim\Container $container)
    {
       $this->sql= $container->mysql;
       $this->ad = $container->ad;
       $this->isams = $container->isams;
    }


    public function login($request, $response, $args)
    {
       // reading json params
      $data = $request->getParsedBody();

      if(!isset($data['login']) || !isset($data['password'])){
        return emitError($response, 400, "Not happening");
      }
      // reading post params
      $login = $data['login']; $password = $data['password'];

      $this->isNewUser = !$this->sql->exists('usr_details', 'id', 'login=?', array($login));
      if ($this->isADLogin($login) || $this->isNewUser)
      {
          $id = $this->checkLoginAd($login, $password);
      } else {

          $id = $this->checkLoginNative($login, $password);
      }

      if ($id !== FALSE) {
          return emit($response, $this->loginReturnObject($id ));
      } else {
          return emitError($response, 400, "Not happening");
      }

    }

    private function isADLogin($login)
    {
      $usr = $this->sql->select('usr_details', 'usr_type', 'login=?', array($login));
      if (isset($usr[0]))
      {
        //check if this user type uses ad login
        $type = $usr[0]['usr_type'];
        $isAdLogin = $this->sql->select('usr_types', 'ad_login', 'id=?', array($type))[0]['ad_login'];
        $isAdLogin = $isAdLogin == 1 ? true : false;
        return $isAdLogin;
      } else {
        //usr doesn't exist so see if it exists in AD and act accordingly.
        return true;
      }
    }

    private function newUser($login)
    {
      $isamsStaff = new \Entities\People\iSamsStaff($this->isams);
      $isamsStaff->initials($login);
      $adaUser = new \Entities\People\Staff($this->sql);
      $success = $adaUser->new($isamsStaff);
      return $success;
    }

    private function checkLoginAd($login, $password)
    {
      if ($this->ad->connect($login, $password))
      {
        if ($this->isNewUser)
        {
          $success = $this->newUser($login);
          if (!$success) return false;
        }
        return $this->sql->select('usr_details', 'id', 'login=?', array($login))[0]['id'];
      } else {
        return false;
      }
    }

    private function checkLoginNative($login, $password)
    {
      $sql = $this->sql;
      $data = array($login);

      $d = $sql->select('usr_details', 'id, password_hash', 'login = ?', $data, TRUE);
      // var_dump($d);
      if ($d) {
          $id = $d[0]['id'];

          if (Tools\PassHash::check_password($d[0]['password_hash'], $password)) {
             $sql->update('usr_details', 'last_login=NOW()', 'id=?', array($id));
             return $id;
          } else {
              return false;
          }

      } else {
          return false;
      }
    }

    private function loginReturnObject($id)
    {
      $sql = $this->sql;

      $data = $sql->select('usr_details', 'firstname, lastname, account_type', 'id=?', array($id));

      if(!$data) return array();

      $d = $data[0];

      $returnObj = array();
      $returnObj['userId'] = $id;
      $returnObj['firstname'] = $d['firstname'];
      $returnObj['lastname'] = $d['lastname'];
      $returnObj['auth'] = $this->newSession($id);

      $permissions = new Tools\PermissionsRetriever($sql);
      $returnObj['permissions'] =  $permissions->getUserPermissions($id);
      $returnObj['roles'] = $permissions->getUserRoles($id);

      return $returnObj;

    }

    private function generateApiKey()
    {
         return md5(uniqid(rand(), true));
    }

    private function generateToken() {
         return md5(uniqid(rand(), true));
    }

    private function newSession($id){
      $sql = $this->sql;

      //delete any old sessions
      // $sql->delete('usr_sessions', 'user_id=?', array($id));

      //generate a new token to be stored by the brower and ge the current time
      $auth = $this->generateToken();
      $start = time();

      //write the new session to the bd
      $data = array($id, $auth, $start);
      $sql->insert('usr_sessions', 'user_id, token, started', $data);

      return $auth;

   }


}

 ?>
