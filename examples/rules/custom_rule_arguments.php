<?php

require '../vendor/autoload.php';

use Violin\Violin;

$v = new Violin;

$v->addRuleMessage('startsWith', 'The {field} must start with "{arg0}".');

$v->addRule('startsWith', function($value, $input, $args) {
    $value = trim($value);
    return $value[0] === $args[0];
});

$v->validate([
    'username'  => ['dale', 'required|min(3)|max(20)|startsWith(a)']
]);

if ($v->passes()) {
    // Passed
} else {
    var_dump($v->errors()->all());
}
