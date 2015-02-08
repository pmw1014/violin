<?php

namespace Violin\Rules;

class BetweenRule
{
    /**
     * Run the validation
     *
     * @param  string $name
     * @param  mixed $value
     * @return bool
     */
    public function run($name, $value, $param1, $param2)
    {
        return ($value >= $param1 && $value <= $param2) ? true : false;
    }
}
