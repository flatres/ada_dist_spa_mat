<?php

/**
 * Description

 * Usage:

 */
namespace Academic;

class Alis
{
    protected $container;
    private $console;

    public function __construct(\Slim\Container $container)
    {
       $this->ada = $container->ada;
       $this->adaModules = $container->adaModules;
       $this->isams = $container->isams;
       $this->mcCustom= $container->mcCustom;
       
    }

// ROUTE -----------------------------------------------------------------------------
    public function alisRegistrationGet($request, $response, $args)
    {
      $auth = $request->getAttribute('auth');
      $this->console = new \Sockets\Console($auth);
      $sets = [];
      
      $this->console->publish("Greetings.");
      $this->console->publish("Fetching L6 set lists");
      
      //find sets and look up their subject name, making corrections on the way
      $s = $this->isams->select('TblTeachingManagerSets', 'TblTeachingManagerSetsID as id, intSubject, txtSetCode', 'intYear=?', [12]);
      $count = count($s);
      
      $this->console->publish("$count found");
      
      if($count === 0) return emit($response, []);
      
      $this->console->publish("Matching Sets to Subjects");
      
      foreach ($s as $set) {
        $subject = $this->isams->select('TblTeachingManagerSubjects', 'TblTeachingManagerSubjectsID as id, txtSubjectName, txtSubjectCode', 'TblTeachingManagerSubjectsID = ?', [$set['intSubject']]);
        if (!isset($subject[0])) continue;
        $subject = $subject[0];
        
        if (!$this->isAcademicSubject($subject['txtSubjectName'], $set['txtSetCode'])) continue;
        
        $isAlevel = $this->setSubjectToAda($subject);
        
        if (strpos($set['txtSetCode'], 'Ma/x') !== false || strpos($set['txtSetCode'], 'Ma/y') !== false) {
          $subject['txtSubjectName'] = 'Further Mathemetics';
        }
        
        if ($subject['txtSubjectCode'] == 'EN') $subject['txtSubjectName'] = 'Literature in English';
        $prefix = $isAlevel ? 'A2;' : 'PREUFC;';
        $subject['txtSubjectName'] = $prefix . $subject['txtSubjectName'];
        $sets['id_' . $set['id']] = array_merge($subject, $set);
        $this->console->publish("Set {$set['txtSetCode']} matched with {$subject['txtSubjectName']}");
        
      }
      $this->console->publish("Getting pupil sets and matching subjects");
      
      //get all year 12 pupils and look them up in set lists. If a new subject, add to their list of subjects
      $students = $this->isams->select(  'TblPupilManagementPupils',
                                            'txtSchoolID as id, txtForename, intFamily, intNCYear, txtSurname, txtGender, txtDOB',
                                            'intNCYear = 12 AND intSystemStatus = 1 ORDER BY txtSurname ASC', []);
      $count = count($students);
      $this->console->publish("$count found");
      $this->console->publish("Finding pupil subjects");
      
      foreach ($students as &$student) {
        $student['subjects'] = [];
        $studentSets = $this->isams->select( 'TblTeachingManagerSetLists', 'intSetID', 'txtSchoolID=?', [$student['id']]);
      
        $this->console->publish($student['txtSurname']);
        foreach ($studentSets as $set) {
          if (isset($sets['id_' . $set['intSetID']])) {
            $foundSet = $sets['id_' . $set['intSetID']];
            if (!isset($student['subjects'][$foundSet['txtSubjectCode']])) {
              $student['subjects'][$foundSet['txtSubjectCode']] = $foundSet['txtSubjectName'];
              //maths and FM under same set code so if in a FM set, also add maths
              if ($foundSet['txtSubjectName'] == 'Further Mathemetics') {
                $student['subjects']['MA2'] = 'Mathematics';
                $this->console->publish('   -- ' . 'Mathematics');
              }
              $this->console->publish('   -- ' . $foundSet['txtSubjectName']);
            }
          }
        }
        
        $adaStudent = new \Entities\People\Student();
        $adaStudent->byMISId($student['id']);
        $tag = new \Entities\Tags\Tag();
        $student['avgGcse'] = $tag->value('Metrics', 'GCSE Avg.', $adaStudent->id);
        
        // add white space to stop excel displaying it in scientific notation
        $student['id'] = '' . strval($student['id']) . ' ';
        $dob = strtotime($student['txtDOB']);
        $student['txtDOB'] = date('d/m/Y',$dob);
        
        $student['s1'] = '';
        $student['s2'] = '';
        $student['s3'] = '';
        $student['s4'] = '';
        $i = 1;
        foreach ($student['subjects'] as $sub) {
          $student["s$i"] = $sub;
          $i++;
        }
      }
      
      $columns = [
        [
          'field' => 'txtSurname',
          'label' => 'Surname'
        ],
        [
          'field' => 'txtForename',
          'label' => 'Forename'
        ],
        [
          'field' => 'txtDOB',
          'label' => 'DOB'
        ],
        [
          'field' => 'id',
          'label' => 'UPN'
        ],
        [
          'field' => 'txtGender',
          'label' => 'Sex'
        ],
        [
          'field' => 'avgGcse',
          'label' => 'Avg (I)GCSE'
        ],
        [
          'field' => 's1',
          'label' => 'Subject1'
        ],
        [
          'field' => 's2',
          'label' => 'Subject2'
        ],
        [
          'field' => 's3',
          'label' => 'Subject3'
        ],
        [
          'field' => 's4',
          'label' => 'Subject4'
        ]
      ];
      
      $settings = [
        'forceText' => true
      ];
      $this->console->publish("Generating Spreadsheet");
      $sheet = new \Utilities\Spreadsheet\SingleSheet($columns, $students, $settings);
      
      return emit($response, $sheet->package);
      // return emit($response, $this->adaModules->select('TABLE', '*'));
    }
    
    private function isAcademicSubject($name, $code){
      
      switch ($name) {
        case 'EPQ' :
        case 'Learning Support' :
          return false;
      }
      
      if (strpos($code, '/G') !== false) return false; //GCSE Language
      if (strpos($code, '/DE') !== false) return false; //DELE
      if (strpos($code, '/DF') !== false) return false; //DELF
      if (strpos($code, 'Ma/mc') !== false) return false; //Maths in Contect
      return true;
      
    }
    
    private function setSubjectToAda($subject) {
      $s = $this->adaModules->select('academic_subjects', 'id, isAlevel', 'subjectCode=?', [$subject['txtSubjectCode']]);
      if (!isset($s[0])){
        $this->adaModules->insert('academic_subjects', 'id, subjectCode, subjectName', [
          $subject['id'],
          $subject['txtSubjectCode'],
          $subject['txtSubjectName']
        ]);
        return true;
      } else {
        return $s[0]['isAlevel'] == 1 ? true : false;
      }
    }

}
