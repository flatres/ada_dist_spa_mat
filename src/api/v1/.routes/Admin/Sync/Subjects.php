<?php

/**
 * Description

 * Usage:

 */
namespace Admin\Sync;

class Subjects
{
    protected $container;
    private $disabledCount = 0;
    private $updatedCount = 0;
    private $deletedCount = 0;
    private $newCount = 0;

    public function __construct(\Slim\Container $container)
    {
       $this->isams= $container->isams;
       $this->sql = $container->mysql;
       $this->ada = $container->ada;

    }

    public function misSubjects_GET($request, $response, $args)
    {
      $data = $this->getSubjectsComparison();
      return emit($response, $data);
    }

    //ensure that ADA Subjects are up to date with iSams
    public function subjectsSync_POST($request, $response, $args)
    {
      $data = array('id'=>2);
      $auth = $request->getAttribute('auth');

      $console = new \Sockets\Console($auth);
      $this->progress = new \Sockets\Progress($auth, 'Admin/Sync/Subjects');

      $console->publish('Pulling iSAMS Subjects...');

      //delete old subjects and classes
      // TODO: Write sync routines for all of this
      $this->ada->delete('sch_classes', 'id>0', []);
      $this->ada->delete('sch_class_exams', 'id>0', []);
      $this->ada->delete('sch_class_students', 'id>0', []);
      $this->ada->delete('sch_class_teachers', 'id>0', []);
      $this->ada->delete('sch_subjects', 'id>0', []);
      $this->ada->delete('sch_subjects_exams', 'id>0', []);

      $misSubjects = $this->isams->select(
        'TblTeachingManagerSubjects',
        'TblTeachingManagerSubjectsID as id, txtSubjectName as name, txtSubjectCode as code',
        'TblTeachingManagerSubjectsID > ?',
        [0]);

      $console->publish('Got ' . count($misSubjects), 1);

      // get ada Subjects to get comparison for syncing
      $console->publish('Pulling ADA Subjects');
      $adaSubjects = $this->sql->select('sch_subjects', 'id, misId', '1=1 ORDER BY name ASC', []);
      $console->publish('Got ' . count($adaSubjects), 1);


      $console->publish('Syncing...');
      $allSubjects = array();
      foreach($adaSubjects as $subject)
      {
        $misId = $subject['misId'];
        $adaId = $subject['id'];
        $allSubjects["s_$misId"] = array(
          'adaId'   => $adaId,
          'misId'   => $misId,
          'isNew'   => false,
          'disabled' => true
        );
      }
      //Subjects not appearing in the MIS will be deleted
      foreach($misSubjects as $subject)
      {
        $misId = $subject['id'];
        if (isset($allSubjects["s_$misId"])) //already exists, just needs updating
        {
          $allSubjects["s_$misId"]['disabled'] = false;
          $allSubjects["s_$misId"]['misData'] = $subject;
        } else {
          $allSubjects["s_$misId"] = array(
            'adaId'   => null,
            'misId'   => $misId,
            'isNew'   => true,
            'disabled' => false,
            'misData' => $subject
          );
        }
      }
      $count = count($allSubjects);
      $i = 1;
      //update
      foreach($allSubjects as &$subject){
        $subject['error'] = $subject['isNew'] == true ? $this->newSubjects($subject) : $this->updateSubjects($subject);
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

    private function newSubjects($subject)
    {
      $d = $subject['misData'];

      if ($d['code'] === 'FM' || $d['name'] === 'ESS') return;

      //weirdly Computer Science is in isams as CO but comes out of the exams systems correctly as case
      if ($d['code'] === 'CO') $d['code'] = 'CS';
      $id = $this->sql->insert(
        'sch_subjects',
        'misId, name, code',
        array(
          $d['id'],
          $d['name'],
          $d['code']
        )
      );

      $subject['adaId'] = $id;


      //Ignore form for now as clashees with Further Maths
      if ($d['code'] === 'FM' || $d['name'] === 'ESS') {
        return;
        $this->updateClasses($subject);
        $this->newCount++;
        return;
       }

      $this->sql->insert('sch_subjects_exams', 'subjectId, examName, examCode', [$id, $d['name'], $d['code']]);

      switch ($d['code']){
        case 'EN' :
          $this->sql->insert('sch_subjects_exams', 'subjectId, examName, examCode', [$id, 'Literature in English', 'ENLIT']);
          break;
        case 'PH' :
          $this->sql->insert('sch_subjects_exams', 'subjectId, examName, examCode, aliasCode', [$id, 'Double Science', 'PHS2', 'PH']);
          break;
        case 'CH' :
          $this->sql->insert('sch_subjects_exams', 'subjectId, examName, examCode, aliasCode', [$id, 'Double Science', 'CHS2', 'CH']);
          break;
        case 'BI' :
          $this->sql->insert('sch_subjects_exams', 'subjectId, examName, examCode, aliasCode', [$id, 'Double Science', 'BIS2', 'BI']);
          break;
        case 'MA' :
          $this->sql->insert('sch_subjects_exams', 'subjectId, examName, examCode', [$id, 'Further Mathematics', 'FM']);
          $this->sql->insert('sch_subjects_exams', 'subjectId, examName, examCode', [$id, 'Maths in Context', 'MC']);
          break;
      }

      $this->updateClasses($subject);
      $this->newCount++;


    }

    private function updateSubjects($subject)
    {
      $d = $subject['misData'];
      if ($d['code'] === 'FM' || $d['name'] === 'ESS') return;
      if ($subject['disabled'] == true)
      {
        $this->sql->delete('sch_subjects', 'id=?', array($subject['adaId']));
        $this->sql->delete('sch_subjects_exams', 'subjectId=?', array($subject['adaId']));
        $this->sql->delete('sch_classes', 'subjectId=?', array($subject['adaId']));
        $this->deletedCount++;
      } else {

        $this->sql->update(
          'sch_subjects',
          'misId=?, name=?, code=?',
          'id=?',
          [
            $d['id'],
            $d['name'],
            $d['code'],
            $subject['adaId']
          ]
        );
        $this->updateClasses($subject);
        $this->updatedCount++;
      }
    }

    private function updateClasses($subject){

        $misSubjectId = $subject['misData']['id'];
        $subjectId = $subject['adaId'];

        // Will want to add a syncing routing to this eventually
        $this->sql->delete('sch_classes', 'subjectId=?', [$subjectId]);
        $this->sql->delete('sch_class_students', 'subjectId=?', [$subjectId]);
        $this->sql->delete('sch_class_teachers', 'subjectId=?', [$subjectId]);

        //isams sets
        $sets = $this->isams->select(
          'TblTeachingManagerSets',
          'TblTeachingManagerSetsID as id',
          'intSubject = ?', [$misSubjectId]);

        foreach($sets as $s) {
          $set = new \Entities\Academic\iSamsSet($this->isams, $s['id']);
          $classId = $this->sql->insert(
            'sch_classes',
            'misId, subjectId, code, year, academicLevel',
            [$s['id'], $subjectId, $set->setCode, $set->NCYear, $set->academicLevel] );

          //exam types
          foreach($set->examCodes as $examCode){
            $ex = $this->sql->select('sch_subjects_exams', 'id', 'examCode=?', [$examCode]);
            if (!isset($ex[0])) {
              $exId = $this->sql->insert('sch_subjects_exams', 'subjectId, examCode', [$subjectId, $examCode]);
            } else {
              $exId = $ex[0]['id'];
            }
            $this->sql->insert('sch_class_exams', 'classId, examId, subjectId', [$classId, $exId, $subjectId]);
          }

          //teachers
          foreach($set->teachers as $t){
              if ($t->id) $this->sql->insert('sch_class_teachers', 'subjectId, classId, userId', [$subjectId, $classId, $t->id]);
          }
          //students
          $students = $set->getStudents();
          foreach($students as $s) {
            if ($s->id) $this->sql->insert('sch_class_students', 'subjectId, classId, studentId', [$subjectId, $classId, $s->id]);
          }
        }


        //isams forms
        $forms = $this->isams->select('TblTeachingManagerSubjectForms', 'TblTeachingManagerSubjectFormsID as id', 'intSubject=?', [$misSubjectId]);

        foreach($forms as $f) {
          $form = new \Entities\Academic\iSamsForm($this->isams, $f['id'], false);
          $classId = $this->sql->insert(
            'sch_classes',
            'misId, subjectId, code, year, academicLevel, isForm, misFormId',
            [$f['id'], $subjectId, $form->setCode, $form->NCYear, $form->academicLevel, true, $form->formId]);

          //exam types
          foreach($form->examCodes as $examCode){
            $ex = $this->sql->select('sch_subjects_exams', 'id', 'examCode=?', [$examCode]);
            if (!isset($ex[0])) {
              $exId = $this->sql->insert('sch_subjects_exams', 'subjectId, examCode', [$subjectId, $examCode]);
            } else {
              $exId = $ex[0]['id'];
            }
            $this->sql->insert('sch_class_exams', 'classId, examId, subjectId', [$classId, $exId, $subjectId]);
          }
          //teachers
          foreach($form->teachers as $t){
              if ($t->id) $this->sql->insert('sch_class_teachers', 'subjectId, classId, userId', [$subjectId, $classId, $t->id]);
          }
          //students
          $students = $form->getStudents();
          foreach($students as $s) {
            if ($s->id) $this->sql->insert('sch_class_students', 'subjectId, classId, studentId', [$subjectId, $classId, $s->id]);
          }
        }
    }


    private function getSubjectsComparison()
    {
      $data = array();
      $misSubjects = $this->isams->select(
        'TblTeachingManagerSubjects',
        'TblTeachingManagerSubjectsID as id, txtSubjectName as name, txtSubjectCode as code',
        'TblTeachingManagerSubjectsID > ?',
        [0]);

      // //get ada Subjects to get comparison for syncing
      $adaSubjects = $this->sql->select('sch_subjects', 'id', '1=1 ORDER BY name ASC', []);
      $data['misSubjects'] = $misSubjects;
      $stats = array();
      $stats['misSubjectsCount'] = count($misSubjects);
      $stats['adaSubjectsCount']  = count($adaSubjects);
      $data['stats'] = $stats;
      return $data;
    }


}
