<?php

namespace Violin\Rules;

class Zip
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
        return preg_match('/^\d{5}(-\d{4})?$/u', $value);
    }
}
