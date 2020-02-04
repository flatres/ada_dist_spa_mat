<?php

/**
 * Description

 * Usage:

 */
namespace Home;

class Almanac
{
    protected $container;

    public function __construct(\Slim\Container $container)
    {
       $this->ada = $container->ada;
       $this->adaModules = $container->adaModules;
       $this->isams = $container->isams;
    }

// ROUTE -----------------------------------------------------------------------------
    public function almanacGet($request, $response, $args)
    {
      $sql = $this->isams;
      // var_dump($this->isams);

      $d = $this->isams->select(
        'TblSchoolCalendar',
        'TblSchoolCalendarID as id,
         txtStartDate as date,
         intAllDayEvent as isAllDayEvent,
         txtStartTime as startTime,
         txtEndTime as endTime,
         txtDescription as description,
         intCategory as categoryId,
         intSubCategory as subcategoryId,
         txtLocation as location',
        'TblSchoolCalendarID > 1 ORDER BY txtStartDate ASC',
        []
      );
      $categories = $this->getCategories();

      $events = [];
      foreach($d as $event) {
        $event['category'] = $categories["id_{$event['categoryId']}"];
        $events[] = $this->processEvent($event);
      }
      return emit($response, $events);
    }

    private function processEvent(&$event){
      $event['id'] = (int)$event['id'];
      $event['categoryId'] = (int)$event['categoryId'];
      if ($event['startTime']) {
        //extract date from startdate stamp which has time set to 00:00
        $startDateStamp = strtotime($event['date']);
        $date = date('d-m-Y', $startDateStamp);
        $dateISO = date('Y-m-d', $startDateStamp);
        $startTimeStamp = strtotime($event['startTime']);
        $startTime = date('G:i', $startTimeStamp);
        $event['start'] = "$date $startTime";
        $event['startISO'] = "$dateISO $startTime";
      }
      if ($event['endTime']) {
        $endDateStamp = strtotime($event['endTime']);
        $event['endTime'] = date('G:i', $endDateStamp);
      }
      $event['isAllDayEvent'] = (int)$event['isAllDayEvent'];
      unset($event['startTime']);
      unset($event['date']);
      return $event;
    }

    private function getCategories(){
      $d = $this->isams->select('TblSchoolCalendarCategory', 'TblSchoolCalendarCategoryID as id, txtName', '1=1', []);
      $categories = ['id_0' => 'None'];
      foreach($d as $cat) {
        $categories["id_{$cat['id']}"] = $cat['txtName'];
      }
      return $categories;
    }

    public function ROUTEPost($request, $response)
    {
      $data = $request->getParsedBody();
      $data['id'] = $this->adaModules->insertObject('TABLE', $data);
      return emit($response, $data);
    }

    public function ROUTELocationsPut($request, $response)
    {
      $data = $request->getParsedBody();
      return emit($response, $this->adaModules->updateObject('TABLE', $data, 'id'));
    }

    public function ROUTEDelete($request, $response, $args)
    {
      return emit($response, $this->adaModules->delete('TABLE', 'id=?', array($args['id'])));
    }

}
