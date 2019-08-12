<?php
namespace Dependency;

 /* Handling database connection */


/*Examples

$sql = new SQL();

//general format function(table, fields, condition, binding)

//INSERT returns new insert id
$id = $sql->insert('encrypt', '*name, *email ', array('ss', 'bob@gmail.com'));

//SELECT returns an array of results
$result = $sql->query('encrypt', 'id, *name, *email', 'email=*?', array('bob@gmail.com'));

//UPDATE : returns rowcount
$sql->update('encrypt', 'name=*?, email = *?', 'email=*?', array('cuthy', 's@g.com', 'bob@gmail.com'));

//DELETE : returns rowcount
$sql->delete('encrypt', 'email=*?', array('s@g.com'));

//ROWCOUNT
$sql->rowCount()

*/


class MSSql {

    public $conn;
	  private $query;
		private $queryType;
		public $writeLog = TRUE;
	  private $selectType = "ALL";
		public $rowCount;
    public $dbName;

    // https://dzone.com/articles/how-correctly-work-php
    public function __sleep()
    {
       return array('dbName');
    }

		public function connect($ip, $db,  $user, $pwd) {

       try {

           $this->conn = new \PDO( "sqlsrv:server=$ip ; Database = $db", $user, $pwd);

        }

       catch(PDOException $e) {
         echo "Failed to connect to MySQL: " . $e->getMessage();
           return NULL;
       }


   	}

    public function updateObject($table, $object, $idField){
      $fieldString = '';
      $binding = array();
      $id = $object[$idField];
      unset($object[$idField]);
      unset($object['__index']); //a vue variable
      $comma = ' ';

      foreach ($object as $key => $value) {
        $fieldString .= $comma . $key . '=?';
        $comma = ', ';
        $binding[] = $value;
      }
      $condition = $idField . '=?';
      $binding[] = $id;

      return $this->update($table, $fieldString, $condition, $binding);
    }

		public function update($table, $fieldString, $condition = NULL, $binding = NULL){

			$this->reset();

			$this->queryType = 'UPDATE';
			$this->binding = $binding;
			$this->table = $table;

			$condition = "WHERE ($condition)";

		 	$this->query = "UPDATE $table SET $fieldString $condition";

 			return $this->execute();

		}

    public function dateFromUnix($unix){

      return date('Y-m-d', $unix);

    }


	 	public function select($table, $fieldString, $condition = NULL, $binding = NULL, $isCaseInsensitive = FALSE){

			$this->reset();

			$this->queryType = 'SELECT';
      $this->binding = $binding;

			$this->table = $table;

			//add encryption gubbins for the field string
      $fieldString = str_replace(' as ', '@', $fieldString);
			$fieldString = str_replace(' ', '', $fieldString);
      $fieldString = str_replace('@', ' as ', $fieldString);

			$explode = explode(',', $fieldString);
			$flag = 0;

			$fieldString = implode(', ', $explode);

			$condition = "WHERE $condition";

		 	$this->query = "SELECT $fieldString from $table $condition";

 			return $this->execute();

		}



		//allows an arbitrary query. Doesn't allow encryption
		public function query($query, $binding = NULL, $isCaseInsensitive = FALSE){

			$this->reset();

			$this->queryType = 'SELECT';

			$this->isCaseInsensitive = $isCaseInsensitive;

			if($binding){

				$this->binding = $binding;

			}else{

				$this->binding = array();

			}

		 	$this->query = $query;

 			return $this->execute();

		}



		public function delete($table, $condition = NULL, $binding = NULL){

			$this->reset();

			if($condition==NULL){return NULL;} //just in case deletes all

			$this->queryType = 'DELETE';
			$this->binding = $binding;
			$this->table = $table;

			$condition = "WHERE ($condition)";

		 	$this->query = "DELETE from $table $condition";

 			return $this->execute();

		}

    public function insertObject($table, $object){
      $fieldString = '';
      $binding = array();
      $comma = '';
      foreach ($object as $key => $value) {
        $fieldString .= $comma . ' ' . $key;
        $comma = ',';
        $binding[] = $value;
      }
      return $this->insert($table, $fieldString, $binding);
    }

		public function insert($table, $fieldString, $binding = NULL){

			$this->reset();

			$this->queryType = 'INSERT';
			$this->binding = $binding;
			$this->table = $table;

			$fieldString = str_replace(' ', '', $fieldString);
			$explode = explode(',', $fieldString);

			$qMarks = ''; $flag = 0;

			foreach($explode as &$item){

				$mark = '?';

				if($flag ==0){
					$qMarks = $mark;
					$flag = 1;
				}else{

					$qMarks .= ", $mark";
				}

			}

			$fieldString = implode(',', $explode);

			$this->query = "INSERT INTO $table($fieldString) values($qMarks)";

			return $this->execute();


		}

		private function xss_Safe($binding){

			//disables as using escaping via the underscore templates
			return $binding;

			foreach($binding as &$item){

				foreach($item as &$data){

					!is_numeric($data) ? $data = htmlspecialchars($data) : null;
				}

			}

			return $binding;

		}


	  public function execute(){

			$this->STH = $this->conn->prepare($this->query);
			$this->STH->execute($this->binding);

			$return = NULL;

			switch($this->queryType){

				case 'SELECT':

					$this->STH->setFetchMode(\PDO::FETCH_ASSOC);

					$return = $this->xss_Safe($this->STH->fetchAll());

					$this->rowCount = $this->rowCount();

					break;


				case 'INSERT':

						// if($this->writeLog) {$return = $this->conn->lastInsertId();}
            $return = $this->conn->lastInsertId();
            break;

				case 'DELETE' :

						$return = $this->rowCount(); break;

				case 'UPDATE' :

						$return = $this->rowCount(); break;


			}

			if($this->writeLog){$this->log();}

			return $return;

		}


	  public function rowCount(){

			return $this->STH->rowCount();

		}

	  private function reset(){

			$this->query = '';
			$this->queryType = '';


		}

		private function log(){
			return;
			global $user_id;

			$table = $this->table;
			$type = $this->queryType;
			$time = date("d/m/y : H:i:s", time());
			$query = $this->query;
			$ip = $_SERVER['REMOTE_ADDR'];

			$data = array($table, $type, $time, $query, $user_id, $ip);

// 			$sql = new SQL();

			$this->writeLog = FALSE;
			$this->insert('l_db', "tbl, type, time, query, user, *ip", $data);
			$this->writeLog = TRUE;



		}


}

?>
