<?php

namespace Violin\Rules;

use Violin\Contracts\RuleContract;

class AlphaRule implements RuleContract
{
    public function run($value, $input, $args)
    {
        return (bool) preg_match('/^[\pL\pM]+$/u', $value);
    }
}
