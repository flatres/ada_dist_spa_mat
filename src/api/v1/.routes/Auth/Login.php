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

      if ($this->isADLogin($login) || $this->isNewUser) {
          $id = $this->checkLoginAd($login, $password);
      } else {
          $id = $this->checkLoginNative($login, $password);
      }

      if ($id !== FALSE) {
          $this->writeLog($request, $login, false);
          return emit($response, $this->loginReturnObject($id ));
      } else {
          return emitError($response, 400, "Not happening");
      }

    }

    private function writeLog($request, string $login, bool $hasFailed)
    {
      $ip = $request->getServerParam('REMOTE_ADDR');
      $hasFailed = $hasFailed ? 1 : 0;
      $user_id = $this->sql->select('usr_details', 'id', 'login=?', array($login))[0]['id'];
      $this->sql->insert('usr_log', 'user_id, ip, failed', [$user_id, $ip, $hasFailed]);
    }

    private function isADLogin($login)
    {
      $usr = $this->sql->select('usr_details', 'ad_login', 'login=?', array($login));
      return isset($usr[0]) ? $usr[0]['ad_login'] : false;

    }

    private function checkLoginAd($login, $password)
    {
      if ($this->ad->connect($login, $password))
      {
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

      $data = $sql->select('usr_details', 'firstname, lastname, ad_login', 'id=?', array($id));

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
