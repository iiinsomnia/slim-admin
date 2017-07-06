<?php
namespace App\Service;

class Identity
{
    public function __get($name)
    {
        return property_exists($this, $name) ? $this->$name : NULL;
    }

    public function __set($name, $value)
    {
        $this->$name = $value;
    }
}
?>