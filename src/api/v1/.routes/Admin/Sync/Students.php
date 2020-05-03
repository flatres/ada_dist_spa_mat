<?php

/**
 * Description

 * Usage:

 */
namespace Admin\Sync;

class Students
{
    protected $container;
    private $disabledCount = 0;
    private $updatedCount = 0;
    private $newCount = 0;

    public function __construct(\Slim\Container $container)
    {
       $this->isams= $container->isams;
       $this->ada = $container->ada;
       $this->exgarde = $container->exgarde;
       ini_set('max_execution_time', 3600);

    }

    /**
     * Generate a password salt
     *
     * @param int $length
     *    The number of characters that the salt should be
     *
     * @return string
     *    Returns a salt that can be used to salt a password hash
     *
     * @access private
     */
    public function misStudents_GET($request, $response, $args)
    {
      $data = $this->getStudentComparison();
      return emit($response, $data);
    }

    //ensure that ADA students are up to date with iSams
    public function studentSync_POST($request, $response, $args)
    {
      $data = array('id'=>2);
      $auth = $request->getAttribute('auth');

      $console = new \Sockets\Console($auth);
      $this->progress = new \Sockets\Progress($auth, 'Admin/Sync/Students');

      $console->publish('Pulling iSAMS students...');
      $misStudents = $this->isams->select(  'TblPupilManagementPupils',
                                            'txtSchoolID as id, txtForename, intFamily, intNCYear, txtPreName, txtEmailAddress, txtSurname, txtFullName, txtInitials, txtGender, txtDOB, intEnrolmentNCYear, txtBoardingHouse, txtLeavingBoardingHouse, intEnrolmentSchoolYear',
                                            'intSystemStatus = 1', array());
      $console->publish('Got ' . count($misStudents), 1);

      // get ada students to get comparison for syncing
      $console->publish('Pulling ADA students');
      $adaStudents = $this->ada->select('stu_details', 'id, mis_id', 'usr_type=? AND disabled = 0 ORDER BY lastname ASC', array(1));
      $console->publish('Got ' . count($adaStudents), 1);


      $console->publish('Syncing...');
      $allStudents = array();
      foreach($adaStudents as $student)
      {
        $misId = $student['mis_id'];
        $adaId = $student['id'];
        $allStudents["s_$misId"] = array(
          'adaId'   => $adaId,
          'misId'   => $misId,
          'isNew'   => false,
          'disable' => true
        );
      }
      //students not appearing in the MIS will keep their disable flag (i.e not longer active)
      foreach($misStudents as $student)
      {
        $misId = $student['id'];
        if (isset($allStudents["s_$misId"])) //already exists, just needs updating
        {
          $allStudents["s_$misId"]['disable'] = false;
          $allStudents["s_$misId"]['misData'] = $student;
        } else {
          $allStudents["s_$misId"] = array(
            'adaId'   => null,
            'misId'   => $misId,
            'isNew'   => true,
            'disable' => false,
            'misData' => $student
          );
        }
      }
      $count = count($allStudents);
      $i = 1;
      //update
      foreach($allStudents as &$student){
        $student['error'] = $student['isNew'] == true ? $this->newStudent($student) : $this->updateStudent($student);
        $this->progress->publish($i/$count);
        $i++;
      }
      $data = array(
        'count'   =>$count,
        'new'     =>$this->newCount,
        'updated' => $this->updatedCount,
        'disabled'=> $this->disabledCount
      );

      $console->publish("Done - new($this->newCount) - updated($this->updatedCount) - disabled($this->disabledCount)");

      return emit($response, $data);
    }

    private function newStudent($student)
    {
      $d = $student['misData'];

      //boardingHouseId
      $bh = $this->ada->select('sch_houses', 'id', 'name=?', [$d['txtBoardingHouse']]);
      $bhId = $bh[0]['id'] ?? null;

      $id = $this->ada->insert(
        'stu_details',
        'lastname, firstname, prename, initials, boardingHouse, boardingHouseId, NCYear, email, mis_id, mis_family_id, gender, dob, enrolmentNCYear, EnrolmentSchoolYear',
        array(
          $d['txtSurname'],
          $d['txtForename'],
          $d['txtPreName'],
          $d['txtInitials'],
          $d['txtBoardingHouse'],
          $bhId,
          $d['intNCYear'],
          $d['txtEmailAddress'],
          $d['id'],
          $d['intFamily'],
          $d['txtGender'],
          $d['txtDOB'],
          $d['intEnrolmentNCYear'],
          $d['intEnrolmentSchoolYear']
        )
      );
      $tag = new \Entities\Tags\Tag($this->ada);
      $tag->create('House', $d['txtBoardingHouse'], ['studentId' => $id]);

      //try to match to exgarde database
      $studentObj = new \Entities\People\Student($this->ada, $id);
      $this->exgarde->match($studentObj);

      $this->newCount++;
    }

    private function updateStudent($student)
    {
      $id = $student['adaId'];
      if ($student['disable'] == true)
      {

        $this->ada->update('stu_details', 'disabled=1', 'id=?', array($id));
        $this->exgarde->unmatch($id);
        $this->disabledCount++;
      } else {
        $d = $student['misData'];
        // echo $d['id'] . $d['txtSurname'] . PHP_EOL;
        //boardingHouseId
        $bh = $this->ada->select('sch_houses', 'id', 'name=?', [$d['txtBoardingHouse']]);
        $bhId = $bh[0]['id'] ?? null;

        $this->ada->update(
          'stu_details',
          'lastname=?, firstname=?, prename=?, initials=?, boardingHouse=?, boardingHouseId=?, NCYear = ?, email=?, mis_id=?, mis_family_id=?, gender=?, dob=?, enrolmentNCYear=?, enrolmentSchoolYear=?, disabled=?',
          'id=?',
          array(
            $d['txtSurname'],
            $d['txtForename'],
            $d['txtPreName'],
            $d['txtInitials'],
            $d['txtBoardingHouse'],
            $bhId,
            $d['intNCYear'],
            $d['txtEmailAddress'],
            $d['id'],
            $d['intFamily'],
            $d['txtGender'],
            $d['txtDOB'],
            $d['intEnrolmentNCYear'],
            $d['intEnrolmentSchoolYear'],
            0,
            $student['adaId']
          )
        );
        $tag = new \Entities\Tags\Tag($this->ada);
        $tag->create('House', $d['txtBoardingHouse'],  ['studentId' => $id]);
        //try to match to exgarde database
        $studentObj = new \Entities\People\Student($this->ada, $id);
        $this->exgarde->match($studentObj);

        $this->updatedCount++;
      }
    }

    private function getStudentComparison()
    {
      $data = array();
      $misStudents = $this->isams->select(  'TblPupilManagementPupils',
                                          'txtSchoolID as id, txtForename, txtPreName, txtEmailAddress, txtSurname, txtFullName, txtInitials, txtGender, txtDOB, intEnrolmentNCYear, txtBoardingHouse, txtLeavingBoardingHouse, intEnrolmentSchoolYear',
                                          'intSystemStatus = 1', array());

      //get ada students to get comparison for syncing
      $adaStudents = $this->ada->select('stu_details', 'id, firstname, lastname, *email, mis_id', 'disabled=? ORDER BY lastname ASC', array(0));
      $data['misStudents'] = $misStudents;
      $stats = array();
      $stats['misStudentCount'] = count($misStudents);
      $stats['adaStudentCount']  = count($adaStudents);
      $data['stats'] = $stats;
      return $data;
    }


}
