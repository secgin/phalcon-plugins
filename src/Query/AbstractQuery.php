<?php

namespace YG\Phalcon\Query;

abstract class AbstractQuery
{
    public function __get($name)
    {
        if (property_exists($this, $name))
            return $this->$name;

        return null;
    }
}