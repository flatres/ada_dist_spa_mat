<?php

/**
 * Description

 * Usage:

 */
namespace Academic;

class Prizes
{
    protected $container;

    public function __construct(\Slim\Container $container)
    {
       $this->ada = $container->ada;
       $this->adaModules = $container->adaModules;
       $this->isams = $container->isams;
       $this->mcCustom= $container->mcCustom;
    }

// ROUTE -----------------------------------------------------------------------------
    public function PrizesGet($request, $response, $args)
    {
      $i = 1;
      $data = ['yo'];
      $prizes = $this->mcCustom->select('TblPrizesPupils', '*', 'dtePrizeAwarded=?', ['2020-05-23']);
      foreach($prizes as &$prize){
        $prize['id'] = $i;
        $i++;
        $pupilID = $prize['txtSchoolID'];
        $prizeID = $prize['intPrizeID'];
        $prizeData = $this->mcCustom->select('TblPrizesPrizes', '*', 'TblPrizesPrizesID=?', array($prizeID));
        $prize = array_merge($prize, $prizeData[0]);

        $pupil = new \Entities\People\iSamsStudent($this->isams, $pupilID);
        $prize['firstName']= $pupil->firstName;
        $prize['lastName'] = $pupil->lastName;
        $prize['gender'] = $pupil->gender;
        $contacts = $pupil->getContacts();
        $i = 1;
        foreach ($contacts as $c){
          $prize["email$i"] = $c['email'];
          $i++;
        }


      }
      return emit($response, $prizes);
      // return emit($response, $this->adaModules->select('TABLE', '*'));
    }

}
