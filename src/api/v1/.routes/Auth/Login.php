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
    const LOCKOUT_TIME_MINUTES = 2;
    protected $container;
    private $isNewUser = false;

    public function __construct(\Slim\Container $container)
    {
       $this->sql= $container->mysql;
       $this->ad = $container->ad;
       $this->isams = $container->isams;
       $this->log = $container->logger;
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
      $userId = $this->sql->select('usr_details', 'id', 'login=?', array($login))[0]['id'] ?? null;
      //check if login exists

      if (!$userId) {  //user doesn't exist
        $data = $data = [
          'success' => false,
          'message' => "Invalid username / password"
        ];
        return emit($response, $data);
      }

      if ($this->isLocked($login)) {
        $ip = $request->getServerParam('REMOTE_ADDR');
        $this->log->warning("Attempt on Locked Account usr:$login ip:$ip");
        return emitError($response, 400, []);
      }

      if ($this->isADLogin($login) || $this->isNewUser) {
          $id = $this->checkLoginAd($login, $password);
      } else {
          $id = $this->checkLoginNative($login, $password);
      }

      if ($id !== FALSE) {
          $this->writeLog($request, $login, false);
          $data = [
            'success' => true,
            'loginObject' => $this->loginReturnObject($id)
          ];
          $this->sql->update('usr_details', 'failedAttempts=?, isLocked=?', 'login=?', [0, 0, $login]);
          return emit($response, $data);
      } else {
          $failedAttempts = $this->sql->select('usr_details', 'failedAttempts', 'login=?', [$login])[0]['failedAttempts'];
          $failedAttempts++;
          $locked = $failedAttempts > 3 ? 1 : 0;

          $this->sql->update('usr_details', 'failedAttempts=?, isLocked=?', 'login=?', [$failedAttempts, $locked, $login]);
          if ($locked == 1) {
            //write log
            $ip = $request->getServerParam('REMOTE_ADDR');
            $userId = $this->sql->select('usr_details', 'id', 'login=?', array($login))[0]['id'];
            $this->log->warning("User Account Locked usr:$login ip:$ip");
            $time = self::LOCKOUT_TIME_MINUTES;
            $data = $data = [
              'success' => false,
              'message' => "Too many attempts. Account locked for $time minutes."
            ];
            return emit($response, $data);
          }

          $data = $data = [
            'success' => false,
            'message' => "Invalid username / password"
          ];
          return emit($response, $data);
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

    private function isLocked($login)
    {
      $usr = $this->sql->select('usr_details', 'isLocked, lastActive', 'login=?', [$login]);
      if (!isset($usr[0])) return FALSE;

      $usr = $usr[0];
      if ($usr['isLocked'] == 0) return false;
      if (time() - strtotime($usr['lastActive']) > self::LOCKOUT_TIME_MINUTES * 60) {
        $this->sql->update('usr_details', 'failedAttempts=?, isLocked=?', 'login=?', [0, 0, $login]);
        return false;
      }
      return true;

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
