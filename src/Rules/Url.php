<?php

namespace Violin\Rules;

class Url
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
        return filter_var($value, FILTER_VALIDATE_URL) !== false;
    }
}
