<?php

/**
 * Description

 * Usage:

 */
namespace Admin\ActiveDirectory;

define('LDAP_IP', $_SERVER["LDAP_IP"]);

class Explorer
{
    protected $container;
    private $allGroups = array(
      array(
      'label' => 'All Groups',
      'value' => '')
    );

    public function __construct(\Slim\Container $container)
    {
       $this->sql= $container->mysql;
       $this->isValidCredentials = $this->connect();
       $this->isams = $container->isams;
       $this->ad = $container->ad;
    }

    private function connect()
    {
      $adServer = LDAP_IP;
      $ldap = \ldap_connect($adServer);
      $this->ldap = $ldap;

      $username = 'sdf';
      $password = 'XX0misje1s.mmondcol';

      $ldaprdn = 'marlborough' . "\\" . $username;

      ldap_set_option($ldap, LDAP_OPT_PROTOCOL_VERSION, 3);
      ldap_set_option($ldap, LDAP_OPT_REFERRALS, 0);

      return @ldap_bind($ldap, $ldaprdn, $password);
    }

    public function iSamsInfo_Get($request, $response, $args)
    {
      $initials = $args['initials'];
      $staff = new \Entities\Staff\Tools\iSamsStaff($this->isams);
      $staff->initials($initials);
      return emit($response, $staff);
    }

    public function me_GET($request, $response, $args)
    {
      if ($this->isValidCredentials) {

          // $data = $this->getDN($ldap, 'sdf', 'dc=marlborough');
          $ldap = $this->ldap;
          $filter="(samAccountName=sdf)";
          $result = ldap_search($ldap,"dc=marlboroughcollege,dc=local",$filter);

          $info = ldap_get_entries($ldap, $result);
          $data = $info;

          @ldap_close($ldap);

      } else {
          $msg = time() . " Invalid email address / password";
          echo $msg;
      }

      $data = $this->convert_from_latin1_to_utf8_recursively($data);
      $userId = $request->getAttribute('userId');
      $data['ad'] = $this->ad->connect('sdf', 'XX0misje1s.mmondcol');
      return emit($response, $data);
    }

    public function allUsers_GET($request, $response, $args)
    {
      $data = array();
      $members = $this->mydap_members('CN=allstaff,OU=Distribution Groups,OU=Groups,DC=marlboroughcollege,DC=local','g');

      $data['users'] = $this->processMembers($members);
      $groups = array_values($this->allGroups);
      usort($groups, array($this, "sortGroup"));
      $data['groups'] = $groups;
      return emit($response, $data);
    }

    private function sortGroup($a, $b)
    {
      return strcmp($a['value'], $b['value']);
    }

    private function processMembers($members)
    {
      $data = array();
      $keep = array('samaccountname','mail','sn', 'givenname', 'memberof');
      foreach($members as $m) {
      	// Query a user's attributes using mydap_attributes(member_dn,keep)
      	// The member_dn is the step $m of this foreach
      	$attr = $this->mydap_attributes($m,$keep);
        $user = array(
          'lastName' => isset($attr['sn'][0]) ? $attr['sn'][0] : null,
          'firstName' => isset($attr['givenname'][0]) ? $attr['givenname'][0] : null,
          'login' => isset($attr['samaccountname'][0]) ? $attr['samaccountname'][0] : null,
          'email' => isset($attr['mail'][0]) ? $attr['mail'][0] : null,
          'groups' => isset($attr['memberof'][0]) ? $this->processGroups($attr['memberof']) : null
        );
        $data[] = $user;
      }
      return $data;
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

    private function convert_from_latin1_to_utf8_recursively($dat)
     {
        if (is_string($dat)) {
           return utf8_encode($dat);
        } elseif (is_array($dat)) {
           $ret = [];
           foreach ($dat as $i => $d) $ret[ $i ] = self::convert_from_latin1_to_utf8_recursively($d);

           return $ret;
        } elseif (is_object($dat)) {
           foreach ($dat as $i => $d) $dat->$i = self::convert_from_latin1_to_utf8_recursively($d);

           return $dat;
        } else {
           return $dat;
        }
     }

    private function mydap_attributes($user_dn,$keep=false) {
    	$mydap = $this->ldap;
    	if(!isset($mydap)) die('Error, no LDAP connection established');
    	if(empty($user_dn)) die('Error, no LDAP user specified');

    	// Disable pagination setting, not needed for individual attribute queries
    	ldap_control_paged_result($mydap,1);

    	// Query user attributes
    	$results = (($keep) ? ldap_search($mydap,$user_dn,'cn=*',$keep) : ldap_search($mydap,$user_dn,'cn=*'))
    	or die('Error searching LDAP: '.ldap_error($mydap));

    	$attributes = ldap_get_entries($mydap,$results);

    	// Return attributes list
    	if(isset($attributes[0])) return $attributes[0];
    	else return array();
    }

    private function mydap_members($object_dn,$object_class='g')
    {
      $mydap = $this->ldap;
     	if(!isset($mydap)) die('Error, no LDAP connection established');
     	if(empty($object_dn)) die('Error, no LDAP object specified');

     	// Determine class of object we are dealing with
     	// Groups, use range to overcome LDAP attribute limit
     	if($object_class == 'g') {
     		$output = array();
     		$range_size = 1500;
     		$range_start = 0;
     		$range_end = $range_size - 1;
     		$range_stop = false;

     		do {
     			// Query Group members
     			// $results = ldap_search($mydap,$object_dn,'cn=*',array("member;range=$range_start-$range_end")) or die('Error searching LDAP: '.ldap_error($mydap));
     			$results = ldap_search($mydap,$object_dn,'cn=*',array("member;range=$range_start-$range_end")) or die('Error searching LDAP: '.ldap_error($mydap));
     			$members = ldap_get_entries($mydap,$results);
     			$member_base = false;

     			// Determine array key of the member results
     			// If array key matches the format of range=$range_start-* we are at the end of the results
     			if(isset($members[0]["member;range=$range_start-*"])) {
     				// Set flag to break the do loop
     				$range_stop = true;
     				// Establish the key of this last segment
     				$member_base = $members[0]["member;range=$range_start-*"];
     			// Otherwise establish the key of this next segment
     			} elseif(isset($members[0]["member;range=$range_start-$range_end"]))
     				$member_base = $members[0]["member;range=$range_start-$range_end"];
     			if($member_base && isset($member_base['count']) && $member_base['count'] != 0) {
     				// Remove 'count' element from array
     				array_shift($member_base);
     				// Append this segment of members to output
     				$output = array_merge($output,$member_base);
     			} else $range_stop = true;
     			if(!$range_stop) {
     				// Advance range
     				$range_start = $range_end + 1;
     				$range_end = $range_end + $range_size;
     			}
     		} while($range_stop == false);

     	// Containers and Organizational Units, use pagination to overcome SizeLimit
     	} elseif($object_class == 'c' || $object_class == "o") {

     		$pagesize = 1000;
     		$counter = "";
     		do {
     			ldap_control_paged_result($mydap,$pagesize,true,$counter);

     			// Query Container or Organizational Unit members
     			$results = ldap_search($mydap,$object_dn,'objectClass=user',array('sn')) or die('Error searching LDAP: '.ldap_error($mydap));
     			$members = ldap_get_entries($mydap, $results);

     			// Remove 'count' element from array
     			array_shift($members);

     			// Pull the 'dn' from each result, append to output
     			foreach($members as $e) $output[] = $e['dn'];

     			ldap_control_paged_result_response($mydap,$results,$counter);
     		} while($counter !== null && $counter != "");

     	// Invalid object_class specified
     	} else die("Invalid mydap_member object_class, must be c, g, or o");

     	// Return alphabetized member list
     	sort($output);
     	return $output;
     }
}
