# violin

[![Build Status](https://travis-ci.org/alexgarrett/violin.svg?branch=master)](https://travis-ci.org/alexgarrett/violin)

Violin is an easy to use, highly customisable PHP validator.

**Note: This package is under heavy development and is not recommended for production.**

## Installing

Install using Composer.

```json
{
    "require": {
        "alexgarrett/violin": "2.*"
    }
}
```

## Basic usage

```php
use Violin\Violin;

$v = new Violin;

$v->validate([
    'name'  => 'billy',
    'age'   => 20
], [
    'name'  => 'required',
    'age'   => 'required|int'
]);

if($v->passes()) {
    echo 'Validation passed, woo!';
} else {
    echo '<pre>', var_dump($v->errors()->all()), '</pre>';
}
```

## Adding custom rules

Adding custom rules is simple. If the closure returns false, the rule fails.

```php
$v->addRuleMessage('isbanana', 'The {field} field expects "banana", found "{value}" instead.');

$v->addRule('isbanana', function($field, $value) {
    return $value === 'banana';
});

$v->validate([
    'fruit' => 'apple'
], [
    'fruit' => 'isbanana'
]);
```

## Adding custom error messages

You can add rule messages, or field messages for total flexibility.

### Adding a rule message

```php
$v->addRuleMessage('required', 'You better fill in the {field} field, or else.');
```

### Adding rule messages in bulk

```php
$v->addRuleMessages([
    'required' => 'You better fill in the {field} field, or else.',
    'int'      => 'The {field} needs to be an integer, but I found {value}.',
]);
```

### Adding a field message

Any field messages you add are used before any default or custom rule messages.

```php
$v->addFieldMessage('username', 'required', 'You need to enter a username to sign up.');
```

### Adding field messages in bulk

```php
$v->addFieldMessages([
    'username' => [
        'required' => 'You need to enter a username to sign up.'
    ],
    'age' => [
        'required' => 'I need your age.',
        'int'      => 'Your age needs to be an integer.',
    ]
]);
```

## Rules

This list of rules are **in progress**. Of course, you can always contribute to the project if you'd like to add more to the base ruleset.

#### alnum

If the value is alphanumeric.

#### alnumDash

If the value is alphanumeric. Dashes and underscores are permitted.

#### alpha

If the value is alphabetic letters only.

#### alphaDash

If the value is alphabetic letters only. Dashes and underscores are permitted.

#### array

If the value is an array.

#### between(int, int)

Checks if the value is within the intervals defined.

#### bool

If the value is a boolean.

#### email

If the value is a valid email.

#### int

If the value is an integer, including integers within strings. 1 and '1' are both classed as integers.

#### ip

If the value is a valid IP address.

#### max(int)

Checks if the value is less than or equal to the given parameter.

#### min(int)

Checks if the value is greater than or equal to the given parameter.

#### required

If the value is present.

#### url

If the value is formatted as a valid URL.

#### matches(field)

Checks if one given input matches the other. For example, checking if *password* matches *password_confirm*.

## Contributing

Please file issues under GitHub, or submit a pull request if you'd like to directly contribute.

### Running tests

Tests are run with phpunit. Run `./vendor/bin/phpunit` to run tests.