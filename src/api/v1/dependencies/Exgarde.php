<?php
namespace Dependency;
use \PDO;

define('EXGARDE_IP', getenv("EXGARDE_IP"));
define('EXGARDE_DB', getenv("EXGARDE_DB"));
define('EXGARDE_USER', getenv("EXGARDE_USER"));
define('EXGARDE_PWD', getenv("EXGARDE_PWD"));

class Exgarde {

	public $types = [];
	public $students = [];

	public $adaStudents = [];
	public $adaMatched = [];
	public $adaUnmatched = [];
	public $adaNames = [];
	public $adaModules;

	public $names = [];
	public $comments = [];
	public $namesForSearch = []; // all lowercase with spaces removed

	public function __construct(\Dependency\Mysql $mysql = null)
	{
    date_default_timezone_set("Europe/London");
    $this->ary_doorNamesByReader = array();
		$this->errorEvents = array();
		$this->names = null;
    $this->sql = $mysql;
		$this->adaModules = new \Dependency\Databases\AdaModules();
    $this->connect();
	}

	private function connect()
  {
    $serverName = EXGARDE_IP;
  	$database = EXGARDE_DB;
    $uid = EXGARDE_USER;
	  $pwd = EXGARDE_PWD;

	  try {
			//Establishes the connection
	  	$this->conn = new \PDO( "sqlsrv:server=$serverName ; Database = $database; LoginTimeout=2", $uid, $pwd);
    }
		catch(\PDOException $e) {
			throw $e;
		}
    return $this->conn;
  }

	public function initialiseTest(\Entities\People\AllStudents $allStudents)
	{
		$this->adaStudents = $allStudents->keyedStudentIds;
		$this->getAllKeyHolderNames();
	}

	//does a basic search and returns true if there is exactly one match
	private function search($search, &$exgardeId){
		$filtered = array_filter($this->namesForSearch, function($k) use ($search) {
				//if a student is put into the system and a staff member al
				// if (str_word_count)
		    return strstr($k, $search) !== FALSE;
		});

		if ($filtered && count($filtered) === 1) {
			// https://stackoverflow.com/questions/1028668/get-first-key-in-a-possibly-associative-array
			reset($filtered);
			$exgardeId = str_replace('id_', '', key($filtered));
			return true;
		} else {
			return false;
		};
	}

	// https://www.php.net/manual/en/function.similar-text.php
	private function similarSearch($search, &$exgardeId){
		$similarity = [];
		foreach($this->namesForSearch as $key => $name){
			similar_text($search, $name, $percentage);
			$similarity[$key] = $percentage;
		}
		// var_dump($similarity);
		arsort($similarity);
		// https://www.php.net/manual/en/function.key.php
		if (reset($similarity) > 80) {
				$exgardeId = str_replace('id_', '', key($similarity));
				return reset($similarity);
		}
		return false;
	}

  public function unmatch(int $studentId)
	{
		$this->adaModules->delete('watch_exgarde_students', 'student_id=?', [$studentId]);
	}

	public function match(\Entities\People\Student &$student) {
		if(count($this->adaStudents) == 0) $this->initialiseTest(new \Entities\People\AllStudents());
		$exgardeId = 0;
		$fullName = $this->sanitize($student->fullName);
		$this->adaNames[] = $fullName;
		$fullPreName = $this->sanitize($student->fullPreName);
		$firstName = $this->sanitize($student->firstName);
		$preName = $this->sanitize($student->preName);
		$lastName = $this->sanitize($student->lastName);

		$search = $this->namesForSearch;
		if ($this->search($fullName, $exgardeId)) {
			$this->adaMatched($student, $exgardeId);
			$student->matched  = 'name';
		} else {
			if ($this->search($fullPreName, $exgardeId)) {				// echo $fullPreName;
				$this->adaMatched($student, $exgardeId);
				$student->matched  = 'prename';
			} else {
				$search = $this->similarSearch($fullName, $exgardeId);
				if ($search){
						$this->adaMatched($student, $exgardeId);
						$student->matched  = 'similar';
						$student->similarity = $search;
				} else {
					$search = $this->similarSearch($fullPreName, $exgardeId);
					if ($search){
							$this->adaMatched($student, $exgardeId);
							$student->matched  = 'similar';
							$student->similarity = $search;
					} else {
							$this->adaUnmatched($student);
							$student->matched  = 'none';
							return false;
					}
				}
			}
		}
		$student->exgardeId = $exgardeId;
		$student->exgardeName = $this->names['id_'.$exgardeId];
		$student->exgardeComment = $this->comments['id_'.$exgardeId];

		// $exgardeId = false;
		// $fl_array = preg_grep("/^(\d+)?\.\d+$/", $array);
		//
		return $exgardeId;
	}

	private function adaMatched(\Entities\People\Student &$student, int $exgardeId = 0) {
		$student->exgardeId = $exgardeId;
		$this->adaMatched['id_'.$student->id] = $student;
		$adaModules = $this->adaModules;
		$adaModules->delete('watch_exgarde_students', 'student_id=?', [$student->id]);
		$adaModules->insert('watch_exgarde_students', 'student_id, exgarde_id', [$student->id, $exgardeId]);
	}

	private function adaUnMatched(\Entities\People\Student &$student) {
		$this->adaUnmatched['id_'.$student->id] = $student;
		$this->adaModules->delete('watch_exgarde_students', 'student_id=?', [$student->id]);
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

	public function getArea(int $id, $forceAllEvents = false)
  {
    $readerAry = $this->getReadersByArea($id);
		$events = $this->getEventsByReaders($readerAry, null, $forceAllEvents);
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

	public function getPerson(int $id)
  {

		$events = $this->getEventsByPerson($id);

	 foreach($events as &$item){
		 $unix = strtotime($item['LOCAL_TIME']);
		 $item['entry_timestamp'] = date('d.m.y, g:i a', $unix);
		 $item['entry_time'] = date('G:i', $unix);
		 $item['entry_unix'] = $unix;
		 $item['name'] = $this->makeName($item);
	 }

	 return $events;
	}

	public function getPersonByDate(int $unix, int $id)
  {
		$events = $this->getEventsByPerson($id, $unix);

		 foreach($events as &$item){
			 $unix = strtotime($item['LOCAL_TIME']);
			 $item['entry_timestamp'] = date('d.m.y, g:i a', $unix);
			 $item['entry_time'] = date('G:i', $unix);
			 $item['entry_unix'] = $unix;
			 $item['name'] = $this->makeName($item);
		 }

	 return $events;
	}

	public function getAreaByDate(int $unix, int $id, bool $studentsOnly = false)
  {
    $names = $this->getAllKeyHolderNames();
    $readerAry = $this->getReadersByArea($id);
    $events = $this->getEventsByReaders($readerAry, $unix);

		$finalEvents = [];
    foreach($events as &$item){
    	$unix = strtotime($item['LOCAL_TIME']);
      $item['entry_timestamp'] = date('d.m.y, g:i a', $unix);
     	$item['entry_time'] = date('G:i', $unix);
      $item['entry_unix'] = $unix;
			$item['type'] = '';
      $item['name'] = $this->makeName($item);
      $item['location'] = $this->getDoorNameByReader($item['ID_1']);
			if ($studentsOnly){
				if ($item['type'] == 'Student') $finalEvents[] = $item;
			}else {
				$finalEvents[] = $item;
			}
    }

    return $finalEvents;
	}

	public function getBoardingFromID(int $userID, array &$item)
  {
    $sql = $this->sql;
    $this->houses = array();
		$item['ada_id'] = $userID;

		$student = new \Entities\People\Student();
		return $student->byId($userID)->boardingHouse;

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
		$d = $sql->select('stu_details', "id", "(firstname LIKE ? OR prename LIKE ?) AND lastname LIKE ?", $bind);

		if(!isset($d[0])){
      //only look for students as less chance of double match
			$bind = array($firstname[0].'%', $firstname[0].'%', $lastname);  //check for J Smith
			$d = $sql->select('stu_details', "id", "(firstname LIKE ? OR prename LIKE ?) AND lastname LIKE ?", $bind);

      if(!isset($d[0])){
				return '-';
			}else{
				if(count($d)>1) return 'Ambiguous';
				$userID = $d[0]['id'];
				return $this->getBoardingFromID($userID, $item);
			}
		}else{
			$userID = $d[0]['id'];

			return $this->getBoardingFromID($userID, $item);
		}
	}

	public function getBoardingFromPIN(int $pin, string &$name='null', array &$item)
  {
    $sql = $this->sql;
		global $user_school;
		$bind = array($pin . "@marlboroughcollege.org");
		$d = $sql->select('stu_details', "firstname, lastname, userid", "email = ?", $bind);

		if(!isset($d[0])){
			$item['style'] = 'error';
			if($name) $name = '-';
			return "-";
		}else{
			if($name){ $name = $d[0]['firstname'] . ' ' . $d[0]['lastname'];}
			if(count($d)>1){ return "!!!!!multiples";} //multiple results
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
		$exgardeId = $item['ID_3'];
		$key = 'id_' . $exgardeId;

		if(strlen($item['ID_2'])==4 && ($item['EVENT_ID'] == 2009 || $item['EVENT_ID'] == 2002)){
			 $item['name'] = "PIN (".$item['ID_2'].")";
			 $student = new \Entities\People\Student(new \Dependency\Databases\Ada);
			 $student->bySchoolNumber($item['ID_2']);
			 $item['ada_id'] = $student->id ?? null;
			 $item['name'] = $student->fullName ?? null;
			 $item['lastName'] = $student->lastName ?? null;
			 $item['type'] = 'Student';
			 $item['boarding'] = $student->boardingHouse ?? null;
			 $item['style'] = 'PIN';
			 $this->errorEvents[] = $item;
	  ///commented out as some keypads are used for registering that the students don't actually have authorisation to access
		// }elseif(strlen($item['ID_2'])>4 && ($item['EVENT_ID'] == 2009 || $item['EVENT_ID'] == 2002)){
		// 	$item['name']='-';
		// 	$item['boarding']='-';
		// 	$item['style']='error';
		// 	$this->errorEvents[] = $item;
		// 	return;
		}else{
			if(isset($this->names[$key])){
				//check to see if it's a known, matched student
				$d = $this->adaModules->select('watch_exgarde_students', 'student_id', 'exgarde_id=?', [$exgardeId]);
				if(isset($d[0])){
					$studentId = $d[0]['student_id'];
					$student = new \Entities\People\Student(new \Dependency\Databases\Ada, $studentId);
					$item['ada_id'] = $studentId;
					$item['name'] = $student->fullName;
					$item['lastName'] = $student->lastName;
					$item['type'] = 'Student';
					$item['boarding'] = $student->boardingHouse;
				} else {
					$item['name'] = $this->names[$key];
					$item['type'] = $this->types[$key];
					$item['boarding'] = isset($this->students[$key]) ? $this->getBoardingFromName($item['name'], $item) : '-';
				}
			}else{
				$item['name'] = '-';
				$item['style'] = 'error';
		  }
			$item['style'] = 'Card';
		}

		return $item['name'];
	}

  public function log(string $message, bool $error = false)
  {
    $sql = $this->sql;
    $isError = $error ? 1 : 0;
    $sql->insert('s_cronLog', 'message, cron_id, error', array($message, $this->id, $isError));
    if($error) $message = "!! " . $message;
    array_unshift($this->logArray , $message);

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
		$query = "SELECT [ID], [NAME] FROM dbo.KEYHOLDER_View WHERE [COMMENT] LIKE 'Student'";
		$data = $this->query($query, $binding);
		$names = array();

		foreach($data as $item){
			$names['id_'. $item['ID']] = $item['NAME'];
		}

		$this->names = $names;
		return $names;
	}

	private function sanitize($name){

		$nospaces = str_replace(" ", '', $name);
		// https://stackoverflow.com/questions/4389297/replace-foreign-characters
		// $nospaces = iconv("UTF-8", "ASCII//TRANSLIT", $nospaces);
		$nospaces = strtr($nospaces, "ÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÑÒÓÔÕÖØÝßàáâãäåçèéêëìíîïñòóôõöøùúûüýÿ","AAAAAACEEEEIIIINOOOOOOYSaaaaaaceeeeiiiinoooooouuuuyy");
		$lc = strtolower($nospaces);

		return $lc;
	}

	private function getAllKeyHolderNames()
  {
		$binding = array();
		$query = "SELECT [ID], [NAME], [COMMENT] FROM dbo.KEYHOLDER_View";
		$data = $this->query($query, $binding);

		$names = [];
		$namesForSearch = [];
		$comments = [];
		$types = [];
		$students = [];

		foreach($data as $item){
			//some comments have a house abbreviation after student
			$commentLower = strtolower(explode(' ',trim($item['COMMENT']))[0]);
			$isStudent = strpos($commentLower, 'student') !== false;

			$key = 'id_'. $item['ID'];
			$names[$key] = $item['NAME'];
			$comments[$key] = $item['COMMENT'];
			$namesForSearch[$key] = $this->sanitize($item['NAME']);

			if ($isStudent) $students[$key] = true;
			$types[$key] = $isStudent ? 'Student' : '-';
		}

		$this->names = $names;
		$this->comments = $comments;
		$this->namesForSearch = $namesForSearch;
		$this->types = $types;
		$this->students = $students;

		return $names;
	}

	//simplar to getAllKeyHolderNames but returns in a format suitable for viewing
	public function getAllPeople()
  {
		$binding = array();
		$query = "SELECT [ID], [NAME], [COMMENT] FROM dbo.KEYHOLDER_View";
		$data = $this->query($query, $binding);

		$names = array();
		$list = [];

		foreach($data as $item){
			$id = $item['ID'];
			$key = 'id_' . $id;
			if (!isset($names[$key])){

					$isStudent = strtolower(explode(' ',trim($item['COMMENT']))[0]) == 'student';
					$names['id_'. $id] = $item['NAME'];
					$d = [
						'id'	=> $id,
						'name'	=> $item['NAME'],
						'type' => $isStudent ? 'Student' : '-',
						'comment' => $item['COMMENT'],
						'isStudent' => $isStudent,
					];
					$list[] = $d;
			}
		}
		return $list;
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

	private function getEventsByPerson($id, $unixDate = null)
  {
		if($unixDate){
		 $dayStart = date('Y-m-d 00:00:00', $unixDate);
	 	 $dayEnd = date('Y-m-d 23:59:59', $unixDate);

	 	 $binding = array($id, $dayStart, $dayEnd);
		 $query = "SELECT [UNIQUE_ID], [UNIQUE_ID] as id, [ID_1], [ID_3], [ID_2], [LOCAL_TIME], [EVENT_ID]
		 					 FROM dbo.EVENT_LOG_View
							 WHERE [ID_3] = ?  AND ([EVENT_ID] = 2001 OR [EVENT_ID] = 2002 OR [EVENT_ID] = 2009) AND [LOCAL_TIME] > ? AND [LOCAL_TIME] < ?
							 ORDER BY [LOCAL_TIME] DESC";

	 	 $data = $this->query($query, $binding);
	 } else{
		 $binding = array($id);
  	 $query = " SELECT TOP 1000 [UNIQUE_ID], [UNIQUE_ID] as id, [ID_1], [ID_3], [ID_2], [LOCAL_TIME], [EVENT_ID]
                FROM dbo.EVENT_LOG_View
                WHERE [ID_3] = ? AND ([EVENT_ID] = 2001 OR [EVENT_ID] = 2002 OR [EVENT_ID] = 2009)
                ORDER BY [LOCAL_TIME] DESC";
	 }

	  $results = $this->query($query, $binding);
		foreach($results as &$result){
			$result['location'] = $this->getDoorNameByReader($result['ID_1']);
		}

		return $results;
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
  	 $query = " SELECT TOP 1000 [UNIQUE_ID], [UNIQUE_ID] as id, [ID_1], [ID_3], [ID_2], [LOCAL_TIME], [EVENT_ID]
                FROM dbo.EVENT_LOG_View
                WHERE [ID_1] = ? AND ([EVENT_ID] = 2001 OR [EVENT_ID] = 2002 OR [EVENT_ID] = 2009)
                ORDER BY [LOCAL_TIME] DESC";
	 }

	 $result = $this->query($query, $binding);

		return $result;
	}

	private function getEventsByReaders($readersAry, $unixDate = null, $forceAllEvents = false)
  {
		$this->unix = $unixDate;
		$this->readers = $readersAry;
		$this->date = date('Y-m-d', $unixDate);

		$eventString = $forceAllEvents ? '' : '([EVENT_ID] = 2001 OR [EVENT_ID] = 2002 OR [EVENT_ID] = 2009)  AND';

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
							 WHERE $eventString [LOCAL_TIME] > ? AND [LOCAL_TIME] < ? AND ([ID_1] = ? $orString )
							 ORDER BY [LOCAL_TIME] DESC";

	 	 $data = $this->query($query, $binding);
	 } else{
		 // $binding = array(2001);
		 $binding = [];
		 $orString = '';
		 $flag = false;

		 foreach($readersAry as $reader){
			 $binding[] = $reader;
			 if($flag) $orString .= 'OR [ID_1] = ? ';
			 $flag = true;
		 }

  	 // $query = "SELECT TOP 1000 [UNIQUE_ID], [UNIQUE_ID] as id, [ID_1], [ID_2], [ID_3], [EVENT_ID], [LOCAL_TIME]
		 // 					 FROM dbo.EVENT_LOG_View
			// 				 WHERE [EVENT_ID] = ? AND ([ID_1] = ? $orString )
		 // 					 ORDER BY [LOCAL_TIME] DESC";

		 $query = "SELECT TOP 1000 [UNIQUE_ID], [UNIQUE_ID] as id, [ID_1], [ID_2], [ID_3], [EVENT_ID], [LOCAL_TIME]
 							FROM dbo.EVENT_LOG_View
 							WHERE $eventString ([ID_1] = ? $orString )
 							ORDER BY [LOCAL_TIME] DESC";
	 }

	  $this->events = $this->query($query, $binding);
		return  $this->events;

	}



}
