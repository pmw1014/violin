<?php

namespace Violin\Contracts;

interface RuleContract
{
    public function run($value, $input, $args);
}
