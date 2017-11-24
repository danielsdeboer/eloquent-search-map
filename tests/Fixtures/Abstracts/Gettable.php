<?php

namespace Aviator\Search\Tests\Fixtures\Abstracts;

abstract class Gettable
{
    /**
     * @param $name
     * @return mixed
     * @throws \Exception
     */
    public function __get ($name)
    {
        if (method_exists($this, $name)) {
            return $this->$name();
        }

        throw new \Exception('Property ' . $name . ' not found.');
    }
}
