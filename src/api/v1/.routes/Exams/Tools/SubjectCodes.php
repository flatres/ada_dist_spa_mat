<?php

/**
 * Description

 * Usage:

 */
//  Art
// Astronomy
// Biology
// Chemistry
// Chinese
// Computer Science
// Design
// Drama
// English Language
// English Literature
// French
// Geography
// German
// Greek
// History
// Italian
// Latin
// Mathematics
// Music
// Physical Education
// Physics
// Religious Studies
// Russian
// Science 1
// Science 2
// Spanish

namespace Exams\Tools;

class SubjectCodes
{
    public $subjectCode;

    public function __construct(string $moduleCode, string $subjectTitle, $sql)
    {
       $this->sql= $sql;
       $this->txtModuleCode = $moduleCode;
       $this->txtOptionTitle = $subjectTitle;
       $codes = $this->getCodes();
       $this->subjectCode = $codes[0];
       $this->subjectName = $codes[1];
       $this->getBoardInformation();
    }

    private function getBoardInformation(){

      $moduleCode = $this->txtModuleCode;
      //find the first matching resul in exammanagerentries and extract the intUAB (board) code
      $d = $this->sql->select('TblExamManagerEntries', 'intUABCode', 'txtModuleCode = ?', array($moduleCode));
      if(!isset($d[0])) return;

      $intUABCode = $d[0]['intUABCode'];
      //match up to board; Hard codes as can't find in isams anywhere
      switch($intUABCode){
        case '01': $boardDesc = "OCR"; $boardName = "OCR"; break;
        case '02': $boardDesc = "CIE"; $boardName = "CIE"; break;
        case '10': $boardDesc = "Edexcel (GCSE)"; $boardName = "Edexcel"; break;
        case 'Int': $boardDesc = "Int"; $boardName = "Int"; break;
        case '11': $boardDesc = "Edexcel (A Level)"; $boardName = "Edexcel"; break;
        case '14': $boardDesc = "Edexcel (Project)"; $boardName = "Edexcel"; break;
        case '40': $boardDesc = "WJEC (GCSE)"; $boardName = "WJEC"; break;
        case '41': $boardDesc = "WJEC (A Level)"; $boardName = "WJEC"; break;
        case '61': $boardDesc = "CEA"; $boardName = "CEA"; break;
        case '70': $boardDesc = "AQA"; $boardName = "AQA"; break;
        case '80': $boardDesc = "Durham"; $boardName = "DUR"; break;
        default: $boardDesc = "Unknown"; $boardName = "Unknown"; break;
      }

      $this->intUABCode = $intUABCode;
      $this->boardDesc = $boardDesc;
      $this->boardName = $boardName;

    }

    private function getCodes()
    {
      if($this->contains('history') && $this->contains('art')) return array('HX', 'History of Art');
      if($this->contains('history')) return array("HI", 'History');

      if($this->contains('english') && $this->contains('language')) return array('EN', 'English Language');
      if($this->contains('english') && $this->contains('literature')) return array('ELI', 'English Literature');

      if($this->contains('music') && $this->contains('technology')) return array("MT", "Music Technology");
      if($this->contains('music')) return array("MU", 'Music');

      // if($this->contains('math') && $this->contains('fur')) return 'MX';
      if($this->contains('math') && $this->contains('add')) return array('ADD', 'Additional Maths');
      if($this->contains('math')) return array('MA', 'Maths');
      if($this->contains('mechanics')) return array('MA', 'Maths');
      if($this->contains('statistics')) return array('MA', 'Maths');
      if($this->contains('further pure')) return array('MA', 'Maths');

      if($this->contains('classic')) return array("CL", "Classics");
      if($this->contains('art') )return array('AR', 'Art');
      if($this->contains('design')) return array("DT", 'Design Tech');
      if($this->contains('D&T')) return array("DT", 'Design Tech');

      // if($this->contains('russian') && $this->contains('first')) return "RUI";
      if($this->contains('russian')) return array("RU", 'Russian');

      // if($this->contains('italian') && $this->contains('first')) return "INI";
      if($this->contains('italian')) return array("IN", 'Italian');
      if($this->contains('turkish')) return array("TK", 'Turkish');

      if($this->contains('chinese') || $this->contains('mandarin')) return array("CN", 'Chinese');
      if($this->contains('chinese')) return array("CN", 'Chinese');

      if($this->contains('greek') || $this->contains('classical')) return array("GC", 'Greek');

      if($this->contains('french')) return array("FR", "French");
      if($this->contains('japanese')) return array("JA", 'Japenese');
      if($this->contains('german')) return array('DE', "German");
      if($this->contains('spanish')) return array("SP", "Spanish");
      if($this->contains('arabic')) return array("AI", "Arabic");
      if($this->contains('latin')) return array("LA", "Latin");
      if($this->contains('dutch')) return array("DU", "Dutch");

      if($this->contains('creative writing')) return array("CR", "Creative Writing");
      if($this->contains('geography')) return array("GG", "Geography");
      if($this->contains('economics')) return array("EC", "Economics");
      if($this->contains('politics')) return array("PO", "Politics");
      if($this->contains('religious')) return array("RE", "Religious Studies");
      if($this->contains('philosophy')) return array("PX", "Philosophy");
      if($this->contains('business')) return array("BE", "Business");
      if($this->contains('psyc')) return array("PY", "Psychology");

      if($this->contains('physics')) return array("PH", "Physics");
      if($this->contains('chemistry')) return array("CH", "Chemistry");
      if($this->contains('biology')) return array("BI", "Biology");

      if($this->contains('comp') && $this->contains('sci')) return array("CS", 'Computer Science');
      if($this->contains('science')) return array("SC", "Science");
      if($this->contains('astronomy')) return array("AS", "Astronomy");

      if($this->contains('drama')) return array("DR", "Drama");
      if($this->contains('extended')) return array("EPQ", "Extended Project");
      if($this->contains('physical')) return array("PE", "PE");

      return array("-", "-");
    }


    function contains($needle)
    {
        return stripos($this->txtOptionTitle, $needle) !== false;
    }
}
