<?php

namespace Violin\Rules;

use Violin\Contracts\RuleContract;

class RegexRule implements RuleContract
{
    public function run($value, $input, $args)
    {
        return (bool) preg_match($args[0], $value);
    }
}
