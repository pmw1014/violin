<?php

require '../../vendor/autoload.php';

use Violin\Violin;

$v = new Violin;

$v->validate([
    'username'  => ['dalegarrett1234567890', 'required|alpha|min(3)|max(20)'],
    'email'     => ['dale.codecourse.com', 'required|email']
]);

if ($v->errors()->has('email')) { // Check if any errors exist for 'email'.
    echo $v->errors()->first('email'); // First 'email' error (string).
}
