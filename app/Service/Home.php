<?php
namespace App\Service;

use Psr\Container\ContainerInterface;

class Home extends Service
{
    function __construct(ContainerInterface $c)
    {
        parent::__construct($c);
    }
}
?>