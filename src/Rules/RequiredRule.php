<?php

namespace Violin\Rules;

use Violin\Contracts\RuleContract;

class RequiredRule implements RuleContract
{
    public function run($value, $input, $args)
    {
        if (is_scalar($value)) {
            $value = preg_replace('/^[\pZ\pC]+|[\pZ\pC]+$/u', '', $value);
        }
        return !empty($value) || (is_scalar($value) && $value == "0");
    }

    public function error()
    {
        return '{field} is required.';
    }

    public function canSkip()
    {
        return false;
    }
}
