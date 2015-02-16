<?php

namespace Violin\Rules;

class MaxRule
{
    /**
     * Run validation.
     *
     * @param string $name
     * @param int|float $value
     * @param mixed $param1
     * @return bool
     */
    public function run($name, $value, $args)
    {
        return (float) $value <= (float) $args[0];
    }
}
