<?php

namespace Violin\Rules;

class IpRule
{
    /**
     * Run the validation
     *
     * @param  string $name
     * @param  mixed $value
     * @return bool
     */
    public function run($name, $value)
    {
        return filter_var($value, FILTER_VALIDATE_IP) !== false;
    }
}
