<?php

require '../vendor/autoload.php';

use Violin\Violin;

$v = new Violin;

$v->addFieldMessages([
    'username' => [
        'required' => 'You need to enter a username to sign up.'
    ],
    'age' => [
        'required' => 'I need your age.',
        'int' => 'Your age needs to be an integer.',
    ]
]);

$v->validate([
    'name' => 'Billy',
    'username' => '',
    'age' => 'Twenty One'
], [
    'name' => 'required',
    'username' => 'required',
    'age' => 'required|int'
]);

if($v->valid()) {
    echo 'Valid!';
} else {
    echo '<pre>', var_dump($v->messages()->all()), '</pre>';
}
