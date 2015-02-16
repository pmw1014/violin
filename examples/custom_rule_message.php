<?php

require '../vendor/autoload.php';

use Violin\Violin;

$v = new Violin;

$v->addRuleMessage('required', 'You better fill in the {field} field, or else.');

$v->validate([
    'name'  => '',
    'age'   => 20
], [
    'name'  => 'required',
    'age'   => 'required|int'
]);

if($v->valid()) {
    echo 'Valid!';
} else {
    echo '<pre>', var_dump($v->messages()->all()), '</pre>';
}
