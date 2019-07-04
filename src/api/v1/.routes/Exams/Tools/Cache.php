<?php

namespace Exams\Tools;

class Cache {

		public function __construct(\Slim\Container $container)
    {
      $this->adaModules = $container->adaModules;
   	}

    public function read(int $sessionId, bool $isGCSE)
    {
      $gcse = $isGCSE ? 1  : 0;
			$data = [];
      $session = $this->adaModules->select('exams_cache', 'data, timestamp', 'sessionId = ? AND isGCSE = ?', [$sessionId, $gcse]);
			if (isset($session[0])) {
				$data = json_decode($session[0]['data'], TRUE);
				$data['timestamp'] = $session[0]['timestamp'];
				return $data;
			}
			return false;
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
