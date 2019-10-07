<?php

namespace SMT\Tools\Watch;

class Chapel{

  const EXGARDE_AREA = 10669778; //MC Attendance
  // const EXGARDE_AREA = 10512410; //GYM

  const MEM_AREA1 = 10670384; //pupil access
  const MEM_AREA2 = 28159791;

  function __construct($ada, $adaModules, $isams, $mcCustom, $exgarde)
  {
    $this->debug = true;

    $this->ada = $ada;
    $this->adaModules = $adaModules;
    $this->isams = $isams;
    $this->mcCustom = $mcCustom;
    $this->exgarde = $exgarde;

    $this->c_Privs = new \SMT\Tools\Watch\Privs($ada, $isams, $mcCustom);
  }

  public function sendAllHouseEmails($unixDate = null)
  {
    if($unixDate == null) $unixDate = time();
    // $unixDate = $unixDate - 24*60*60;
    // $unixDate = strtotime('yesterday');
    $this->reportAllByDate($unixDate);

    foreach($this->reports as $report){
      $html = $report['html'];
      $email = $this->debug ? 'sdf@marlboroughcollege.org' : $report['email'];
      $name = $report['hm'];
      $email = new \SMT\Tools\Watch\SundayReport($email, $name, $html);
    }
  }

  private function makeHouseReport($name)
  {
    $reportString = '';
    $rawName = str_replace('_', ' ', $name);
    $report['name'] = $rawName;
    $houseInfo = $this->ada->select('sch_houses', 'HM, email', 'name=?', array($rawName));

    if(!isset($houseInfo[0])) return null;

    $report['email'] = $houseInfo[0]['email'];
    $report['hm'] = $houseInfo[0]['HM'];

    //missing
    $house = $this->allHouses[$name];

    //Summary
    $reportString .= "<tr>
          <td align='left'>
            <h2 class='title'>Summary</h2>
          </td>
        </tr>";

    $missingCount = count($house['missed']);
    $reportString .= "<tr>
          <td align='left' class='name'>
            Missing: $missingCount
          </td>
        </tr>
        ";

    $privCount = count($house['priv']);
    $reportString .= "<tr>
          <td align='left' class='name'>
            Privs: $privCount
          </td>
        </tr>
        ";

    $count = count($house['sessions']['early']);
    $reportString .= "<tr>
          <td align='left' class='name'>
            Early Service: $count
          </td>
        </tr>
        ";

    $count = count($house['sessions']['late']);
    $reportString .= "<tr>
          <td align='left' class='name'>
            Main Service: $count
          </td>
        </tr>
        ";

    $count = count($house['sessions']['talk']);
    $reportString .= "<tr>
          <td align='left' class='name'>
            Evening Talk: $count
          </td>
        </tr>
        ";

    //Missing
    $reportString .= "<tr>
          <td align='left'>
            <h2 class='title'>Did Not Attend</h2>
          </td>
        </tr>";

    foreach($house['missed'] as $missing){
      $name = $missing['name'];
      $reportString .= "<tr>
            <td align='left' class='name'>
              $name
            </td>
          </tr>
          ";
    }
    $report['html'] = $reportString;
    return $report;
  }

  public function reportAllByDate($unixDate)
  {
    $this->byDate($unixDate, true);
    $this->dateString = date("l, jS F Y", $unixDate);
    $this->reports = array();

    foreach($this->allHouses as $key=> $value){
      $report = $this->makeHouseReport($key);
      if($report) $this->reports[] = $report;
    }
    //sort missed by lastname
    usort($this->reports, function($a, $b) {
      return $a['name'] <=> $b['name'];
    });
    return $this;
  }

  public function byDate($unixDate, $keepKeys = false)
  {
    $this->pin = 0;
    $this->card = 0;
    $this->error = 0;

    $this->events = $this->exgarde->getAreaByDate($unixDate, self::EXGARDE_AREA, true);
    $this->events = array_merge($this->events, $this->exgarde->getAreaByDate($unixDate, self::MEM_AREA1, true));
    $this->events = array_merge($this->events, $this->exgarde->getAreaByDate($unixDate, self::MEM_AREA2, true));

    $studentByKey = array();
    //put into key array for removing later
    foreach($this->events as $event){
      if(isset($event['ada_id'])) $studentByKey['s_' . $event['ada_id']] = $event;
    }

    unset($student);
    $idsForRemoval = array();

    foreach($studentByKey as &$student){
      if($student['style']=='PIN') ++$this->pin;
      if($student['style']=='Card') ++$this->card;
      if($student['style']=='error') ++$this->error;
      if(!isset($student['boarding'])) $student['boarding'] = 'err';
      if($student['style']=='error' || $student['boarding']=='Staff') $idsForRemoval[]= $student['ada_id'];

      $hour = date('G', $student['entry_unix']);

      switch(TRUE){
        case ($hour<10) : $student['session'] = 'early'; break;
        case ($hour<16) : $student['session'] = 'late'; break;
        default : $student['session'] = 'talk'; break;
      }
    }

    $this->idsForRemoval = $idsForRemoval;

    //unset the entries with an error
    foreach($idsForRemoval as $id){  unset($studentByKey['s_'.$id]);}

    $this->students = array_values($studentByKey);

    //get priv Students. Chapel is on Sunday and Privs are logged for the saturday so go back one cal_days_in_month
    $yesterday = $unixDate - 24*60*60;
    $this->c_Privs->byDate($yesterday);

    $this->sortIntoCategories();
    $this->sortIntoBoarding();

    ksort($this->allHouses);
    if (!$keepKeys) $this->allHouses = array_values($this->allHouses);

    return $this;
  }

  private function sortIntoCategories()
  {
    $this->identified = array();
    $this->unidentified = array();

    $this->sessions = array(  'early' =>  array(),
                              'talk'  =>  array(),
                              'late'  =>  array());

    foreach($this->students as $student){
      if(isset($student['ada_id'])){
        $id = $student['ada_id'];
        $this->identified['s_'.$id] = $student;
        $session = strtolower($student['session']);

        if(!isset($this->sessions[$session])) $this->sessions[$session] = array();

        $this->sessions[$session][] = $student;
      }else{
        $id = $student['ID_3'];
        $this->unidentified['s_'.$id] = $student;
      }
    }
  }

  private function sortIntoBoarding()
  {
    //first set up each hosue array and populate the missed list with everyone in the house
    $this->allHouses = [];

    $houses = new \Entities\Houses\All($this->ada);

    $d = $houses->list;

		foreach($d as $bhouse){
      $house = [
        'name' => $bhouse->name,
        'attended'  =>  [],
        'missed'    =>  [],
        'priv'      =>  [],
        'sessions'   => [ 'early' =>  [],
                          'late'  =>  [],
                          'talk'  =>  []
                        ]
      ];

      //the choir
      foreach($bhouse->studentIds as $student){
        //don't add them if in choir
        // $choirData = $this->ada->select('u_details', 'choir', 'userid=?', array($tag['user_id']));
        // if(isset($choirData[0])){
        //   if($choirData[0]['choir']==0){
        //     //fill up the missed with everyone and then simply remove as their records appear
            $house['missed']['s_'.$student] = $student;
        //   }
        // }
      }

      //add to master array
			$this->allHouses[$bhouse->noSpaceName]  = $house;
		}

    //move through each sucessfully identified student and add / remove as appropriate
    foreach($this->identified as $student){
      $bhouseName = str_replace(" ", '_', $student['boarding']);
      $house = &$this->allHouses[$bhouseName];
      $house['attended'][] = $student;
      $session = strtolower($student['session']);
      $house['sessions'][$session][] = $student;

      //remove from missed list
      $id = $student['ada_id'];
      unset($house['missed']['s_'.$id]);
    }

     //privs
    foreach($this->c_Privs->students as $student){
      $bhouseName = $student->boardingHouseSafe;
      $house = &$this->allHouses[$bhouseName];
      $house['priv'][] = $student;

      //remove from missed list
      $id = $student->id;
      unset($house['missed']['s_'.$id]);
    }

    //fill in names of missed allStudentIDs
    foreach($this->allHouses as &$house){
      // if(!isset($house['missed'])) $house['missed'] = array();
      foreach($house['missed'] as $id){
        // $d=$this->ada->select('u_details', '*firstname, *lastname', 'userid=?', array($id));
        $student = new \Entities\People\Student($this->ada, $id);
        $name = $student->displayName;
        $lastname = $student->lastName;

        $house['missed']['s_'.$id] = array( 'ada_id' => $id,
                                            'name' =>  $name,
                                            'lastname' => $lastname);
      }

      //sort missed by lastname
      usort($house['missed'], function($a, $b) {
        return $a['lastname'] <=> $b['lastname'];
      });

      //sort sessions by lastname
      foreach($house['sessions'] as &$session){
        //sort missed by lastname
        usort($session, function($a, $b) {
        return $a['lastName'] <=> $b['lastName'];
        });
      }
    }


    if(count($this->unidentified)>0){
      //sort unidentified
      usort($this->unidentified, function($a, $b) {
        if(!isset($a['lastName']) || !isset($b['lastName'])) return false;
        return $a['lastName'] <=> $b['lastName'];
      });
    }
  }

  public function reportSummaryByDate($unixDate)
  {
    $this->byDate($unixDate, true);
    $this->dateString = date("l, jS F Y", $unixDate);

    $report = array();
    $report['dateString'] = $this->dateString;

    $reportString = '';

    $reportString .= "<tr>
          <td align='left'>
            <h2 class='title'>Overview</h2>
          </td>
        </tr>";

    $count1 = count($this->sessions['early']);
    $reportString .= "<tr>
            <td align='left' class='name'>
              Early Service: $count1
            </td>
          </tr>
          ";

    $count2 = count($this->sessions['late']);
    $reportString .= "<tr>
            <td align='left' class='name'>
              Late Service: $count2
            </td>
          </tr>
          ";

    $count3 = count($this->sessions['talk']);
    $reportString .= "<tr>
            <td align='left' class='name'>
              Talk: $count3
            </td>
          </tr>
          ";

    $count4 = $count1 + $count2 + $count3;
    $reportString .= "<tr>
            <td align='left' class='name'>
              <span style='font-weight:bold'>Total:</span> $count4
            </td>
          </tr>
          ";

    //blank row
    $reportString .= "<tr>
            <td align='left' class='name'><p>

            </p>
            </td>
          </tr>
          ";
    $privs = count($this->c_Privs->students);
    $reportString .= "<tr>
            <td align='left' class='name'>
              Privs: $privs
            </td>
          </tr>
          ";

    $missed = 0;

    foreach($this->allHouses as $house){
      $missed += count($house['missed']);
    }

    $reportString .= "<tr>
            <td align='left' class='name'>
              Missed: $missed
            </td>
          </tr>
          ";

    $count = count($this->unidentified);
    $reportString .= "<tr>
            <td align='left' class='name'>
              Unidentified: $count
            </td>
          </tr>
          ";

    //blank row
    $reportString .= "<tr>
            <td align='left' class='name'><p>

            </p>
            </td>
          </tr>
          ";

    $count = $this->card;
    $reportString .= "<tr>
            <td align='left' class='name'>
              Card: $count
            </td>
          </tr>
          ";

    $count = $this->pin;
    $reportString .= "<tr>
            <td align='left' class='name'>
              PIN: $count
            </td>
          </tr>
          ";


    $count = $this->error;
    $reportString .= "<tr>
            <td align='left' class='name'>
              Errors: $count
            </td>
          </tr>
          ";

    //blank row
    $reportString .= "<tr>
            <td align='left' class='name'><p>

            </p>
            </td>
          </tr>
          ";

    //HOUSE Summary
    $reportString .= "<tr>
          <td align='left'>
            <h2 class='title'>House Summary</h2>
          </td>
        </tr>";

    foreach($this->allHouses as $house){
      $name = $house->nameSafe;
      $early = count($house['sessions']['early']);
      $late = count($house['sessions']['late']);
      $talk = count($house['sessions']['talk']);
      $total = count($house['attended']);
      $privs = count($house['priv']);
      $missed = count($house['missed']);

      $reportString .= "<tr>
              <td align='left' class='name'>
                <span style='font-weight:bold'>$name</span>:
              </td>
              <td align='left' class='name'>
                Early ($early)
              </td>
              <td align='left' class='name'>
                Late ($late)
              </td>
              <td align='left' class='name'>
                Talk ($talk)
              </td>
              <td align='left' class='name'>
                Priv ($privs)
              </td>
              <td align='left' class='name'>
                Total ($total)
              </td>
              <td align='left' class='name' style='width:auto'>
                Missed ($missed)
              </td>
            </tr>
            ";

    }

    //blank row
    $reportString .= "<tr>
            <td align='left' class='name'><p>

            </p>
            </td>
          </tr>
          ";

    //Unidentified Summary

    $reportString .= "<tr>
          <td align='left'>
            <h2 class='title'>Unidentified</h2>
          </td>
        </tr>";

    foreach($this->unidentified as $student){

      $name = $student['name'];
      $reportString .= "<tr>
              <td align='left' class='name'>
                $name
              </td>
            </tr>
            ";
    }

    $report['html'] = $reportString;

    return $report;

  }

  function reportSummaryByDateEmail($unixDate=null){
    if($unixDate == null) $unixDate = time();
    $report = $this->reportSummaryByDate($unixDate);
    // $email = new \SundaySummary('wdln@marlboroughcollege.org', 'Bill', $report['html']);
    $email = new \SundaySummary('sdf@marlboroughcollege.org', 'Bill', $report['html']);
  }

}
 ?>
