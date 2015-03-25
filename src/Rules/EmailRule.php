<?php

namespace Violin\Rules;

use Violin\Contracts\RuleContract;

class EmailRule implements RuleContract
{
    public function run($value, $input, $args)
    {
        return filter_var($value, FILTER_VALIDATE_EMAIL) !== false;
    }
}
