<?php

/**
 * Description

 * Usage:

 */
namespace Entities\Exams;

class Grade
{
    public $points, $ucasPoints, $pass, $fail;

    public function __construct($grade)
    {
       $this->sql= $ada ?? new \Dependency\Databases\Ada();
       $points = 0;
       $ucasPoints = 0;
       $pass = 0;
       $fail = 0;

       switch(strtoupper($grade))
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
         case '9' : $points = (int)$grade; $pass = 1; $fail = 0; break;
         case '8'  : $points = (int)$grade; $pass = 1; $fail = 0; break;
         case '7'  : $points = (int)$grade; $pass = 1; $fail = 0; break;
         case '6'  : $points = (int)$grade; $pass = 1; $fail = 0; break;
         case '5'  : $points = (int)$grade; $pass = 1; $fail = 0; break;
         case '4'  : $points = (int)$grade; $pass = 1; $fail = 0; break;
         case '3'  : $points = (int)$grade; $pass = 1; $fail = 0; break;
         case '2'  : $points = (int)$grade; $pass = 1; $fail = 0; break;
         case '1'  : $points = (int)$grade; $pass = 1; $fail = 0; break;
       }

       $this->points = $points;
       $this->ucasPoints = $points;
       $this->pass = $pass === 1 ? true : false;
       $this->fail = $fail === 1 ? true : false;

       return $this;
    }

    public function points() {

    }


}
