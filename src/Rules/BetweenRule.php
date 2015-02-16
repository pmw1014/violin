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
    public function run($name, $value, $args)
    {
        return ($value >= $args[0] && $value <= $args[1]) ? true : false;
    }
}
