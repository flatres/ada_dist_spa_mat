<?php
namespace Dependency;
use PDO;

class Exgarde {

	public function __construct(\Dependency\Mysql $mysql)
	{
    date_default_timezone_set("Europe/London");
    $this->ary_doorNamesByReader = array();
		$this->errorEvents = array();
		$this->names = null;
    $this->sql = $mysql;
    $this->connect();
	}

  public function getLocations()
  {
   $binding = array();
   $query = "SELECT [id], [name] FROM dbo.DOOR_View";
   return $this->query($query, $binding);
  }

	public function getAreas()
  {
   $binding = array();
   $query = "SELECT [id], [name] FROM dbo.AREA_View";
   return $this->query($query, $binding);
  }

  public function getLocation(int $id)
  {
		$names = $this->getAllKeyHolderNames();
		$readerID = $this->getReaderByDoor($id);
		$events = $this->getEventsByReader($readerID);

	 foreach($events as &$item){
		 $unix = strtotime($item['LOCAL_TIME']);
		 $item['entry_timestamp'] = date('d.m.y, g:i a', $unix);
		 $item['entry_time'] = date('G:i', $unix);
		 $item['entry_unix'] = $unix;
		 $item['name'] = $this->makeName($item);
	 }

	 return $events;
	}

	public function getLocationByDate(int $unix, int $id)
  {
		 $names = $this->getAllKeyHolderNames();
	 	 $readerID = $this->getReaderByDoor($id);
	   $events = $this->getEventsByReader($readerID, $unix);

     foreach($events as &$item){
       $unix = strtotime($item['LOCAL_TIME']);
       $item['entry_timestamp'] = date('d.m.y, g:i a', $unix);
       $item['entry_time'] = date('G:i', $unix);
       $item['entry_unix'] = $unix;
       $item['name'] = $this->makeName($item);
	 	}

	 	return $events;
	}

	public function getAreaByDate(int $unix, int $id)
  {
    $names = $this->getAllKeyHolderNames();
    $readerAry = $this->getReadersByArea($id);
    $events = $this->getEventsByReaders($readerAry, $unix);

    foreach($events as &$item){
    	$unix = strtotime($item['LOCAL_TIME']);
      $item['entry_timestamp'] = date('d.m.y, g:i a', $unix);
     	$item['entry_time'] = date('G:i', $unix);
      $item['entry_unix'] = $unix;
      $item['name'] = $this->makeName($item);
      $item['location'] = $this->getDoorNameByReader($item['ID_1']);
    }

    return $events;
	}

	public function getBoardingFromID(int $userID, array &$item)
  {
    $sql = $this->sql;
    $this->houses = array();
		$item['node_id'] = $userID;

		$d = $sql->select('t_categories', 'id', 'name=? AND school=223', array('BOARDING'));
		$catID = $d[0]['id'];

		$d = $sql->select('t_tags', 'id, name', 'category=?', array($catID));
		foreach($d as $bhouse){
			$houses['h_'. $bhouse['id']]  = $bhouse['name'];
		}

		$d = $sql->select('t_tagmap', 'tag_id', 'user_id=?', array($userID));
		foreach($d as $tag){
			$tagID = $tag['tag_id'];
			if(isset($houses['h_'.$tagID])){ return $houses['h_'.$tagID];}
		}

		return '-';

	}

	public function getBoardingFromName(string $name, array &$item = null)
  {
    $sql = $this->sql;
		global $user_school;

		$arr = explode(' ',trim($name));
		$firstname = $arr[0];
		$lastname = array_pop($arr);
		if($item) $item['lastName'] = $lastname;

    $bind = array($firstname.'%', $firstname.'%', '%'.$lastname);
		$d = $sql->select('u_details', "userid, is_student", "(firstname_plain LIKE ? OR prename LIKE ?) AND lastname_plain LIKE ?", $bind);

		if(!isset($d[0])){
      //only look for students as less chance of double match
			$bind = array($firstname[0].'%', $firstname[0].'%', $lastname);  //check for J Smith
			$d = $sql->select('u_details', "userid, is_student", "is_student =1 AND (firstname_plain LIKE ? OR prename LIKE ?) AND lastname_plain LIKE ?", $bind);

      if(!isset($d[0])){
				return '-';
			}else{
				if(count($d)>1) return 'Ambiguous';
				$userID = $d[0]['userid'];
				return $d[0]['is_student']==1 ? $this->getBoardingFromID($userID, $item) : 'Staff';
			}
		}else{
			$userID = $d[0]['userid'];

			return $d[0]['is_student']==1 ? $this->getBoardingFromID($userID, $item) : 'Staff';
		}
	}

	public function getBoardingFromPIN(int $pin, string &$name='null', array &$item)
  {
    $sql = $this->sql;
		global $user_school;
		$bind = array($pin . "@marlboroughcollege.org");
		$d = $sql->select('u_details', "*firstname, *lastname, userid", "email = *?", $bind);

		if(!isset($d[0])){
			$item['style'] = 'error';
			if($name) $name = '-';
			return "-";
		}else{
			if($name){ $name = $d[0]['firstname'] . ' ' . $d[0]['lastname'];}
			if(count($d)>1){ return "!!!!!";} //multiple results
			$userID = $d[0]['userid'];
			if($item) {
				$item['lastName'] =$d[0]['lastname'];
				$item['name'] = $name;
			}
			return $this->getBoardingFromID($userID, $item);
		}
	}

  public function makeName(array &$item)
  {
		if(!$this->names){ $this->names = $this->getAllKeyHolderNames(); }

		if(strlen($item['ID_2'])==4 && ($item['EVENT_ID'] == 2009 || $item['EVENT_ID'] == 2002)){
			 $item['name'] = "PIN (".$item['ID_2'].")";
			 $item['boarding'] = $this->getBoardingFromPIN($item['ID_2'], $item['name'], $item);
			 $item['style'] = 'PIN';
			 $this->errorEvents[] = $item;
		}elseif(strlen($item['ID_2'])>4 && ($item['EVENT_ID'] == 2009 || $item['EVENT_ID'] == 2002)){
			$item['name']='-';
			$item['boarding']='-';
			$item['style']='error';
			$this->errorEvents[] = $item;
			return;
		}else{
			if(isset($this->names['id_' . $item['ID_3']])){
				$item['name'] = $this->names['id_' . $item['ID_3']];
				$item['type'] = $this->types['id_' . $item['ID_3']];
				$item['boarding'] = $this->getBoardingFromName($item['name'], $item);
			}else{
				$item['name'] = '-';
				$item['style'] = 'error';
		  }
			$item['style'] = 'Card';
		}

		return $item['name'];
	}

	public function getArea(int $id)
  {
    $readerAry = $this->getReadersByArea($id);
		$events = $this->getEventsByReaders($readerAry);
    foreach($events as &$item){
		 $unix = strtotime($item['LOCAL_TIME']);
		 $item['entry_timestamp'] = date('d.m.y, g:i a', $unix);
		 $item['entry_time'] = date('G:i', $unix);
		 $item['entry_unix'] = $unix;
		 $item['name'] = $this->makeName($item);
		 $item['location'] = $this->getDoorNameByReader($item['ID_1']);
	 }

	 return $events;
	}

  public function log(string $message, bool $error = false)
  {
    $sql = $this->sql;
    $isError = $error ? 1 : 0;
    $sql->insert('s_cronLog', 'message, cron_id, error', array($message, $this->id, $isError));
    if($error) $message = "!! " . $message;
    array_unshift($this->logArray , $message);

  }

  private function connect()
  {
    $serverName = "172.16.6.5";
  	$database = "EXGARDE";
    $uid = "DBUSER2";
	  $pwd = "T3chnical";

	  //Establishes the connection
	  $this->conn = new \PDO( "sqlsrv:server=$serverName ; Database = $database", $uid, $pwd);

    if (!$this->conn) {
       throw new Exception('Exgarde Connection Unavailiable');
    }

    return $this->conn;
  }

  private function query($query, $binding)
  {
    $conn = $this->conn;
    $this->STH = $this->conn->prepare($query);
    $this->STH->execute($binding);
    $this->STH->setFetchMode(PDO::FETCH_ASSOC);

    return $this->STH->fetchAll();
  }

	private function getReaderByDoor($doorID)
  {
	 $binding = array($doorID);
 	 $query = "SELECT [READER_ID] FROM dbo.DOOR_TO_READER_View WHERE [DOOR_ID] = ?";
 	 $data = $this->query($query, $binding);

	 return $data[0]['READER_ID'];
	}

	private function getDoorNameByReader($reader)
  {
	 if(isset($this->ary_doorNamesByReader['id_'.$reader])) return $this->ary_doorNamesByReader['id_'.$reader];

	 $binding = array($reader);
 	 $query = "SELECT [DOOR_ID] FROM dbo.DOOR_TO_READER_View WHERE [READER_ID] = ?";

 	 $doorID = $this->query($query, $binding)[0]['DOOR_ID'];
	 $binding = array($doorID);
	 $query = "SELECT [NAME] FROM dbo.DOOR_View WHERE [ID] = ?";

	 $data = $this->query($query, $binding);
	 $name = $data[0]['NAME'];

	 $this->ary_doorNamesByReader['id_' . $reader] = $name;

	 return $name;
	}

	private function getReadersByArea($area)
  {
	 $binding = array($area);
 	 $query = "SELECT [READER_ID] FROM dbo.DOOR_TO_READER_View WHERE [AREA_TO_ID] = ?";
 	 $data = $this->query($query, $binding);
	 $return = array();

	 foreach($data as $reader){
		 $return[] = $reader['READER_ID'];
	 }

	 return $return;
	}

	private function getKeyHolderName($id, &$type = null)
  {
		$binding = array($id);
		$query = "SELECT [NAME], [COMMENT] FROM dbo.KEYHOLDER_View WHERE [ID]= ?";
		$data = $this->query($query, $binding);

		$type = ($data[0]['COMMENT'] && !is_null($data[0]['NAME']))== 'Student' ? 'Student' : 'Staff';

		return $data[0]['NAME'];
	}

	private function getSupportNames()
  {
		$binding = array();
		$query = "SELECT [ID], [NAME] FROM dbo.KEYHOLDER_View WHERE [COMMENT] <> 'Teaching Staff' AND [COMMENT] <> 'Student'";
		$data = $this->query($query, $binding);
		$names = array();

		foreach($data as $item){
			$names['id_'. $item['ID']] = $item['NAME'];
		}

		$this->names = $names;
	}

	private function getCRNames()
  {
		$binding = array();
		$query = "SELECT [ID], [NAME] FROM dbo.KEYHOLDER_View WHERE [COMMENT] = 'Teaching Staff'";
		$data = $this->query($query, $binding);
		$names = array();

		foreach($data as $item){
			$names['id_'. $item['ID']] = $item['NAME'];
		}

		$this->names = $names;
	}

	private function getStudentNames()
  {
		$binding = array();
		$query = "SELECT [ID], [NAME] FROM dbo.KEYHOLDER_View WHERE [COMMENT] = 'Student'";
		$data = $this->query($query, $binding);
		$names = array();

		foreach($data as $item){
			$names['id_'. $item['ID']] = $item['NAME'];
		}

		$this->names = $names;
		return $names;
	}

	private function getAllKeyHolderNames()
  {
		$binding = array();
		$query = "SELECT [ID], [NAME], [COMMENT] FROM dbo.KEYHOLDER_View";
		$data = $this->query($query, $binding);

		$names = array();
		$types = array();

		foreach($data as $item){
			$names['id_'. $item['ID']] = $item['NAME'];
			//some comments have a house abbreviation after student
			$types['id_'. $item['ID']] = strtolower(explode(' ',trim($item['COMMENT']))[0]) == 'student' ? 'Student' : '-';
		}

		$this->names = $names;
		$this->types = $types;

		return $names;
	}

	private function getAllKeyHolderComments()
  {
		$binding = array();
		$query = "SELECT [ID], [COMMENT] FROM dbo.KEYHOLDER_View";
		$data = $this->query($query, $binding);
		$names = array();

		foreach($data as $item){
			$names['id_'. $item['ID']] = $item['COMMENT'];
		}

		return $names;
	}

	private function getEventsByReader($id, $unixDate = null)
  {
		if($unixDate){
		 $dayStart = date('Y-m-d 00:00:00', $unixDate);
	 	 $dayEnd = date('Y-m-d 23:59:59', $unixDate);

	 	 $binding = array($id, $dayStart, $dayEnd);
		 $query = "SELECT [UNIQUE_ID], [UNIQUE_ID] as id, [ID_1], [ID_3], [ID_2], [LOCAL_TIME], [EVENT_ID]
		 					 FROM dbo.EVENT_LOG_View
							 WHERE [ID_1] = ?  AND ([EVENT_ID] = 2001 OR [EVENT_ID] = 2002 OR [EVENT_ID] = 2009) AND [LOCAL_TIME] > ? AND [LOCAL_TIME] < ?
							 ORDER BY [LOCAL_TIME] DESC";

	 	 $data = $this->query($query, $binding);
	 } else{
		 $binding = array($id);
  	 $query = " SELECT TOP 100 [UNIQUE_ID], [UNIQUE_ID] as id, [ID_1], [ID_3], [ID_2], [LOCAL_TIME], [EVENT_ID]
                FROM dbo.EVENT_LOG_View
                WHERE [ID_1] = ? AND ([EVENT_ID] = 2001 OR [EVENT_ID] = 2002 OR [EVENT_ID] = 2009)
                ORDER BY [LOCAL_TIME] DESC";
	 }

	 $result = $this->query($query, $binding);

		return $result;
	}

	private function getEventsByReaders($readersAry, $unixDate = null)
  {
		$this->unix = $unixDate;
		$this->readers = $readersAry;
		$this->date = date('Y-m-d', $unixDate);

		if($unixDate){
		 $dayStart = date('Y-m-d 00:00:00', $unixDate);
	 	 $dayEnd = date('Y-m-d 23:59:59', $unixDate);
	 	 $binding = array($dayStart, $dayEnd);
		 $orString = '';
		 $flag = false;

		 foreach($readersAry as $reader){
			 $binding[] = $reader;
			 if($flag) $orString .= 'OR [ID_1] = ? ';
			 $flag = true;
		 }

		 $query = "SELECT [UNIQUE_ID], [UNIQUE_ID] as id, [ID_1], [ID_2], [ID_3], [LOCAL_TIME], [EVENT_ID]
		 					 FROM dbo.EVENT_LOG_View
							 WHERE ([EVENT_ID] = 2001 OR [EVENT_ID] = 2002 OR [EVENT_ID] = 2009)  AND [LOCAL_TIME] > ? AND [LOCAL_TIME] < ? AND ([ID_1] = ? $orString )
							 ORDER BY [LOCAL_TIME] DESC";

	 	 $data = $this->query($query, $binding);
	 } else{
		 $binding = array(2001);
		 $orString = '';
		 $flag = false;

		 foreach($readersAry as $reader){
			 $binding[] = $reader;
			 if($flag) $orString .= 'OR [ID_1] = ? ';
			 $flag = true;
		 }

  	 $query = "SELECT TOP 100 [UNIQUE_ID], [UNIQUE_ID] as id, [ID_1], [ID_2], [ID_3], [EVENT_ID], [LOCAL_TIME]
		 					 FROM dbo.EVENT_LOG_View
							 WHERE [EVENT_ID] = ? AND ([ID_1] = ? $orString )
		 					 ORDER BY [LOCAL_TIME] DESC";
	 }

	  $this->events = $this->query($query, $binding);
		return  $this->events;

	}



}
