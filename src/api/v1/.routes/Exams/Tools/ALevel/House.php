<?php

/**
 * Description

 * Usage:

 */
// A Result Object
// grade:"A"
// id:"39550"
// intEnrolmentNCYear:"12"
// subjectCode:"GG"
// txtBoardingHouse:"Littlefield"
// txtForename:"Lucy"
// txtFullName:"Lucy Goodman"
// txtGender:"F"
// txtHouseCode:"LI"
// txtInitialedName:"Goodman, L C"
// txtInitials:"L C"
// txtLeavingBoardingHouse:"Littlefield"
// txtModuleCode:"2031"
// txtOptionTitle:"GCE GEOGRAPHY ADV"
// txtQualification:"GCE"
// txtSchoolID:"111234705547"
// txtSurname:"Goodman"
// ['isNewSixthForm']

namespace Exams\Tools\ALevel;

class House
{

    public $txtHouseCode;
    public $boysCount = 0;
    public $girlsCount = 0;
    public $genderType;
    public $typeKey;
    public $joinKey;
    public $genderKey;
    public $students = array(); // key s_{txtSchoolID}
    public $results = array();
    public $gradeCounts = [ 'A*'  => 0,
                            'A'   => 0,
                            'B'   => 0,
                            'C'   => 0,
                            'D'   => 0,
                            'E'   => 0,
                            'U'   => 0,
                            'D1'  => 0,
                            'D2'  => 0,
                            'D3'  => 0,
                            'M1'  => 0,
                            'M2'  => 0,
                            'M3'  => 0,
                            'P1'  => 0,
                            'P2'  => 0,
                            'P3'  => 0,
                            'PU'  => 0,
                            'Q'   => 0
                          ];
    public $data = [
        'all'=> [
          'all' => [
            'all' => [],
            'boys' => [],
            'girls' => []
          ],
          'NL6' => [
            'all' => [],
            'boys' => [],
            'girls' => []
          ],
          'LS'  => [
            'all' => [],
            'boys' => [],
            'girls' => []
          ]
        ],
        'A'  => [
          'all' => [
            'all' => [],
            'boys' => [],
            'girls' => []
          ],
          'NL6' => [
            'all' => [],
            'boys' => [],
            'girls' => []
          ],
          'LS'  => [
            'all' => [],
            'boys' => [],
            'girls' => []
          ]
        ],
        'AS'  => [
          'all' => [
            'all' => [],
            'boys' => [],
            'girls' => []
          ],
          'NL6' => [
            'all' => [],
            'boys' => [],
            'girls' => []
          ],
          'LS'  => [
            'all' => [],
            'boys' => [],
            'girls' => []
          ]
        ],
        'PreU'  => [
          'all' => [
            'all' => [],
            'boys' => [],
            'girls' => []
          ],
          'NL6' => [
            'all' => [],
            'boys' => [],
            'girls' => []
          ],
          'LS'  => [
            'all' => [],
            'boys' => [],
            'girls' => []
          ]
        ],
        'EPQ'  => [
          'all' => [
            'all' => [],
            'boys' => [],
            'girls' => []
          ],
          'NL6' => [
            'all' => [],
            'boys' => [],
            'girls' => []
          ],
          'LS'  => [
            'all' => [],
            'boys' => [],
            'girls' => []
          ]
        ],
        'unknown'  => [
          'all' => [
            'all' => [],
            'boys' => [],
            'girls' => []
          ],
          'NL6' => [
            'all' => [],
            'boys' => [],
            'girls' => []
          ],
          'LS'  => [
            'all' => [],
            'boys' => [],
            'girls' => []
          ]
        ],
        'U6'  => [
          'all' => [
            'all' => [],
            'boys' => [],
            'girls' => []
          ],
          'NL6' => [
            'all' => [],
            'boys' => [],
            'girls' => []
          ],
          'LS'  => [
            'all' => [],
            'boys' => [],
            'girls' => []
          ]
        ]
      ];

    public $summaryData = array();

    public function __construct(array $result)
    {
      $this->txtHouseCode = $result['txtHouseCode'];

      foreach($this->data as &$type){
        foreach($type as &$joins){
          foreach($joins as &$gender){
            $gender = [
              'results' => 0,
              'points' => 0,
              'pointsAvg' => 0,
              'ucasPoints' => 0,
              'ucasAvg' => 0,
              'passes' => 0,
              'fails' => 0,
              'gradeCounts' => $this->gradeCounts,
              'position' => 0
            ];
          }
        }
      }
    }

    public function setResult(\Exams\Tools\ALevel\Result &$result)
    {
      $this->results['r_' . $result->id] = $result;

      if ($result->txtGender === 'M') $this->boysCount++;
      if ($result->txtGender === 'F') $this->girlsCount++;

      $gender = $result->gender === 'M' ? 'boys' : 'girls';
      $joined = $result->isNewSixthForm ? 'NL6' : 'LS';
      $level = $result->level;

      $item = &$this->data['all']['all']['all'];
      $this->increment($item, $result);

      $item = &$this->data['all']['all'][$gender];
      $this->increment($item, $result);

      $item = &$this->data['all'][$joined][$gender];
      $this->increment($item, $result);

      $item = &$this->data['all'][$joined]['all'];
      $this->increment($item, $result);

      $item = &$this->data[$level]['all']['all'];
      $this->increment($item, $result);

      $item = &$this->data[$level]['all'][$gender];
      $this->increment($item, $result);

      $item = &$this->data[$level][$joined][$gender];
      $this->increment($item, $result);

      $item = &$this->data[$level][$joined]['all'];
      $this->increment($item, $result);

      if ($level === 'A' || $level === 'PreU') {
        $item = &$this->data['U6'][$joined]['all'];
        $this->increment($item, $result);
        $item = &$this->data['U6'][$joined][$gender];
        $this->increment($item, $result);
        $item = &$this->data['U6']['all'][$gender];
        $this->increment($item, $result);
        $item = &$this->data['U6']['all']['all'];
        $this->increment($item, $result);
      }


    }

    private function increment(&$item, $result)
    {
      $item['passes'] += $result->passes;
      $item['fails'] += $result->fails;
      $item['points'] += $result->points;
      $item['ucasPoints'] += $result->ucasPoints;
      $item['gradeCounts'][trim($result->grade)]++;
      $item['results']++;
    }

    public function setStudent(\Exams\Tools\ALevel\Student &$student)
    {
      $txtSchoolID = $student->txtSchoolID;
      $this->students['s_' . $txtSchoolID] = &$student;
    }

    public function makeSummaryData(int $year)
    {
      $sD = array();
      $sD['year'] = $year;

      if ($this->girlsCount > 0) $this->genderType = 'girls';
      if ($this->boysCount > 0) $this->genderType = 'boys';
      if ($this->girlsCount > 0 && $this->boysCount > 0 ) $this->genderType = 'mixed';

      foreach($this->data as &$type){
        foreach($type as &$joins){
          foreach($joins as &$gender){
            if ($gender['results'] > 0){
              $gender['pointsAvg'] = round($gender['points'] / $gender['results'], 2);
              $gender['ucasPointsAvg'] = round($gender['ucasPoints'] / $gender['results'], 2);
            }
          }
        }
      }
      $sD['data'] = $this->data;

      $sD['candidateCount'] = count($this->students);

      $sD['history'] = [$sD];
      $sD['historyKeys'] = ['y_' . $year => $sD];

      $this->summaryData = $sD;

    }
}
