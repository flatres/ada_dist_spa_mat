<?php

namespace Exams\Tools;

class Cache {

		public function __construct(\Slim\Container $container)
    {
      $this->adaModules = $container->adaModules;
			ini_set('memory_limit', '1024M'); // or you could use 1G
   	}

		public function exists(int $sessionId, bool $isGCSE)
		{
			$gcse = $isGCSE ? 1  : 0;
			$data = [];
      $session = $this->adaModules->select('exams_cache', 'timestamp', 'sessionId = ? AND isGCSE = ?', [$sessionId, $gcse]);

			try {
				if (isset($session[0])) {
					return $session[0]['timestamp'];
				}
				return false;
			} catch (Exception $e) {
				return false;
			}
		}

		public function deleteAll()
		{
			$this->adaModules->delete('exams_cache', 'sessionId > 0', []);
		}

		public function delete($id)
		{
			$this->adaModules->delete('exams_cache', 'sessionId = ?', [$id]);
		}

    public function read(int $sessionId, bool $isGCSE)
    {
      $gcse = $isGCSE ? 1  : 0;
			$data = [];
      $session = $this->adaModules->select('exams_cache', 'data, timestamp', 'sessionId = ? AND isGCSE = ?', [$sessionId, $gcse]);

			try {
				if (isset($session[0])) {
					$data = json_decode($session[0]['data'], TRUE);
					$data['timestamp'] = $session[0]['timestamp'];
					return $data;
				}
				return false;
			} catch (Exception $e) {
				return false;
			}
    }

	  public function write(int $sessionId, bool $isGCSE, array $data)
    {
		    // $data = base64_encode(serialize($data));
				// $data = base64_encode(json_encode($data));
				$data = json_encode($data);
        $gcse = $isGCSE ? 1  : 0;
				// $data = 'abcdefg';
        $this->adaModules->delete('exams_cache', 'sessionId = ? AND isGCSE = ?', [$sessionId, $gcse]);
        $this->adaModules->insert('exams_cache', "sessionId, isGCSE, data", [$sessionId, $gcse, $data]);
		}

}

?>
