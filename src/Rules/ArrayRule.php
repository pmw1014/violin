<?php

namespace Violin\Rules;

use Violin\Contracts\RuleContract;

class ArrayRule implements RuleContract
{
    public function run($value, $input, $args)
    {
        return is_array($value);
    }
}
