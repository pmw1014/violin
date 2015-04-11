<?php

require '../../vendor/autoload.php';

use Violin\Violin;

$v = new Violin;

$v->validate([
    'username'  => ['dalegarrett1234567890', 'required|alpha|min(3)|max(20)'],
    'email'     => ['dale.codecourse.com', 'required|email']
]);

var_dump($v->errors()->all()); // Array of all errors.
