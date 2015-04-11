<?php

require '../vendor/autoload.php';

use Violin\Violin;

$v = new Violin;

$v->addRuleMessage('between', 'The {field} must be between {arg0} and {arg1}, you gave {value}');

$v->validate([
    'age'  => ['82', 'required|between(18, 35)']
]);

if ($v->passes()) {
    // Passed
} else {
    var_dump($v->errors()->all());
}
