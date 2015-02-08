<?php

require '../vendor/autoload.php';

use Violin\Violin;

$v = new Violin;

$v->validate([
    'name' => '',
    'age' => 'twenty'
], [
    'name' => 'required',
    'age' => 'required|int'
]);

if($v->valid()) {
    echo 'Valid!';
} else {
    echo '<pre>', var_dump($v->messages()->all()), '</pre>';
}
