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
    public $level;
    public $isIGCSE = false;
    public $error = false;
    private $console;
    // public $boardName;
    // public $boardDesc;

    private static $boardInfo = [];

    public function __construct(string $moduleCode = null, string $subjectTitle = null, $sql = null, $level = null, $supressBoard = false, $console = false)
    {
       if (!$sql) return $this;
       $this->sql= $sql;
       $this->console = $console;
       $this->txtModuleCode = $moduleCode;
       $this->txtOptionTitle = $subjectTitle;

       switch($level) { //must be before codes
           case 'A'  : $level = 'A'; break;
           case 'ASB': $level = 'AS'; break;
           case 'FC': $level = 'PreU'; break;
           case 'B' : $level = 'EPQ'; break;
           case 'GCSE': $level = 'GCSE'; break;
           default: $level = '';
       }

       $this->level = $level;

       $codes = $this->getCodes();
       $this->subjectCode = $codes[0];
       $this->subjectName = $codes[1];
       if (!$supressBoard) {
         $this->getBoardInformation() ;
       } else {
         $this->intUABCode = 0;
         $this->boardDesc = '-';
         $this->boardName = '-';
       };
    }

    public function GCSEType(){
      $sql = new \Dependency\Databases\ISams();
      $ada = new \Dependency\Databases\AdaModules();
      $d = $ada->select('exams_modules', 'isIGCSE', 'moduleID=? AND title=?', [$this->txtModuleCode, $this->txtOptionTitle]);
      if(isset($d[0])) {
        $this->isIGCSE = $d[0]['isIGCSE'] == 1 ? true : false;
        return $this->isIGCSE;
      } else {
        $this->console->publish('Searching Google for ' . $this->boardName . '('.$this->txtOptionTitle .')');
        // $text = $this->txtModuleCode . ' ' . $this->boardName;
        // $text = 'is ' . $this->txtModuleCode . ' igcse';
        // $cleanQuery = urlencode($text);
        // $url = 'http://www.google.com/search?q='.$cleanQuery;
        // $scrape = file_get_contents($url);
        // $pos1 = stripos($scrape, 'international gcse');
        // $pos2 = stripos($scrape, 'igcse');
        // $pos3 = stripos($scrape, ' gcse');
        //
        // if ($pos1 < $pos3 || $pos2 < $pos3) {
        //     $isIGCSE = true;
        // } else {
        //     $isIGCSE = false;
        // }
        $d = $sql->select('TblExamManagerTeachingProgram', 'intProgram', 'txtModuleCode=?', [$this->txtModuleCode]);
        if (!isset($d[0])){
          $this->console->publish('ERROR!');
        } else {
          $intProgram = $d[0]['intProgram'];
          $e = $sql->select('TblExamManagerTeachingProgramHeaders', 'txtHeaderName', 'TblExamManagerTeachingProgramHeadersID=?',[$intProgram]);
          $string = $e[0]['txtHeaderName'] ?? '';
          $isIGCSE = stripos($string, 'igcse') !== false ? true : false;
          $ans = $isIGCSE ? 'YES' : 'NO';
          $this->console->publish('Is IGCSE = ' . $ans);
          $this->isIGCSE = $isIGCSE;
          $bit = $isIGCSE ? 1 : 0;
          $ada->insert('exams_modules', 'isIGCSE, moduleID, title, header', [$bit, $this->txtModuleCode, $this->txtOptionTitle, $string]);
        }

      }

    }

    private function getBoardInformation(){

      $moduleCode = $this->txtModuleCode;

      if (isset(self::$boardInfo[$moduleCode])){
        var_dump(self::$boardInfo[$moduleCode]);
        $board = self::$boardInfo['m_' . $moduleCode];
        $this->intUABCode = self::$boardInfo['m_' . $moduleCode]['intUABCode'];
        $this->boardDesc = self::$boardInfo['m_' . $moduleCode]['boardDesc'];
        $this->boardName = self::$boardInfo['m_' . $moduleCode]['boardName'];
        return;
      }
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

      $board = [
        'intUABCode' => $intUABCode,
        'boardDesc' => $boardDesc,
        'boardName' => $boardName
      ];

      self::$boardInfo['m_' . $moduleCode] = $board;
      $this->intUABCode = $intUABCode;
      $this->boardDesc = $boardDesc;
      $this->boardName = $boardName;

    }
    public function getCodes ()
    {
      $data = $this->getBasic();
      // if($this->level === 'ASB') {
      //   $data[1] = $data[1] . ' AS';
      // }
      return $data;
    }
    public function getBasic()
    {

      if($this->contains('chemistry') && $this->contains('dual')) return array('CHS2', 'Chemistry (Dual Award)');
      if($this->contains('physics') && $this->contains('dual')) return array('PHS2', 'Physics (Dual Award)');
      if($this->contains('biology') && $this->contains('dual')) return array('BIS2', 'Biology (Dual Award)');

      if($this->contains('history') && $this->contains('art')) return array('HX', 'Art History');
      if($this->contains('history')) return array("HI", 'History');

      if($this->contains('english') && $this->contains('language')) return array('EN', 'English Language');
      // if($this->contains('english') && $this->contains('literature')) return array('ELI', 'Literature in English');
      if($this->contains('english') && $this->contains('literature') && $this->level == 'GCSE') return array('ENLIT', 'Literature in English');
      if($this->contains('english')) return array('EN', 'English');

      if($this->contains('music') && $this->contains('technology')) return array("MT", "Music Technology");
      if($this->contains('music')) return array("MU", 'Music');

      // if($this->contains('math') && $this->contains('fur')) return 'MX';
      if($this->contains('decision mathematics')) return array('FM', 'Further Maths');
      if($this->contains('mechanics 1')) return array('MA', 'Mathematics');
      if($this->contains('mechanics')) return array('FM', 'Further Mathematics');
      if($this->contains('statistics 1')) return array('MA', 'Mathematics');
      if($this->contains('statistics')) return array('FM', 'Further Mathematics');
      if($this->contains('further pure')) return array('MA', 'Mathematics');

      if($this->contains('mathematics in context')) return array('MC', 'Maths in Context');
      if($this->contains('math') && $this->contains('add')) return array('ADD', 'Additional Maths');
      if($this->contains('math') && $this->contains('fur')) return array('FM', 'Further Maths');
      if($this->contains('math')) return array('MA', 'Mathematics');

      if($this->contains('latin')) return array("LA", "Latin");
      if($this->contains('greek')) return array("CG", "Greek");
      if($this->contains('classical civilisation')) return array("CL", "Classical Civilisation");

      if($this->contains('classic')) return array("CL", "Classics");
      if($this->contains('art') )return array('AR', 'Art');
      if($this->contains('design')) return array("DT", 'Design Technology');
      if($this->contains('D&T')) return array("DT", 'Design Technology');

      // if($this->contains('russian') && $this->contains('first')) return "RUI";
      if($this->contains('russian')) return array("RU", 'Russian');

      // if($this->contains('italian') && $this->contains('first')) return "INI";
      if($this->contains('italian')) return array("IN", 'Italian');
      if($this->contains('turkish')) return array("TK", 'Turkish');

      if($this->contains('chinese') || $this->contains('mandarin')) return array("CN", 'Chinese');
      if($this->contains('chinese')) return array("CN", 'Chinese');

      if($this->contains('greek') || $this->contains('classical')) return array("GC", 'Greek');

      if($this->contains('french')) return array("FR", "French");
      if($this->contains('japanese')) return array("JA", 'Japanese');
      if($this->contains('german')) return array('DE', "German");
      if($this->contains('spanish')) return array("SP", "Spanish");
      if($this->contains('arabic')) return array("AI", "Arabic");

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
      if($this->contains('computing')) return array("CS", 'Computer Science');
      if($this->contains('astronomy')) return array("AS", "Astronomy");

      if($this->contains('drama')) return array("DR", "Theatre Studies");
      if($this->contains('extended')) return array("EPQ", "Extended Project");
      if($this->contains('physical')) return array("PE", "Physical Education");
      if($this->contains('theatre')) return array("DR", "Theatre Studies");
      if($this->contains('re')) return array("RE", "Religious Studies");
      if($this->contains('sport')) return array("PE", "Sport Science");

      if($this->contains('science')) return array("SC", "Science");

      $this->error = true;
      return array("-", "-");
    }

    public function refresh($txtOptionTitle)
    {
      $this->txtOptionTitle = $txtOptionTitle;
      return $this->getCodes;
    }

    function contains($needle)
    {
        return stripos($this->txtOptionTitle, $needle) !== false;
    }
}
