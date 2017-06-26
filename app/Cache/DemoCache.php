<?php
namespace App\Cache;

use Psr\Container\ContainerInterface;

class DemoCache
{
    protected $redis;

    function __construct(ContainerInterface $c)
    {
        $this->redis = $c->get('redis');
    }
}
?>