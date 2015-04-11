<?php

require '../vendor/autoload.php';

use Violin\Violin;

$v = new Violin;

$v->validate([
    'username|Username'  => ['', 'required'],
    'password|Password'  => ['', 'required']
]);

if ($v->passes()) {
    // Passed
} else {
    var_dump($v->errors()->all());
}
