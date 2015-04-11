<?php

require '../vendor/autoload.php';

use Violin\Violin;

$v = new Violin;

$v->addRuleMessages([
    'required' => 'Hold up, the {field} field is required!',
    'email' => 'That doesn\'t look like a valid email address to me.'
]);

$v->validate([
    'username'  => ['', 'required|alpha|min(3)|max(20)'],
    'email'     => ['dale.codecourse.com', 'required|email'],
    'password'  => ['ilovecats', 'required'],
    'password_confirm' => ['ilovecats', 'required|matches(password)']
]);

if ($v->passes()) {
    // Passed
} else {
    var_dump($v->errors()->all());
}
