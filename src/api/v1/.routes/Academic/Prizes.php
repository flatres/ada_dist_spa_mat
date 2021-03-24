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
    public function CMPrizesGet($request, $response, $args)
    {
      $i = 1;
      $data = ['yo'];
      // $prizes = $this->isams->select(
      //   'TblRewardsManagerRewards',
      //   txt
      // );
      $prizes = $this->isams->query(
        "SELECT txtSchoolID, count(txtSchoolID) as count FROM
        TblRewardsManagerRewards
        WHERE txtDate BETWEEN '1 Jan 2021' AND '31 Mar 2021'
        GROUP BY txtSchoolID
        ",
        []
      );
      $final = [];
      foreach($prizes as &$prize){
        $prize['id'] = $i;
        $i++;
        $pupilID = $prize['txtSchoolID'];
        $count = (int)$prize['count'];
        $adaPupil = (new \Entities\People\Student($this->ada))->byMISId($pupilID);
        $pupil = new \Entities\People\iSamsStudent($this->isams, $pupilID);

        if ($pupil->NCYear < 11 && $count < 25) continue;
        if ($pupil->NCYear == 11 && $count < 30) continue;
        if ($pupil->NCYear > 11) continue;

        $prize['firstName']= $pupil->firstName;
        $prize['lastName'] = $pupil->lastName;
        $prize['pupilEmail']= $adaPupil->email;
        $prize['NCYear'] = $adaPupil->NCYear;
        $prize['gender'] = $pupil->gender;
        $contacts = $pupil->getContacts();
        $i = 1;
        foreach ($contacts as $c){
          $prize["email$i"] = $c['email'];
          $i++;
        }
        $final[] = $prize;

      }
      return emit($response, $final);
      // return emit($response, $this->adaModules->select('TABLE', '*'));
    }


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
        $adaPupil = (new \Entities\People\Student($this->ada))->byMISId($pupilID);
        $prize['firstName']= $pupil->firstName;
        $prize['lastName'] = $pupil->lastName;
        $prize['pupilEmail']= $adaPupil->email;
        $prize['NCYear'] = $adaPupil->NCYear;
        $prize['gender'] = $pupil->gender;
        $contacts = $pupil->getContacts();
        $i = 1;
        foreach ($contacts as $c){
          $prize["ls$i"] = $c['letterSalulation'];
          $prize["email$i"] = $c['email'];
          $i++;
        }


      }
      return emit($response, $prizes);
      // return emit($response, $this->adaModules->select('TABLE', '*'));
    }

}
