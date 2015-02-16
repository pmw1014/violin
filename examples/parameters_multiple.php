<?php

require '../vendor/autoload.php';

use Violin\Violin;

$v = new Violin;

$v->addFieldMessages([
    'age' => [
        'between'   => 'Your age is {input}. To buy a 16-25 railcard you need to be between {value} and {value:1}',
        'min'       => 'You\'re too young to be travelling on your own.'
    ]
]);

$v->validate([
    'age' => 5,
], [
    'age' => 'required|between(16, 25)|min(10)'
]);

if($v->valid()) {
    echo 'Valid!';
} else {
    echo '<pre>', var_dump($v->messages()->all()), '</pre>';
}
