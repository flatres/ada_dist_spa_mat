<?php

/**
 * Description

 * Usage:

 */
namespace Location\Chapel;

class Chapel
{
    protected $container;

    public function __construct(\Slim\Container $container)
    {
       $this->sql= $container->mysql;
       $this->isams = $container->isams;
    }

    //process new modules and return json of new modules
    public function chapelAttendance_GET($request, $response, $args){

      $userId = $request->getAttribute('userId');
      //date comes in format DD-MM-YYYY
      $date = strtotime($args['date']);

    	$JSON =array();
      // $c_chapel = new Locations\Chapel\Tools\Attendance();
      $data = array();

      try {

        // $data = $c_chapel->byDate($date);
        return emit($response, $data);


      } catch (Exception $e) {

        return emit($response, array('message'=>$e->getMessage(), 'trace'=>$e->getTraceAsString()));
        return;

      }

    }

    private function byDate($unixDate){

      $this->pin = 0;
      $this->card = 0;
      $this->error = 0;


      $this->c_exgMgr = new \Exgarde\Manager();

      $this->students = $this->c_exgMgr->getAreaByDate($unixDate, 10669778); //id of MCAttendance Area on Exgarde  10669778  //gym 10512410

      $studentByKey = array();
      //put into key array for removing later
      foreach($this->students as $student){

          $studentByKey['s_' . $student['id']] = $student;

      }

      unset($student);

      $idsForRemoval = array();

      foreach($studentByKey as &$student){

        if($student['style']=='PIN') ++$this->pin;
        if($student['style']=='Card') ++$this->card;
        if($student['style']=='error') ++$this->error;

        if(!isset($student['boarding'])) $student['boarding'] = 'err';

        if($student['style']=='error' || $student['boarding']=='Staff') $idsForRemoval[]= $student['id'];

        $hour = date('G', $student['entry_unix']);

        switch(TRUE){

          case ($hour<10) : $student['session'] = 'early'; break;
          case ($hour<16) : $student['session'] = 'late'; break;
          default : $student['session'] = 'talk'; break;

        }

      }



      $this->idsForRemoval = $idsForRemoval;

      //unset the entries with an error
      foreach($idsForRemoval as $id){  unset($studentByKey['s_'.$id]);}

      $this->students = array_values($studentByKey);

      //get priv Students. Chapel is on Sunday and Privs are logged for the saturday so go back one cal_days_in_month
      $yesterday = $unixDate - 24*60*60;
      $this->c_Privs = new \Projects\Privs();
      $this->c_Privs->getPrivsByDate($yesterday);


      $this->sortIntoCategories();
      $this->sortIntoBoarding();

      return $this;


    }


    // public function ($request, $response, $args)
    // {
    //   $userId = $request->getAttribute('userId');
    //   return emit($response, $data);
    // }
}
