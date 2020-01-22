<?php



  function getStringBetween($string, $start, $end)
  {
    $string = ' ' . $string;
    $ini = strpos($string, $start);
    if ($ini == 0) return '';
    $ini += strlen($start);
    $len = strpos($string, $end, $ini) - $ini;
    return substr($string, $ini, $len);
  }
// https://stackoverflow.com/questions/15538687/using-php-to-stream-data-of-programs-such-as-htop
  function getResources()
  {

        $top = '';
        $cpuIdle = getUbuntuCPU();
        $memTotal = 0;
        $memFree = 0;

        return [
          'cpuIdle' => $cpuIdle,
          'memTotal'  => $memTotal,
          'memFree' => $memFree,
          'string'  =>  $top
        ];



    // return system("top -n 1");
  }
  // https://gist.github.com/rlemon/1780212
  function getUbuntuCPU() {
    /* get core information (snapshot) */
    $stat1 = GetCoreInformation();
    /* sleep on server for one second */
    sleep(0.5);
    /* take second snapshot */
    $stat2 = GetCoreInformation();
    /* get the cpu percentage based off two snapshots */
    $cpu = GetCpuPercentages($stat1, $stat2);

    $i = 0;
    $sum = 0;
    foreach($cpu as $c) {
      $sum = $sum + $c['idle'];
      $i++;
    }
    if ($i == 0) return 0;
    return round($sum / $i, 2);

  }

  /* Gets individual core information */
  function GetCoreInformation() {
  	$data = file('/proc/stat');
  	$cores = array();
  	foreach( $data as $line ) {
  		if( preg_match('/^cpu[0-9]/', $line) )
  		{
  			$info = explode(' ', $line );
  			$cores[] = array(
  				'user' => $info[1],
  				'nice' => $info[2],
  				'sys' => $info[3],
  				'idle' => $info[4]
  			);
  		}
  	}
  	return $cores;
  }
/* compares two information snapshots and returns the cpu percentage */
function GetCpuPercentages($stat1, $stat2) {
	if( count($stat1) !== count($stat2) ) {
		return;
	}
	$cpus = array();
	for( $i = 0, $l = count($stat1); $i < $l; $i++) {
		$dif = array();
		$dif['user'] = $stat2[$i]['user'] - $stat1[$i]['user'];
		$dif['nice'] = $stat2[$i]['nice'] - $stat1[$i]['nice'];
		$dif['sys'] = $stat2[$i]['sys'] - $stat1[$i]['sys'];
		$dif['idle'] = $stat2[$i]['idle'] - $stat1[$i]['idle'];
		$total = array_sum($dif);
		$cpu = array();
		foreach($dif as $x=>$y) {
      $cpu[$x] = $total == 0 ? 0 : round($y / $total * 100, 1);
    }

		$cpus['cpu' . $i] = $cpu;
	}
	return $cpus;
}

var_dump(getResources())

}
