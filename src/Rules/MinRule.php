<?php

namespace Violin\Rules;

use Violin\Contracts\RuleContract;

class MinRule implements RuleContract
{
    public function run($value, $input, $args)
    {
        return (float) $value >= (float) $args[0];
    }
}
