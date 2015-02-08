<?php

require '../vendor/autoload.php';

use Violin\Violin;

$v = new Violin;

$v->addRuleMessages([
    'required' => 'You better fill in the {field} field, or else.',
    'int' => 'The {field} field needs to be an integer, but I found {input}.',
]);

$v->validate([
    'name' => '',
    'age' => ''
], [
    'name' => 'required',
    'age' => 'required|int'
]);

if($v->valid()) {
    echo 'Valid!';
} else {
    echo '<pre>', var_dump($v->messages()->all()), '</pre>';
}
