<?php

/**
 * Description

 * Usage:

 */
namespace Entities\Exams;

class MLO
{

    private $sql;

    public function __construct(\Dependency\Databases\AdaData $ada = null)
    {
       $this->sql= $ada ?? new \Dependency\Databases\AdaData();
       return $this;
    }

    public function getActiveSession(int $yearGroup) {
      $session = $this->sql->select('mlo_sessions', 'id, name, timestamp, yearGroup, isActive', 'yearGroup=? AND isActive=?', [$yearGroup, 1]);
      if (isset($session[0])) return $session[0];
      return null;
    }

    public function newMLO(int $sessionId, int $userId, int $studentId, int $examId, $mloCurrent, $mloPotential)
    {
      $mlo = $this->sql->select(
        'mlo',
        'id',
        'session_id = ? AND user_id = ? AND student_id = ? AND exam_id = ?',
        [$sessionId, $userId, $studentId, $examId]
      );
      if (isset($mlo[0])) {
        // update
        $id = $mlo[0]['id'];
        $this->sql->update(
          'mlo',
          'session_id = ?, user_id = ?, student_id = ?, exam_id = ?, mlo_current = ?, mlo_potential = ?',
          'id = ?',
          [$sessionId, $userId, $studentId, $examId, $mloCurrent, $mloPotential, $mlo[0]['id']]
        );

      } else {
        // new
        $this->sql->insert(
          'mlo',
          'session_id, user_id, student_id, exam_id, mlo_current, mlo_potential',
          [$sessionId, $userId, $studentId, $examId, $mloCurrent, $mloPotential]
        );
      }

    }

    //fetches the most recent MLO from this subject and teacher
    public function getSingleMLO(int $studentId, int $examId, int $userId) {
      $mlo = $this->sql->select(
        'mlo',
        'session_id, exam_id, student_id, mlo_current, mlo_potential',
        'student_id = ? AND exam_id = ? AND user_id = ? ORDER BY timestamp DESC',
        [$studentId, $examId, $userId]
      );
      return isset($mlo[0]) ? $mlo[0] : null;
    }

    public function getStudentMLO($studentId, $subjectId = null) {
      // $isamsStudent = new
    }

    public function makeProfile(\Entities\People\Student &$student, $examId = null) {
      //get a list of exams and collect MLO Grades
      $exams = [];
      $examsInfo = [];
      $returnExam = [];
      foreach($student->examData['mlo'] as $m){
        $examId = $m['examId'];
        $key = "id" . $examId;
        if (!isset($exams[$key])) {
          $exams[$key] = [];
          $examsInfo[] = new \Entities\Academic\SubjectExam(null, $examId);
        }
        if ($m['mlo']) {
          $grade = $m['mlo']['mlo_current'];
          $exams[$key][] = [
            'points'  => (new \Entities\Exams\Grade($grade))->points,
            'grade'   => $grade
          ];
        }
      }
      //extract the highest and lowest mlo for each exam
      foreach($examsInfo as &$e) {
        $key = "id" . $e->id;
        $results = sortArrays($exams[$key], 'points');
        $e->mloMax = $results[0]['grade'] ?? null ;
        $e->mloMin = $results[count($results) - 1]['grade'] ?? null ;
        if ($e->id == $examId) $returnExam = $e;
      }
      $student->exams = $examsInfo;
      return $returnExam;
    }


}
