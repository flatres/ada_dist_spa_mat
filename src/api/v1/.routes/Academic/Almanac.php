<?php
use Slim\Http\UploadedFile;
/**
 * Description

 * Usage:

 */
namespace Academic;

class Almanac
{
    protected $container;
    private $console;
    private $channel = 'academic.alis.upload';

    public function __construct(\Slim\Container $container)
    {
       $this->ada = $container->ada;
       $this->adaModules = $container->adaModules;
       $this->adaData = $container->adaData;
       $this->isams = $container->isams;
       $this->mcCustom= $container->mcCustom;

    }

// ROUTE -----------------------------------------------------------------------------
    public function almanacHistoryGet($request, $response, $args)
    {
      $auth = $request->getAttribute('auth');
      $this->console = new \Sockets\Console($auth);
      $sets = [];
      $year = 12;

      $this->console->publish("Greetings.");
      $this->console->publish("Fetching The Almanac");

      $events = $this->mcCustom->query('SELECT * from TblAlmanacEvents WHERE dteDate > ? ORDER BY dteDate ASC', ['2018-09-01']);

      $data = [];
      $categories = $this->getCategories();

      foreach($events as $e) {
        $data[] = [
          'division'  => $this->getDivision($e['txtDescription']),
          'category' => $categories["id_{$e['intCategory']}"],
          'title'     => $e['txtDescription'],
          'location'  => $e['txtLocation'],
          'description' => '',
          'start'     => $e['dteDate'],
          'time'      => $e['boolAllDay'] == 0 ? $this->parseTime($e['time']) : '',
          'end'       => '',
          'endTime'   => '',
          'allDay'    => $e['boolAllDay'] == 0 ? 'No' : 'Yes',
          'approved'  => '',
          'published' => '',
          'notes'     => ''
        ];
      }

      $columns = [
        [ 'field' => 'division', 'label' => 'Division' ],
        [ 'field' => 'category', 'label' => 'Category' ],
        [ 'field' => 'title', 'label' => 'Title' ],
        [ 'field' => 'location', 'label' => 'Location' ],
        [ 'field' => 'description', 'label' => 'Description' ],
        [ 'field' => 'start', 'label' => 'Start Date' ],
        [ 'field' => 'time', 'label' => 'Start Time' ],
        [ 'field' => 'end', 'label' => 'End Date' ],
        [ 'field' => 'endTime', 'label' => 'End Time' ],
        [ 'field' => 'allDay', 'label' => 'All Day Event' ],
        [ 'field' => 'approved', 'label' => 'All Day Events' ],
        [ 'field' => 'published', 'label' => 'Approved' ],
        [ 'field' => 'notes', 'label' => 'Notes' ]
      ];

      $settings = [
        'forceText' => true
      ];
      $this->console->publish("Generating Spreadsheet");
      $sheet = new \Utilities\Spreadsheet\SingleSheet($columns, $data, $settings);

      return emit($response, $sheet->package);
      // return emit($response, $this->adaModules->select('TABLE', '*'));
    }

    private function getCategories(){
      $d = $this->isams->select('TblSchoolCalendarCategory', 'TblSchoolCalendarCategoryID as id, txtName', '1=1', []);
      $categories = ['id_0' => 'None'];
      foreach($d as $cat) {
        $categories["id_{$cat['id']}"] = $cat['txtName'];
      }
      return $categories;
    }

    private function parseTime($timeStamp) {
      $time = explode(' ', $timeStamp)[1];
      $time = explode(':', $time);
      return $time[0] . ':' . $time[1];
    }

    private function getDivision($title) {
      $t = strtolower($title);
      if (strpos($t, 'upper sixth') !== false) return 'Upper Sixth';
      if (strpos($t, 'upper school') !== false) return 'Upper School';
      if (strpos($t, 'lower sixth') !== false) return 'Lower Sixth';
      if (strpos($t, 'lower school') !== false) return 'Lower School';
      if (strpos($t, 'hundred') !== false) return 'Hundred';
      if (strpos($t, 'remove') !== false) return 'Remove';
      if (strpos($t, 'shell') !== false) return 'Shell';
      return 'All';
    }

}
