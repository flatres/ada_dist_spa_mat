<?php

/**
 * Description

 * Usage:

 */
namespace Dependency;

class ActiveDirectory
{
    public function __construct()
    {
    }

    public function connect($username, $password)
    {
      $adServer = "192.168.2.5";
      $ldap = ldap_connect($adServer);
      $this->ldap = $ldap;

      $ldaprdn = 'marlborough' . "\\" . $username;

      ldap_set_option($ldap, LDAP_OPT_PROTOCOL_VERSION, 3);
      ldap_set_option($ldap, LDAP_OPT_REFERRALS, 0);

      if (@ldap_bind($ldap, $ldaprdn, $password))
      {
        return $this->getAttributes($username);
      } else {
        return false;
      };
    }

    private function getAttributes($username)
    {
      $ldap = $this->ldap;
      $filter="(samAccountName=$username)";
      $result = ldap_search($ldap,"dc=marlboroughcollege,dc=local",$filter);

      $entriesAry = ldap_get_entries($ldap, $result);
      if (isset($entriesAry[0]))
      {
        $attr = $entriesAry[0];

        $details = array(
          'lastName' => isset($attr['sn'][0]) ? $attr['sn'][0] : null,
          'firstName' => isset($attr['givenname'][0]) ? $attr['givenname'][0] : null,
          'login' => isset($attr['samaccountname'][0]) ? $attr['samaccountname'][0] : null,
          'email' => isset($attr['mail'][0]) ? $attr['mail'][0] : null,
          'groups' => isset($attr['memberof'][0]) ? $this->processGroups($attr['memberof']) : null
        );
      }
      // $data = $info;

      @ldap_close($ldap);

      return $details;

    }

    private function processGroups($groups)
    {
      $data = array();
      foreach($groups as $group)
      {
        //remove firefly and share permissions as of no use to ADA
        if (strpos($group, 'Firefly') == false && strpos($group, 'Share Permission') == false && strpos($group, 'Sdrive') == false)
        {
          // typical group string format:
          // CN=All-Beaks-Auto-Generated,OU=Security_Groups,OU=Auto-Generated-Groups,OU=Domain Local Groups,OU=Groups,DC=marlboroughcollege,DC=local
          $cn = explode(',', $group)[0];
          $explode = explode('=', $cn);
          if (isset($explode[1]))
          {
            $data[] = $name = $explode[1];
            if (!isset($this->allGroups[$name]))
            {
              $this->allGroups[$name] = array(
                'label' => $name,
                'value' => $name );
            }
          }
        }
      }
      return $data;
    }
}
