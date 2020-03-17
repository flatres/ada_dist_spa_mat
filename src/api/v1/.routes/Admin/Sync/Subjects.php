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
        $misId = $subjects['misId'];
        $adaId = $subjects['id'];
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

      $id = $this->sql->insert(
        'sch_subjects',
        'misId, name, code',
        array(
          $d['id'],
          $d['name'],
          $d['code']
        )
      );

      $this->newCount++;
    }

    private function updateSubjects($subject)
    {
      if ($subject['disabled'] == true)
      {
        $this->sql->delete('sch_subjects', 'id=?', array($subject['adaId']));
        $this->deletedCount++;
      } else {
        $d = $subject['misData'];
        $this->sql->update(
          'sch_subjects',
          'midId, name=?, code=?',
          'id=?',
          array(
            $d['id'],
            $d['name'],
            $d['code'],
            $subjects['adaId']
          )
        );
        $this->updatedCount++;
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
