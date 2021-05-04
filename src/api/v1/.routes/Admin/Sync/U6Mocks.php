<?php

/**
 * Description

 * Usage:

 */
namespace Admin\Sync;

class U6Mocks extends \Admin\Sync\Tools\Internal\Exams
{
    protected $container;
    private $results;
    private $adaSessionId;

    public function __construct(\Slim\Container $container)
    {

       parent::__construct($container);

    }

}
