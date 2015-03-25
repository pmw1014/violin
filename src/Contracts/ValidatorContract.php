<?php

namespace Violin\Contracts;

use Closure;

interface ValidatorContract
{
    public function validate(array $input, array $rules);
    public function passed();
    public function failed();
    public function errors();
    public function addRuleMessage($rule, $message);
    public function addRuleMessages(array $messages);
    public function addFieldMessage($field, $rule, $message);
    public function addFieldMessages(array $messages);
    public function addRule($name, Closure $callback);
}
