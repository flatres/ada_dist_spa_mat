<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once dirname(__FILE__).'/tools/db/c_sql.php';
require_once dirname(__FILE__).'/dependencies/MSSql.php';
require_once dirname(__FILE__).'/dependencies/Databases/ISams.php';



$path = $_SERVER['DOCUMENT_ROOT'] . '/api/';
$vpath = $_SERVER['DOCUMENT_ROOT'] . '/api/v2/';


$sql = new SQL();
$isams = new ISams();

echo '<h3>' . date('m/d/Y h:i:s a', time())  . ' </h3>';

echo 'Clearing Tables' . PHP_EOL;

$sql->delete('u_logins', 'id>0');
$sql->delete('u_details', 'id>0');
$sql->delete('t_tagmap',   'id>0');


echo 'Getting Students' . PHP_EOL;;

$studentData = $isams->select(  'TblPupilManagementPupils',
                                    'txtSchoolID, txtForename, txtPreName, txtEmailAddress, txtSurname, txtFullName, txtInitials, txtGender, txtDOB, intEnrolmentNCYear, txtBoardingHouse, txtLeavingBoardingHouse, intEnrolmentSchoolYear',
                                    'intSystemStatus = 1', array());


echo count($studentData);
$i = 1;
foreach($studentData as $s){

  echo '<p style="font-size:8px; margin:1px; padding:1px">' . $i .' : '. $s['txtSurname'] . ' ' . $s['txtForename']  . '('. $s['txtPreName'] .')'. ' - ' . $s['txtBoardingHouse'] . '</p>';

  $bind = array($s['txtSchoolID'], 1255);
  $choir = $isams->select('TblPupilManagementSelectionsStudents', 'txtSchoolID','txtSchoolID = ? AND intSelectionID=?', $bind);
  $choir = isset($choir[0]) ? 1 : 0;
  echo "<p>
  $choir
  </p>";

  $bind = array($i, 223, $s['txtForename'], $s['txtSurname'], $s['txtBoardingHouse'], $s['txtPreName'], $s['txtFullName'], $s['txtEmailAddress'], $choir, 1, $s['txtSchoolID'], $s['txtForename'], $s['txtSurname']);



  $sql->insert( 'u_details',
                'userid, school, firstname_plain, lastname_plain, boarding, prename, fullname, email, choir, is_student, mis_id, *firstname, *lastname',
                $bind);

   $tagID = $sql->select('t_tags', 'id', 'name=?', array($s['txtBoardingHouse']))[0]['id'];
   $sql->insert('t_tagmap', 'user_id, tag_id', array($i, $tagID));
   $i++;
};
// var_dump($studentData);



?>
