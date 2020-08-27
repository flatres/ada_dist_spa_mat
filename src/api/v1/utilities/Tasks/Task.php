<?php

namespace Utilities\Tasks;

class Task
{
    private $jobId, $ada;

    public function __construct($jobId)
    {
      date_default_timezone_set('Europe/London');
      $this->jobId = (int)$jobId;

      $this->ada = new \Dependency\Databases\Ada();
      $timestamp = date("Y-m-d H:i:s", time());
      $this->ada->update('auto_jobs', 'last_run=?', 'id=?', [$timestamp, $this->jobId]);

      $this->run();
    }
}
