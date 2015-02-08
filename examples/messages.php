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

    echo '<pre>', var_dump($v->messages()->get('name')), '</pre>'; // Array of messages for name field
    echo '<pre>', var_dump($v->messages()->get('age')), '</pre>'; // Array of messages for age field
    echo '<pre>', var_dump($v->messages()->first('name')), '</pre>'; // "name is required"
    echo '<pre>', var_dump($v->messages()->first('age')), '</pre>'; // "age must be a number"
}
