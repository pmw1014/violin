<?php

namespace Violin\Rules;

use Violin\Contracts\RuleContract;

class BoolRule implements RuleContract
{
    public function run($value, $input, $args)
    {
        return is_bool($value);
    }
}
