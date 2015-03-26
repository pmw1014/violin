<?php

namespace Violin\Rules;

use Violin\Contracts\RuleContract;

class IntRule implements RuleContract
{
    public function run($value, $input, $args)
    {
        return is_numeric($value) && (int)$value == $value;
    }
}
