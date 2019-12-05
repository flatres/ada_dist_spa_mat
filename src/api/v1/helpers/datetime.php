<?php

function tidyTime(string &$time) {
  $dateTime = new \DateTime($time);
  $time = $dateTime->format('H:i');
  return $time;
}

function convertToMysqlDatetime(&$date){

    //create an object of DateTime
    $date = new DateTime($date);
    //Format the date to the format you need
    //Y for full year (1993 or 2000 or 2017)
    //m for month with leading 0 (01-09 and 10-12)
    //d for date with leading 0 (0-9 and 10 to 31)
    //H:i:s is optional
    $date = $date->format('Y-m-d H:i:s');
    
    return $date;
    //$date now contains 2017-01-01 00:00:00

}

function convertToAdaDatetime(&$date){

    //create an object of DateTime
    $date = new DateTime($date);
    //Format the date to the format you need
    //Y for full year (1993 or 2000 or 2017)
    //m for month with leading 0 (01-09 and 10-12)
    //d for date with leading 0 (0-9 and 10 to 31)
    //H:i:s is optional
    $date = $date->format('d-m-Y H:i');
    //$date now contains 2017-01-01 00:00:00
    return $date;
}

function convertToAdaDate(&$date){

    //create an object of DateTime
    $date = new DateTime($date);
    //Format the date to the format you need
    //Y for full year (1993 or 2000 or 2017)
    //m for month with leading 0 (01-09 and 10-12)
    //d for date with leading 0 (0-9 and 10 to 31)
    //H:i:s is optional
    $date = $date->format('d-m-Y');
    //$date now contains 2017-01-01 00:00:00
    return $date;
}

function verifyMysqlDatetime($date)
{
  return (
    DateTime::createFromFormat('Y-m-d H:i:s', $date) !== false
  );
}

function verifyMysqlDate($date)
{
  return (
    DateTime::createFromFormat('Y-m-d', $date) !== false
  );
}

function verifyAdaDatetime($date)
{
  return (
    DateTime::createFromFormat('d-m-Y H:i:s', $date) !== false ||
    DateTime::createFromFormat('d-m-Y H:i', $date) !== false ||
    DateTime::createFromFormat('d-m-Y', $date) !== false
  );
}

function convertArrayToMysqlDatetime(array &$array)
{
  foreach ($array as &$item){
    if (is_array($item)) {
      foreach ($item as &$field) {
        if (verifyAdaDatetime($field)) $field = convertToMysqlDatetime($field);
      }
    } else {
      if (verifyAdaDatetime($item)) $item = convertToMysqlDatetime($item);
    }
  }
  return $array;
}

function convertArrayToAdaDatetime(array &$array)
{
  foreach ($array as &$item){
    if (is_array($item)) {
      foreach ($item as &$field) {
        if (verifyMysqlDatetime($field)) $field = convertToAdaDatetime($field);
        if (verifyMysqlDate($field)) $field = convertToAdaDate($field);
      }
    } else {
      if (verifyMysqlDatetime($item)) $item = convertToAdaDatetime($item);
      if (verifyMysqlDate($field)) $field = convertToAdaDate($field);
    }
  }
  return $array;
}

?>
