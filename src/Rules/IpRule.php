<?php

namespace Violin\Rules;

use Violin\Contracts\RuleContract;

class IpRule implements RuleContract
{
    public function run($value, $input, $args)
    {
        return filter_var($value, FILTER_VALIDATE_IP) !== false;
    }
}
