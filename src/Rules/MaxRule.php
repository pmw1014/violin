<?php

namespace Violin\Rules;

use Violin\Contracts\RuleContract;

class MaxRule implements RuleContract
{
    public function run($value, $input, $args)
    {
        return (float) $value <= (float) $args[0];
    }
}
