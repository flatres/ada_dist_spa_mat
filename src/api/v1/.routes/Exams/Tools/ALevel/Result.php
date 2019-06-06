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

    public function __construct(array $result)
    {
        $this->id = $result['id'];
        $this->subjectCode = $result['subjectCode'];
        $this->moduleCode = $result['txtModuleCode'];
        $this->grade = $result['grade'];
        $this->NCYear = $result['NCYear'];

        $points = 0;
        $pass = 1;
        $fail = 0;
        switch(strtoupper($this->grade))
        {
          case 'A*' : $points = 8; $pass = 1; $fail = 0; break;
          case 'A'  : $points = 7; $pass = 1; $fail = 0; break;
          case 'B'  : $points = 6; $pass = 1; $fail = 0; break;
          case 'C'  : $points = 5; $pass = 1; $fail = 0; break;
          case 'D'  : $points = 4; $pass = 0; $fail = 1; break;
          case 'E'  : $points = 3; $pass = 0; $fail = 1; break;
          case 'F'  : $points = 2; $pass = 0; $fail = 1; break;
          case 'G'  : $points = 1; $pass = 0; $fail = 1; break;
          case 'U'  : $points = 0; $pass = 0; $fail = 1; break;
        }
        $this->points = $points;
        $this->passes = $pass;
        $this->fails = $fail;
    }

}
