<?php

namespace Violin\Rules;

class MinRule
{
    /**
     * Run validation.
     *
     * @param string $name
     * @param int|float $value
     * @param mixed $param1
     * @return bool
     */
    public function run($name, $value, $param1)
    {
        return (float) $value >= (float) $param1;
    }
}
