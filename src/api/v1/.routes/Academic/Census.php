<?php

/**
 * Description

 * Usage:

 */
namespace Academic;

class Census
{
    protected $container;
    private $console;

    public function __construct(\Slim\Container $container)
    {
       $this->ada = $container->ada;
       $this->adaModules = $container->adaModules;
       $this->isams = $container->isams;
       $this->mcCustom= $container->mcCustom;
       ini_set('max_execution_time', 240);

    }

// ROUTE -----------------------------------------------------------------------------
    public function censusGet($request, $response, $args)
    {
      $auth = $request->getAttribute('auth');
      $this->console = new \Sockets\Console($auth);

      $year = date("Y") - 1;
      $date = "31 Aug $year";
      $subjects = [
        'GCE_M' => [
            'id'   => 'GCE_Male',
            'a_13' => 0,
            'a_14' => 0,
            'a_15' => 0,
            'a_16' => 0,
            'a_17' => 0,
            'a_18' => 0,
          ],
        'GCE_F' => [
            'id'   => 'GCE_Female',
            'a_13' => 0,
            'a_14' => 0,
            'a_15' => 0,
            'a_16' => 0,
            'a_17' => 0,
            'a_18' => 0,
          ],
        'GCSE_M' => [
            'id'   => 'GCSE_Male',
            'a_13' => 0,
            'a_14' => 0,
            'a_15' => 0,
            'a_16' => 0,
            'a_17' => 0,
            'a_18' => 0,
          ],
        'GCSE_F' => [
            'id'   => 'GCSE_Female',
            'a_13' => 0,
            'a_14' => 0,
            'a_15' => 0,
            'a_16' => 0,
            'a_17' => 0,
            'a_18' => 0,
          ]
      ];

      $this->console->publish("Greetings.");
      $this->console->publish("Fetching Birthdays..");

      $ageData = $this->isams->query(
        "SELECT txtSchoolID, txtSurname + ', ' + txtForename as name, txtGender, intNCYear, convert(varchar, txtDOB, 106) as dob, Convert(integer,((DateDiff(day,txtDOB,?))/365.25)) as age, txtType
         FROM TblPupilManagementPupils
         WHERE intSystemStatus=1
         AND intSystemStatus=1
         ORDER BY txtdob ASC",
         [$date]
      );

      $ages = [];
      $count = 0;
      $total = count($ageData);

      foreach ($ageData as $d) {
        if ((int)$d['age'] > 19) $d['age'] = 19; // top category is 19 and over
        $key = 'a_' . $d['age'];
        if (!isset($ages[$key])) {
          $ages[$key] = [
            'age' => $d['age'],
            'Day pupil' => [
              'M' => 0,
              'F' => 0
            ],
            'Boarder' => [
              'M' => 0,
              'F' => 0
            ]
          ];
        }
        //increment the counters
        $ages[$key][$d['txtType']][$d['txtGender']]++;

        $level = $this->getHighestLevel($d['txtSchoolID']);
        $levelKey = $level . '_' . $d['txtGender'];
        $subjects[$levelKey][$key]++;

        $text = $count . "/" . $total . " - " . $d['name'];
        $this->console->publish($text);
        $count++;

      }

      $ageCensus = [];
      $boardersM = 0;
      $boardersF = 0;
      $dayM = 0;
      $dayF = 0;
      $id = 1;

      foreach($ages as $a){
        $ageCensus[] = [
          'id'  => $id,
          'age' => $a['age'],
          'boarderM' => $a['Boarder']['M'],
          'boarderF' => $a['Boarder']['F'],
          'dayM' => $a['Day pupil']['M'],
          'dayF' => $a['Day pupil']['F'],
        ];
        $boardersM += $a['Boarder']['M'];
        $boardersF += $a['Boarder']['F'];
        $dayM += $a['Day pupil']['M'];
        $dayF += $a['Day pupil']['F'];
        $id++;
      }
      $ageCensus[] = [
        'id'        => $id,
        'age'       => 'Totals',
        'boarderM'  => $boardersM,
        'boarderF'  => $boardersF,
        'dayM'      => $dayM,
        'dayF'      => $dayF
      ];


      $data = [
        'date' => $date,
        'ageRaw' => $ageData,
        'ageCensus' => $ageCensus,
        'subjects'  => array_values($subjects)
      ];

      return emit($response, $data);
      // return emit($response, $this->adaModules->select('TABLE', '*'));
    }

    //A level and PreU are GCE. If none of these must be GCSE
    private function getHighestLevel($misId) {
      // return "GCSE";
      $student = new \Entities\People\iSamsStudent($this->isams, $misId);
      $student->getSets();
      // foreach($student->sets as $set) {
      //   if ($set->academicLevel === 'A' || $set->academicLevel === 'PreU') return "GCSE";
      // }
      return "GCSE";
    }


}
