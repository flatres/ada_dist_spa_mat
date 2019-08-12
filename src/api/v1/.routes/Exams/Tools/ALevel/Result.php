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

namespace Exams\Tools\ALevel;

class Result
{
    public $id, $subjectCode, $moduleCode, $grade;
    public $points = 0;
    public $passes = 1;
    public $fails = 0;
    public $level;
    public $NCYear;
    public $txtGender;
    public $txtForename;
    public $txtSurname;
    public $txtHouseCode;
    public $txtLevel;

    public function __construct(array $result)
    {
        $this->id = $result['id'];
        $this->subjectCode = $result['subjectCode'];
        $this->moduleCode = $result['txtModuleCode'];
        $this->gender = $result['txtGender'];
        $this->grade = $result['grade'];
        $this->isNewSixthForm = $result['isNewSixthForm'];
        $this->NCYear = $result['NCYear'] ?? 0;
        $this->txtGender = $result['txtGender'];
        $this->txtInitialedName = $result['txtInitialedName'];
        $this->txtForename = $result['txtForename'];
        $this->txtSurname = $result['txtSurname'];
        $this->txtHouseCode = $result['txtHouseCode'];
        $this->txtLevel = $result['txtLevel'];
        $this->txtSubjectName = $result['subjectName'];
        $this->level = $result['level'];

        $points = 0;
        $ucasPoints = 0;
        $pass = 1;
        $fail = 0;
        switch(strtoupper($this->grade))
        {
          case 'A*' : $points = 12; $ucasPoints = 56; $pass = 1; $fail = 0; break;
          case 'A'  : $points = 10; $ucasPoints = 48; $pass = 1; $fail = 0; break;
          case 'B'  : $points = 8; $ucasPoints = 40; $pass = 1; $fail = 0; break;
          case 'C'  : $points = 6; $ucasPoints = 32; $pass = 1; $fail = 0; break;
          case 'D'  : $points = 4; $ucasPoints = 24; $pass = 1; $fail = 0; break;
          case 'E'  : $points = 2; $ucasPoints = 16; $pass = 1; $fail = 0; break;
          case 'U'  : $points = 0; $ucasPoints = 0; $pass = 0; $fail = 1; break;
          case 'D1' : $points = 12; $ucasPoints = 56; $pass = 1; $fail = 0; break;
          case 'D2'  : $points = 12; $ucasPoints = 56; $pass = 1; $fail = 0; break;
          case 'D3'  : $points = 11; $ucasPoints = 52; $pass = 1; $fail = 0; break;
          case 'M1'  : $points = 9; $ucasPoints = 44; $pass = 1; $fail = 0; break;
          case 'M2'  : $points = 8; $ucasPoints = 40; $pass = 1; $fail = 0; break;
          case 'M3'  : $points = 7; $ucasPoints = 36; $pass = 1; $fail = 0; break;
          case 'P1'  : $points = 5; $ucasPoints = 28; $pass = 1; $fail = 0; break;
          case 'P2'  : $points = 4; $ucasPoints = 24; $pass = 1; $fail = 0; break;
          case 'P3'  : $points = 3; $ucasPoints = 20; $pass = 1; $fail = 0; break;

        }

        if ($this->level === 'EPQ') {
          $points = $points / 2;
          $ucasPoints = $ucasPoints / 2;
        }

        if ($this->level === 'AS') {
          $points = 0;
          $ucasPoints = 0;
          $pass = 0;
          $fail = 0;
        }

        $this->ucasPoints = $ucasPoints;
        $this->points = $points;
        $this->passes = $pass;
        $this->fails = $fail;
    }
}
